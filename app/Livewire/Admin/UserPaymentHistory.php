<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Payment;
use App\Models\Order;
use App\Models\PaymentItem;
use App\Models\AsignedVehicle;
use App\Models\OrderMerchantNumber;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
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
    public function FetchPayment($merchantTxnNo,$txnID,$paymentMode,$paymentDateTime){

        $OrderMerchantNumber = OrderMerchantNumber::where('merchantTxnNo',$merchantTxnNo)->first();
        if(!$OrderMerchantNumber){
            session()->flash('payment_fetch_error', 'No data found by this merchantTxnNo.');
            return false;
        }
        if($OrderMerchantNumber->type==='new'){
            DB::beginTransaction();
            try{
                $status = true;
                $order_amount = $OrderMerchantNumber->amount;
                if($status==true){
                    $order = Order::find($OrderMerchantNumber->order_id);
                    $amount = number_format($order_amount, 2, '.', '');
                    $orderAmount = number_format($order->final_amount, 2, '.', '');

                    if ($orderAmount !== $amount) {
                        session()->flash('payment_fetch_error', "Sorry, the payment amount (₹$amount) does not match the subscription amount (₹$orderAmount).");
                        return false;
                    }
                    if($order->payment_status=="completed"){
                        session()->flash('payment_fetch_error', "Payment already completed for this subscription.");
                        return false;
                    }

                    $order_type = $order->subscription?Str::snake($order->subscription->subscription_type):"";
                    $payment = Payment::where('icici_merchantTxnNo',$merchantTxnNo)->first();
                    if(!$payment){
                        session()->flash('payment_fetch_error', "Payment details not found on this merchantTxnNo.");
                        return false;
                    }
                    $payment->order_id = $order->id;
                    $payment->user_id = $order->user_id;
                    $payment->order_type = 'new_subscription_'.$order_type;
                    $payment->payment_method = $paymentMode;
                    $payment->currency = "INR";
                    $payment->payment_status = 'completed';
                    $payment->transaction_id = $paymentDateTime;
                    $payment->amount = $order->final_amount;
                    $payment->icici_txnID = $txnID;
                    $payment->payment_date = date('Y-m-d h:i:s', strtotime($paymentDateTime));
                    $payment->save();
                    if($payment){
                        // Deposit Amount
                        PaymentItem::updateOrCreate(
                            [
                                'payment_id' => $payment->id,
                                'product_id' => $order->product_id,
                                'type'       => 'deposit',
                            ],
                            [
                                'payment_for' => 'new_subscription_' . $order_type,
                                'duration'    => $order->rent_duration,
                                'amount'      => $order->deposit_amount,
                            ]
                        );

                        // Rental Amount
                        PaymentItem::updateOrCreate(
                            [
                                'payment_id' => $payment->id,
                                'product_id' => $order->product_id,
                                'type'       => 'rental',
                            ],
                            [
                                'payment_for' => 'new_subscription_' . $order_type,
                                'duration'    => $order->rent_duration,
                                'amount'      => $order->rental_amount,
                            ]
                        );
                    }

                    $order->payment_mode = "Online";
                    $order->payment_status = "completed";
                    $order->rent_status = "ready to assign";
                    $order->subscription_type = 'new_subscription_'.$order_type;
                    $order->save();

                    DB::commit();
                    session()->flash('payment_fetch_success', "Payment has been successfully created.");
                    
                }else{
                    session()->flash('payment_fetch_error', "Payment failed. Please try again.");
                    return false;
                }    
            } catch (\Exception $e) {
                DB::rollBack();
                // dd($e->getMessage());
                session()->flash('payment_fetch_error', $e->getMessage());
                // return response()->json([
                //     'status' => false,
                //     'message' => 'Failed to update payment.',
                //     'error' => $e->getMessage(),
                // ], 500);
            }
        }else{
            $status = true;
            $order = Order::with('subscription')->find($OrderMerchantNumber->order_id);
            DB::beginTransaction();
            try{
                if($status==true){

                    $existing_payment = Payment::where('icici_merchantTxnNo',$merchantTxnNo)->first();
                    if(!$existing_payment){
                        session()->flash('payment_fetch_error', "Payment details not found on this merchantTxnNo.");
                        return false;
                    }else{
                        $assignRider = AsignedVehicle::where('order_id', $order->id)->first();

                        $order_type = $order->subscription?Str::snake($order->subscription->subscription_type):"";
                        $payment = Payment::find($existing_payment['id']);
                        $payment->order_id = $order->id;
                        $payment->user_id = $order->user_id;
                        $payment->order_type = 'renewal_subscription_'.$order_type;
                        $payment->payment_method = $paymentMode;
                        $payment->currency = "INR";
                        $payment->payment_status = 'completed';
                        $payment->transaction_id = $paymentDateTime;
                        $payment->icici_txnID = $txnID;
                        $payment->payment_date = date('Y-m-d h:i:s', strtotime($paymentDateTime));

                        $payment->amount = $order->subscription ? $order->subscription->rental_amount : $order->rental_amount;
                        $payment->payment_date = date('Y-m-d h:i:s');
                        $payment->save();
            
                        if($payment){
                            // Rental Amount using updateOrCreate
                            $payment_item = PaymentItem::updateOrCreate(
                                [
                                    'payment_id' => $payment->id,
                                    'type' => 'rental',
                                ],
                                [
                                    'product_id' => $order->product_id,
                                    'payment_for' => 'renewal_subscription_' . $order_type,
                                    'vehicle_id' => $assignRider->vehicle_id,
                                    'amount' => $order->subscription ? $order->subscription->rental_amount : $order->rental_amount,
                                    'duration' => $order->subscription ? $order->subscription->duration : $order->rent_duration,
                                ]
                            );

                            // Calculate dates
                            $startDate = Carbon::parse($assignRider->end_date);
                            $endDate = $startDate->copy()->addDays($payment_item->duration);

                            // Update Order
                            $order->payment_mode = "Online";
                            $order->payment_status = "completed";
                            $order->rental_amount = $payment_item->amount;
                            $order->total_price = $order->deposit_amount + $payment_item->amount;
                            $order->final_amount = $order->deposit_amount + $payment_item->amount;
                            $order->rent_duration = $payment_item->duration;
                            $order->rent_start_date = $startDate;
                            $order->rent_end_date = $endDate;
                            $order->subscription_type = 'renewal_subscription_' . $order_type;
                            $order->save();
            
                            
            
                            DB::table('exchange_vehicles')->insert([
                                'status'       => "renewal",
                                'user_id'      => $assignRider->user_id,
                                'order_id'     => $assignRider->order_id,
                                'vehicle_id'   => $assignRider->vehicle_id,
                                'start_date'   => $assignRider->start_date,
                                'end_date'     => $assignRider->end_date,
                                'created_at'   => now(),
                                'updated_at'   => now(),
                            ]); 
            
                            $assignRider->start_date = $startDate;
                            $assignRider->end_date = $endDate;
                            $assignRider->status = "assigned";
                            $assignRider->save();

                            DB::commit();
                            session()->flash('payment_fetch_success', "Payment completed and subscription renewed successfully.");
                        }
                    }
                }
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Payment Failed', [
                    'response' => $e->getMessage()
                ]);
                session()->flash('payment_fetch_error', $e->getMessage());
            }
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
        ->orderBy('payment_date', 'DESC')
        ->paginate(25);
         
        return view('livewire.admin.user-payment-history', [
            'data' => $data,
        ]);
    }
}
