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

        $OrderMerchantNumber = OrderMerchantNumber::where('merchantTxnNo', $merchantTxnNo)->first();

        $message = '';
        $success_message = '';
        // Case: Invalid merchantTxnNo
        if (!$OrderMerchantNumber) {
            $message = 'No data found by this merchantTxnNo.';
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

            }
            // Extract message
            Log::info("Payment Successfull");


        }



    }
}
