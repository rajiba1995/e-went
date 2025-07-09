<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Payment;
use App\Models\PaymentItem;

class UserPaymentSummaryExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $selected_rider, $selected_product_type, $selected_payment_status, $start_date, $end_date, $export_type;

    public function __construct($selected_rider, $selected_product_type, $selected_payment_status, $start_date, $end_date,$export_type)
    {
        $this->selected_rider = $selected_rider;
        $this->selected_product_type = $selected_product_type;
        $this->selected_payment_status = $selected_payment_status;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->export_type = $export_type;
    }
    public function collection()
    {
        $data = Payment::when($this->selected_rider, function ($query) {
                $query->where('user_id', $this->selected_rider);
            })
            ->when($this->export_type=="deposit", function ($query) {
                $query->where('order_type', 'like', 'new_subscription_%');
            })
            // ->when($this->export_type=="rental", function ($query) {
            //     $query->where('order_type', 'like', 'renewal_subscription_%');
            // })
            ->when($this->selected_product_type, function ($query) {
                $query->where('order_type', $this->selected_product_type);
            })
            ->when($this->selected_payment_status, function ($query) {
                $query->where('payment_status', $this->selected_payment_status);
            })
            ->when($this->start_date && $this->end_date, function ($query) {
                $query->whereBetween('payment_date', [$this->start_date. ' 00:00:00', $this->end_date . ' 23:59:59']);
            })
            ->when($this->start_date && !$this->end_date, function ($query) {
                $query->whereDate('payment_date', '>=', $this->start_date);
            })
            ->when(!$this->start_date && $this->end_date, function ($query) {
                $query->whereDate('payment_date', '<=', $this->end_date);
            })
            ->orderBy('id', 'DESC')
            ->get()->map(function ($payment) {
            return [
                'user_name' => $payment->user ? $payment->user->name : 'N/A', // User Name
                'product_name' => optional(optional($payment->order)->product)->title ?? 'N/A',
                'order_type' => ucwords(str_replace('_', ' ', $payment->order_type)),
                'payment_method' => ucwords($payment->payment_method),
                'payment_status' => ucwords($payment->payment_status),
                'icici_txnID' => $payment->icici_txnID,
                'ride_duration' => optional(PaymentItem::where('payment_id', $payment->id)->first())->duration 
                ? optional(PaymentItem::where('payment_id', $payment->id)->first())->duration . ' Days' 
                : '0 Days',
                'currency' => $payment->currency,
                'rental_amount' => PaymentItem::where('payment_id', $payment->id)
                ->where('type', 'rental')
                ->first()?->amount ?? 0,
                'deposit_amount' => PaymentItem::where('payment_id', $payment->id)
                ->where('type', 'deposit')
                ->first()?->amount ?? 0,
                'total_amount' => $payment->amount,
                'payment_date' => date('Y-m-d', strtotime($payment->payment_date)),
            ];
        })
        ->toArray();

        if($this->export_type == "deposit"){
            $deposit_data = [];
            foreach($data as $key=>$item){
                $deposit_data []= [
                    'voucher_number' =>$key+1,
                    'name_of_debit_account' =>'Rental Deposit',
                    'debit' =>'Dr',
                    'credit_account' =>$item['user_name'],
                    'credit' =>'Cr',
                    'NARRATION' =>'payment_trasaction_id:'.$item['icici_txnID'],
                    'rental_deposit' =>$item['deposit_amount'],
                    'payment_date'=>date('d-m-Y',strtotime($item['payment_date'])),
                ];
            }
             return collect($deposit_data);
        }elseif($this->export_type == "rental"){
            $rental_data = [];
            foreach($data as $key=>$item){
                 // Rental amount (includes 5% tax)
                $rentalAmount = $item['rental_amount'];

                // Calculate taxable amount (before GST)
                $taxableAmount = round($rentalAmount / 1.05, 2);

                // CGST and SGST each 2.5% of taxable amount
                $cgst = round($taxableAmount * 0.025, 2);
                $sgst = round($taxableAmount * 0.025, 2);

                $rental_data[] = [
                    'sl_number' =>$key+1,
                    'rider_name'       => $item['user_name'],
                    'order_type'       => $item['order_type'],
                    'voucher_number'   => $item['icici_txnID']?'V'.$item['icici_txnID']:"",
                    'sales_account'    => 'Rental Income From ' . $item['product_name'],
                    'NARRATION'        => 'payment_trasaction_id: ' . $item['icici_txnID'] . ', Ride Duration: ' . $item['ride_duration'],
                    'currency'         => $item['currency'],
                    'taxable_amount'   => $taxableAmount,
                    'CGST'             => $cgst,
                    'SGST'             => $sgst,
                    'total_amount'     => $rentalAmount,
                    'payment_date'     => date('d-m-Y',strtotime($item['payment_date'])),
                ];
            }
             return collect($rental_data);
        }else{
            return collect($data);
        }
        
    }

    public function headings(): array
    {
        if($this->export_type == "deposit"){
            return [
                'Voucher number',
                'Name of debit account',
                'debit',
                'credit account(Rider Name)',
                'credit',
                'NARRATION',
                'Rental Deposit',
                'Payment Date',
            ];
        }elseif($this->export_type == "rental"){
            return [
                'Serial Number',
                'Rider Name',
                'Order Type',
                'Voucher Number',
                'sales account',
                'NARRATION',
                'Currency',
                'taxable amount',
                'CGST',
                'SGST',
                'Total Amount',
                'Payment Date',
            ];
        }else{
            return [
                'Rider Name',
                'Product',
                'Order Type',
                'Payment Method',
                'Payment Status',
                // 'Razorpay Order ID',
                'Transaction ID',
                'Subscription Duration',
                'Currency',
                'Rental Amount',
                'Deposit Amount',
                'Total Amount',
                'Payment Date',
            ];
        }
        
    }
}
