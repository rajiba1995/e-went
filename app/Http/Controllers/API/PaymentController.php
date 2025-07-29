<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Banner;
use App\Models\WhyEwent;
use App\Models\UserKycLog;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Stock;
use App\Models\SellingQuery;
use App\Models\Payment;
use App\Models\PaymentItem;
use App\Models\Offer;
use App\Models\RentalPrice;
use App\Models\Order;
use App\Models\PaymentLog;
use App\Models\DigilockerDocument;
use App\Models\AsignedVehicle;
use App\Models\UserTermsConditions;
use App\Models\Policy;
use App\Models\OrderMerchantNumber;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\UserLocationLog;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function handleIPN(Request $request)
    {
      try {
        //  Log::info('PhiCommerce IPN Received', $request->all());

        $response = $request->all(); // Get all data
        if (!empty($response)) {
            Log::info('PhiCommerce IPN Received', $response);

            PaymentLog::create([
                'gateway' => 'ICICI',
                'transaction_id' => $response['txnID'] ?? null,
                'merchant_txn_no' => $response['merchantTxnNo'] ?? null,
                'response_payload' => json_encode($response),
                'status' => $response['responseCode'] ?? null,
                'message' => isset($response['respDescription']) ? $response['respDescription'] . ' (authorized)' : null,
            ]);
        } else {
            Log::warning('PhiCommerce IPN Received with empty payload');
        }
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
        // if (!$OrderMerchantNumber) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => "No data found by this merchantTxnNo.",
        //     ], 200);

        // }
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
              return response()->json([
                'status' => true,
                'message' => "Payment Successfull.",
            ], 200);
        }
      } catch (Exception $ex) {
       echo "<pre>";print_r($ex);exit;
        //throw $th;
      }
        // Log all data for debugging




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
       protected function bookingRenewICICIPayment($merchantTxnNo,$txnID,$paymentMode,$paymentDateTime){
        $OrderMerchantNumber = OrderMerchantNumber::where('merchantTxnNo',$merchantTxnNo)->first();

        if(!$OrderMerchantNumber){
            return response()->json([
                'status' => false,
                'message' => 'No data found by this merchantTxnNo.',
            ], 400);
        }

        $status = true;
        $order = Order::with('subscription')->find($OrderMerchantNumber->order_id);
        DB::beginTransaction();
        try{
            if($status==true){
                $existing_payment = Payment::where('icici_merchantTxnNo',$merchantTxnNo)->first();
                if(!$existing_payment){
                    return response()->json([
                        'status' => false,
                        'message' => "Payment details not found on this merchantTxnNo.",
                    ], 404);
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
                    $payment->payment_date = now()->toDateTimeString();
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

                        $asigned_vehicle = Stock::where('id',$assignRider->vehicle_id)->first();
                        if($asigned_vehicle){
                            if($asigned_vehicle->immobilizer_status=="IMMOBILIZE"){
                                $this->MobilizationRequest($assignRider->vehicle_id);
                            }else{
                                $asigned_vehicle->immobilizer_status = "MOBILIZE";
                                $asigned_vehicle->immobilizer_request_id = null;
                                $asigned_vehicle->save();
                            }
                        }

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

                        return response()->json([
                            'status' => true,
                            'message' => "Payment completed and subscription renewed successfully.",
                        ], 200);
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Failed', [
                'response' => $e->getMessage()
            ]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
