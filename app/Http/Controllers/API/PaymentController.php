<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentLog;
use App\Models\Payment;

use App\Models\OrderMerchantNumber;

use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function handleIPN(Request $request)
    {
        // Log all data for debugging
        Log::info('PhiCommerce IPN Received', $request->all());
        $response = $request->all(); // Get all data
         PaymentLog::create([
            'gateway' => 'ICICI',
            'transaction_id' => $response['txnID'] ?? null,
            'merchant_txn_no' => $response['merchantTxnNo'] ?? null,
            'response_payload' => json_encode($response),
            'status' => $response['responseCode'] ?? null,
            'message' => isset($response['respDescription']) ? $response['respDescription'] . '(authorized)' : null,
        ]);
        $merchantTxnNo = $response['merchantTxnNo'] ?? null;

        $payment = Payment::where('icici_merchantTxnNo', $merchantTxnNo)->first();

        if ($payment) {
            $payment->icici_txnID = $response['txnID'] ?? null;
            $payment->save();
        }
        $OrderMerchantNumber = OrderMerchantNumber::where('merchantTxnNo', $merchantTxnNo)->first();

        $message = '';
        $success_message = '';
        // Case: Invalid merchantTxnNo
        if (!$OrderMerchantNumber) {
           // $message = 'No data found by this merchantTxnNo.';
            //return view('icici.thanks', compact('message'));
        }
        // Case: Payment success
        if (
            isset($response['respDescription']) &&
            $response['respDescription'] === 'Transaction successful'
        )
        {
            PaymentLog::create([
                'gateway' => 'ICICI',
                'transaction_id' => $response['txnID'] ?? null,
                'merchant_txn_no' => $response['merchantTxnNo'] ?? null,
                'response_payload' => json_encode($response),
                'status' => $response['responseCode'] ?? null,
                'message' => isset($response['respDescription']) ? $response['respDescription'] . '(completed)' : null,
            ]);
          Log::info('Pyment Successfull');

          if(!empty($OrderMerchantNumber->type) and $OrderMerchantNumber->type==='new'){
                 Log::error('bookingNewICICIPayment data', [
                    'merchantTxnNo'     => $merchantTxnNo,
                    'txnID'             => $response['txnID'] ?? null,
                    'paymentMode'       => $response['paymentMode'] ?? null,
                    'paymentDateTime'   => $response['paymentDateTime'] ?? null,
                ]);
                $bookingResponse = $this->bookingNewICICIPayment(
                    $merchantTxnNo,
                    $response['txnID'],
                    $response['paymentMode'],
                    $response['paymentDateTime']
                );
            }
            else{

                Log::error('bookingRenewICICIPayment data', [
                    'merchantTxnNo'     => $merchantTxnNo,
                    'txnID'             => $response['txnID'] ?? null,
                    'paymentMode'       => $response['paymentMode'] ?? null,
                    'paymentDateTime'   => $response['paymentDateTime'] ?? null,
                ]);
                $bookingResponse = $this->bookingRenewICICIPayment(
                    $merchantTxnNo,
                    $response['txnID'],
                    $response['paymentMode'],
                    $response['paymentDateTime']
                );
            }

        }



    }
      protected function bookingNewICICIPayment($merchantTxnNo,$txnID,$paymentMode,$paymentDateTime){

        $OrderMerchantNumber = OrderMerchantNumber::where('merchantTxnNo',$merchantTxnNo)->first();

        if(!$OrderMerchantNumber){
            return response()->json([
                'status' => false,
                'message' => 'No data found by this merchantTxnNo.',
            ], 400);
        }
        DB::beginTransaction();
        try{
            $status = true;
            $order_amount = $OrderMerchantNumber->amount;
            // $razorpay_order_id = $request->razorpay_order_id;
            // $razorpay_payment_id = $request->razorpay_payment_id;
            // $razorpay_signature = $request->razorpay_signature;
            if($status==true){
                $order = Order::find($OrderMerchantNumber->order_id);
                $amount = number_format($order_amount, 2, '.', '');
                $orderAmount = number_format($order->final_amount, 2, '.', '');

                if ($orderAmount !== $amount) {
                    return response()->json([
                        'status' => false,
                        'message' => "Sorry, the payment amount (₹$amount) does not match the subscription amount (₹$orderAmount).",
                    ], 403);
                }
                if($order->payment_status=="completed"){
                    return response()->json([
                        'status' => false,
                        'message' => "Payment already completed for this subscription.",
                    ], 403);
                }

                $order_type = $order->subscription?Str::snake($order->subscription->subscription_type):"";
                $payment = Payment::where('icici_merchantTxnNo',$merchantTxnNo)->first();
                if(!$payment){
                    return response()->json([
                        'status' => false,
                        'message' => "Payment details not found on this merchantTxnNo.",
                    ], 404);
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

                return response()->json([
                    'status' => true,
                    'message' => "Payment has been successfully created.",
                ], 200);

            }else{
                return response()->json([
                    'status' => false,
                    'message' => "Payment failed. Please try again.",
                ], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to update payment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
