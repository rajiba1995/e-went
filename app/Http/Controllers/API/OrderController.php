<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\RentalPrice;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
   public function ExistingPaymentType(){
        $data = GetAllActivePaymentType();
        return response()->json(['status'=>true, 'data'=>$data, 'message'=>'Data successfully retrieved'], 200);
   }

   public function ApplyCoupon(Request $request){
        $validator = validator::make($request->all(),[
            'coupon_code' => 'required|string',
            'order_amount' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

         // Use the helper function
        $result = checkCouponValue(
            $request->coupon_code,
            $request->order_amount,
            $request->user_id
        );

        return response()->json($result, $result['status'] ? 200 : 400);
   }

  

   public function createOrder(Request $request){
        // coupon_code
        // discount_amount
        $validator = Validator::make($request->all(),[
            'user_id' => 'required|exists:users,id',
            'order_type' => 'required|in:Rent,Sell',
            'order_items' => 'required|array',
            'order_items.*.product_id' => 'required|exists:products,id',
            'order_items.*.quantity' => 'required|integer|min:1',
            'payment_type' => 'required|in:Card,COD,PhonePay,GooglePay,UPI,NetBanking,Wallet',
            'payment_mode' => 'required|in:Online,Offline',
            'shipping_address' => 'nullable|string',
            'offer_id' => 'nullable|exists:offers,id',
            'rent_duration' => 'required_if:order_type,Rent|integer|min:1',
            'rent_start_date' => 'required_if:order_type,Rent|date',
        ]);

        DB::beginTransaction();
        try{

            // Calculate Total Price
            $totalPrice = 0;
            $finalPrice = 0;
            foreach($request->order_items as $item){
                $product = Product::findOrFail($item['product_id']);
                $productPrice = RentalPrice::where('product_id',$item['product_id'])->where('duration', $item['duration'])->first();
                    if(!isset($productPrice)){
                        return response()->json([
                            'status' => false,
                            'message' => 'Sorry! This duration does not exist for this product.',
                        ], 400);
                    }
                if($request->order_type==="Rent"){
                    $totalPrice+=$productPrice->price*$item['quantity'];
                }elseif($request->order_type==="Sell"){
                    $totalPrice+=$product->display_price*$item['quantity'];
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'Sorry! this is unknown order type',
                    ], 400);
                }
            }
            // Calculate the Discount Price
            
            $discountAmount = 0;
            if($request->offer_id){
                $result = checkCouponValue(
                    $request->coupon_code,
                    $totalPrice,
                    $request->user_id
                );
                if($result['status']==false){
                    return response()->json($result, $result['status'] ? 200 : 400);
                }
                
                $discountAmount  = $result['discount'];
                $finalPrice = $totalPrice-$result['discount'];
               
                if ($request->discount_amount != $result['discount']) {
                    return response()->json([
                        'status' => false,
                        'message' => 'The discount amount provided does not match the calculated discount. Please verify and try again.'
                    ], 400);
                }
            }
                $finalPrice = $finalPrice==0?$totalPrice:$finalPrice;


                $order = Order::create([
                    'user_id' => $request->user_id,
                    'order_type' => $request->order_type,
                    'order_number' => generateOrderNumber(),
                    'total_price' => $totalPrice,
                    'discount_amount' => $discountAmount,
                    'final_amount' => $finalPrice,
                    'quantity' => array_sum(array_column($request->order_items, 'quantity')),
                    'status' => 'pending',
                    'offer_id' => $request->offer_id?$request->offer_id:null,
                    'payment_type' => $request->payment_type,
                    'payment_status' => $request->payment_type==="Wallet"?"completed":"pending",
                    'payment_mode' => $request->payment_mode,
                    'shipping_address' => $request->shipping_address,
                    'rent_duration' => $request->order_type === 'Rent' ? $request->rent_duration : null,
                    'rent_start_date' => $request->order_type === 'Rent' ? Carbon::parse($request->rent_start_date) : null,
                    'rent_end_date' => $request->order_type === 'Rent' 
                        ? Carbon::parse($request->rent_start_date)->addDays($request->rent_duration) 
                        : null,
                    'rent_status' => $request->order_type === 'Rent' ? 'pending' : null,
                ]);

                // Create Order Items

                foreach($request->order_items as $order_item){
                    $productPrice = RentalPrice::where('product_id',$order_item['product_id'])->where('duration', $order_item['duration'])->first();
                    $product = Product::findOrFail($order_item['product_id']);
                    $item_price = 0;
                    if($request->order_type==="Rent"){
                        $item_price=$productPrice->price;
                    }elseif($request->order_type==="Sell"){
                        $item_price=$product->display_price;
                    }
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $order_item['quantity'],
                        'price' => $item_price,
                        'total_price' => $item_price * $order_item['quantity'],
                    ]);

                    $product->stock_qty = $product->stock_qty-$order_item['quantity'];
                    $product->stock = $product->stock_qty==0?0:1;
                    $product->save();
                }
                
                // For Wallet Payment
                if($request->payment_type==="Wallet"){
                    $existing_user_wallet = Wallet::where('user_id', $order->user_id)->first();

                    if (!$existing_user_wallet) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Wallet not found. Please set up your wallet.',
                        ], 400);
                    }
                    if ((float) $existing_user_wallet->balance < (float) $finalPrice) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Insufficient wallet balance.',
                        ], 400);
                    }

                    $updated_wallet_balance = $existing_user_wallet->balance-$finalPrice;
                    $wallet = $existing_user_wallet->update([
                        'balance' => $updated_wallet_balance,
                    ]);
                    $wallet_transaction =WalletTransaction::create([
                         'wallet_id'=>$existing_user_wallet->id, 'order_id'=>$order->id, 'transaction_type'=>'debit', 'amount'=>$finalPrice, 'description'=>'Purpose of New Order'
                    ]);

                }

                // Create payment entry
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $request->payment_type, // e.g., 'credit_card', 'upi'
                    'payment_status' => $request->payment_type==="Wallet"?"completed":"pending",
                    'amount' => $finalPrice,
                    'currency' => env('APP_CURRENCY_NAME', 'INR'), // Adjust as per your system
                ]);



                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Order created successfully.',
                    'order' => $order,
                ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to create order.',
                'error' => $e->getMessage(),
            ], 500);
        }
   }
}
