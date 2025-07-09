<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Payment;
use App\Models\PaymentItem;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserPaymentSummaryExport;

class UserPaymentHistory extends Component
{
    public $filterData = [];
    public $expandedRows = [];
    public $transaction_details = [];
    public $selected_rider,$selected_product_type,$selected_payment_status,$start_date,$end_date,$export_type;
    public function mount(){

        $this->filterData = [
            'rider' => User::select('id', 'name')->get()->toArray(),
            'product_type' => Payment::select('order_type')->whereNotNull('order_type')->distinct()->pluck('order_type')->toArray(),
            'payment_status' => Payment::select('payment_status')->distinct()->pluck('payment_status')->toArray(),
        ];
    }
    public function updateFilters($value,$field){
        $this->$field = $value;
    }
    public function resetPageField(){
        $this->reset(['selected_rider','selected_product_type','selected_payment_status','start_date','end_date','export_type']);
    }

   public function toggleRow($key, $merchantTxnNo,$amount)
    {
        $this->transaction_details[$key] = $this->paymentFetch($merchantTxnNo,$amount);
        if (in_array($key, $this->expandedRows)) {
            $this->expandedRows = array_diff($this->expandedRows, [$key]);
        } else {
            $this->expandedRows[] = $key;
        }
    }

    public function paymentFetch($merchantTxnNo,$amount)
    {
        $merchantID = env('ICICI_MARCHANT_ID');
        $transactionType = 'STATUS';

        // Retrieve these from DB if needed
        $originalTxnNo = $merchantTxnNo; // Ideally, fetch actual amount from your DB using this txn no

        // Optional: Only include if the transaction was aggregator-initiated
        $aggregatorID = env('ICICI_AGGREGATOR_ID');
        $aggregatorSecretKey = env('ICICI_MARCHANT_SECRET_KEY');

        // Create secureHash (optional but usually required)
        $hashString = $amount . $merchantID . $merchantTxnNo . $originalTxnNo . $transactionType;
        $secureHash = hash_hmac('sha256', $hashString, $aggregatorSecretKey);

        $postData = [
            'merchantID'       => $merchantID,
            'merchantTxnNo'    => $merchantTxnNo,
            'originalTxnNo'    => $originalTxnNo,
            'transactionType'  => $transactionType,
            'amount'           => $amount,
            'secureHash'       => $secureHash,
            // Only include aggregatorID if needed
            // 'aggregatorID'     => $aggregatorID,
        ];

        // Make cURL request
        $ch = curl_init(env('ICICI_PAYMENT_CHECK_STATUS_BASH_URL'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

       $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Capture HTTP response code
        curl_close($ch);
        if ($httpCode == 200) {
            return json_decode($response, true);
        } else {
            return [
                'status' => false,
                'message' => "Failed to capture payment.",
                'error' => json_decode($response, true)
            ];
        }
    }

     public function exportAll()
    {
        if (!$this->export_type) {
            session()->flash('error', 'Please select export type');
                return false;
        }
        return Excel::download(new UserPaymentSummaryExport($this->selected_rider, $this->selected_product_type, $this->selected_payment_status, $this->start_date, $this->end_date,$this->export_type), 'user_payment_history.xlsx');
    }

    public function render()
    {
        $data = Payment::when($this->selected_rider, function ($query) {
            $query->where('user_id', $this->selected_rider);
        })
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
        ->paginate(25);
         
        return view('livewire.admin.user-payment-history', [
            'data' => $data,
        ]);
    }
}
