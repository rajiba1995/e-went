<?php
use App\Models\User;
use App\Models\AdminRating;
use App\Models\ProductReview;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Pincode;
use App\Models\Stock;
use App\Models\AsignedVehicle;
use App\Models\Permission;
use App\Models\DesignationPermission;
use Illuminate\Support\Facades\Auth;

if (!function_exists('storeFileWithCustomName')) {
    function storeFileWithCustomName($file, $directory)
    {
       // Ensure the directory exists
       $path = storage_path("app/public/$directory");
       if (!is_dir($path)) {
           mkdir($path, 0755, true);
       }
       // Generate a custom filename: random 4 digits + timestamp + file extension
       $filename = rand(1000, 9999) . '_' . time() . '.' . $file->getClientOriginalExtension();
    //    dd($filename);
       // Store the file in the specified directory and return its path
       $file->storeAs($directory, $filename, 'public');
       return 'storage/' . $directory . '/' . $filename;
    }
}
if (!function_exists('MakingCustomerId')) {
    function MakingCustomerId(){
        do {
            // Generate a new customer ID
            $lastCustomer = User::orderBy('id', 'desc')->first();
            $customerId = 'EW-' . str_pad($lastCustomer ? $lastCustomer->id + 1 : 1, 8, '0', STR_PAD_LEFT);
    
            // Check if the generated ID already exists in the database
            $exists = User::where('customer_id', $customerId)->exists();
    
        } while ($exists);
    
        return $customerId;
    }
}

if (!function_exists('CheckUserStatus')) {
    function CheckUserStatus($id) {
        // Get the user's status for all required fields
        $data = User::select('driving_licence_status', 'aadhar_card_status', 'pan_card_status', 'passbook_status', 'current_address_proof_status','profile_image_status')
                    ->where('id', $id)
                    ->first();

        // Check if all the status fields are equal to 2
        if ($data && $data->driving_licence_status == 2 && 
            $data->aadhar_card_status == 2 && 
            $data->pan_card_status == 2 && 
            $data->passbook_status == 2 && 
            $data->profile_image_status == 2 && 
            $data->current_address_proof_status == 2) {
            return true;  // Return 1 if all fields are 2
        }

        return false;  // Return 0 if any field is not 2
    }
}
if(!function_exists('AdminRatings')){
    function AdminRatings($id){
        $ratings =AdminRating::where('user_id', $id)->get();

        //check if any ratings founds
        if($ratings->isEmpty()){
            return 0; //If not ratings, return 0
        }

        //Calculate the average rating
        $averateRating = $ratings->avg('rating');//Assuming 'rating' is the column for ratings
        return round($averateRating, 1);//Round to 1 decimal for better precision
    }
}

if (!function_exists('ProductReviews')) {
    function ProductReviews($id) {
        // Retrieve product reviews with user details (name, profile_image)
        $reviews = ProductReview::select('product_id', 'user_id', 'rating', 'review')
            ->with([
                'user' => function($query) {
                    $query->select('id', 'name', 'profile_image');  // Specify columns to retrieve from the user table
                }
            ])
            ->where('product_id', $id)
            ->get();

        // Format the reviews to match the desired structure
        return $reviews->map(function($review) {
            if($review->user){
                return [
                    'name' => $review->user->name,
                    'profile_image' => $review->user->profile_image,
                    'rating' => $review->rating,
                    'review' => $review->review
                ];
            }
        });
    }
}

if (!function_exists('GetAllActivePaymentType')) {
    function GetAllActivePaymentType() {
        return $data = [
            [
                'title' => 'Card',
                'value'=>'Card',
                'image' => 'assets/img/card.png'
            ],
            [
                'title' => 'Cash on Delivery (COD)',
                'value'=>'COD',
                'image' => 'assets/img/cash-on-delivery.png'
            ],
            [
                'title' => 'PhonePay',
                'value'=>'PhonePay',
                'image' => 'assets/img/phonepe.png'
            ],
            [
                'title' => 'GooglePay',
                'value'=>'GooglePay',
                'image' => 'assets/img/gpay.png'
            ],
            [
                'title' => 'UPI',
                'value'=>'UPI',
                'image' => 'assets/img/upi.png'
            ],
            [
                'title' => 'NetBanking',
                'value'=>'NetBanking',
                'image' => 'assets/img/net-banking.png'
            ],
            [
                'title' => 'Wallet',
                'value'=>'Wallet',
                'image' => 'assets/img/wallet.png'
            ]
        ];
    }
}

if(!function_exists('checkCouponValue')){
    function checkCouponValue($couponCode, $orderAmount, $userId){

        $current_date = date('Y-m-d H:i:s'); // Use 'H' for 24-hour format

        // Fetch Offer
        $offer = Offer::where('coupon_code', $couponCode)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $current_date)
            ->whereDate('end_date', '>=', $current_date)
            ->first();
        if (!$offer) {
            return [
                'status' => false,
                'message' => 'Invalid or expired coupon.',
            ];
        }
        // Validate the minimum order amount
        if ($offer->minimum_order_amount && $orderAmount < $offer->minimum_order_amount) {
            return [
                'status' => false,
                'message' => 'Order amount does not meet the minimum required for this coupon.',
            ];
        }

        // Check global usage limit
        if($offer->usage_limit){
            $global_usage_order = Order::where('offer_id', $offer->id)->count();
            if ($global_usage_order >= $offer->usage_limit) {
                return [
                    'status' => false,
                    'message' => 'This coupon has reached its global usage limit.',
                ];
            }
        }
        

        // Check usage limit per user
        if($offer->usage_per_user){
            $usage_per_user_order = Order::where('offer_id', $offer->id)
                ->where('user_id', $userId)
                ->count();
            if ($usage_per_user_order >= $offer->usage_per_user) {
                return [
                    'status' => false,
                    'message' => 'You have reached the usage limit for this coupon.',
                ];
            }
        }

        // Calculate discount
        $discount = 0;
        if ($offer->discount_type === 'flat') {
            $discount = $offer->discount_value;
        } elseif ($offer->discount_type === 'percentage') {
            $discount = $orderAmount * $offer->discount_value / 100;
            if ($offer->maximum_discount) {
                $discount = min($discount, $offer->maximum_discount);
            }
        }

        $finalAmount = max(0, $orderAmount - $discount);

        return [
            'status' => true,
            'offer_id' => $offer->id,
            'discount' => $discount,
            'final_amount' => $finalAmount,
            'message' => 'Coupon applied successfully.',
        ];
    }
}

if(!function_exists('generateOrderNumber')){
    function generateOrderNumber(){
        $prefix = 'EW-'.date('Ym');
        $orderNumber = null;

        do{
            $lastOrder = Order::where('order_number', 'LIKE', "{$prefix}%")
            ->orderBy('order_number', 'desc')
            ->first();
            $lastNumber = $lastOrder ? (int)substr($lastOrder->order_number, -6) : 0;

            // Increment the last number and format it as a 6-digit number
            $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);

            // Generate the new order number
            $orderNumber = "{$prefix}{$newNumber}";
        } while (Order::where('order_number', $orderNumber)->exists()); // Ensure uniqueness

        return $orderNumber;
    }
}
if(!function_exists('PincodeStatus')){
    function PincodeStatus($code){
        $Pincode = Pincode::where('pincode', $code)->first();
        if($Pincode){
            return $Pincode->status;
        }
        return 1;
    }
}
if(!function_exists('VehicleStatus')){
    function VehicleStatus($id){
        $data = AsignedVehicle::with('order')->where('vehicle_id', $id)->whereIn('status', ['sold','assigned'])->orderBy('id','DESC')->first();
        
        if($data){
            $return = [];
            $return['order_id']= $data->order?$data->order->id:null;
            if($data->status=="assigned"){
                $return['class'] = "warning";
                $return['message'] = "Assigned Now";
            }elseif($data->status=="sold"){
                $return['class'] = "danger";
                $return['message'] = "Sold";
            }
            return $return; //Assigned
        }
        return null; // Return null instead of empty array
    }
}
if(!function_exists('vehicleLog')){
    function vehicleLog($id){
        $data = AsignedVehicle::where('vehicle_id', $id)->get()->count();
        if($data){
            return $data; //Assigned
        }
        return 0; //Not Assigned
    }
}
if (!function_exists('GetProductWiseAvailableStock')) {
    function GetProductWiseAvailableStock($product_id) {
        $all_vehicle_ids = Stock::where('product_id', $product_id)->pluck('id')->toArray();

        $used_data = AsignedVehicle::whereIn('status', ['assigned', 'sold'])
            ->whereIn('vehicle_id', $all_vehicle_ids)
            ->count();

        return count($all_vehicle_ids) - $used_data; // Available Stock
    }
}

if (!function_exists('GetProductWiseAssignedStock')) {
    function GetProductWiseAssignedStock($product_id) {
        $all_vehicle_ids = Stock::where('product_id', $product_id)->pluck('id')->toArray();
        
        return AsignedVehicle::where('status', 'assigned')
            ->whereIn('vehicle_id', $all_vehicle_ids)
            ->count();
    }
}

if (!function_exists('GetProductWiseSoldStock')) {
    function GetProductWiseSoldStock($product_id) {
        $all_vehicle_ids = Stock::where('product_id', $product_id)->pluck('id')->toArray();

        return AsignedVehicle::where('status', 'sold')
            ->whereIn('vehicle_id', $all_vehicle_ids)
            ->count();
    }
}



if(!function_exists('PincodeId')){
    function PincodeId($code){
        $Pincode = Pincode::where('pincode', $code)->first();
        if($Pincode){
            return $Pincode->id;
        }
        return 1;
    }
}
if(!function_exists('GetIgnitionStatus')){
    function GetIgnitionStatus($vehicle_id){
        $vehiclesUrl = 'https://api.a.loconav.com/integration/api/v1/vehicles/telematics/last_known';
        $payload = [
            "vehicleIds" => [$vehicle_id],
            "sensors" => [
                "gps",
                "vehicleBatteryLevel",
                "numberOfSatellites"
            ]
        ];

        $ch = curl_init($vehiclesUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true); // Set as POST request
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Authentication: " . env('LOCONAV_TOKEN'),
            "Accept: application/json",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload)); // Pass JSON body

        $vehiclesResponse = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($vehiclesResponse, true);
        if($response['success']==true){
            $VehicleLastKnow = $response['data']['values'][0];

            if (
                isset($VehicleLastKnow['gps']) &&
                isset($VehicleLastKnow['gps']['ignition']) &&
                isset($VehicleLastKnow['gps']['ignition']['value'])
            ) {
                return $VehicleLastKnow['gps']['ignition']['value'];
            }
        }else{
           return "OFF";
        }
    }
}
if(!function_exists('hasPermissionByParent')){
    function hasPermissionByParent($parentName){
        // Ensure designation is loaded
        $user = Auth::guard('admin')->user();
        if (!$user || !$user->designation) {
            return false;
        }
        $permission_id = Permission::where('parent_name', $parentName)->value('id');
        if($permission_id){
            return DesignationPermission::where('permission_id', $permission_id)->where('designation_id', $user->designation)->exists();
        }else{
            return false;
        }
    }
}
if(!function_exists('UserCurrentLocation')){
    function UserCurrentLocation($lat,$lng){
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}&zoom=18&addressdetails=1";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'E-went'); // Required by Nominatim

        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response, true);
        return $response;
        
    }
}




