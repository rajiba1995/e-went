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
class AuthController extends Controller
{
    /**
     * Handle user registration.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    //  private function storeFileWithCustomName($file, $directory)
    // {
    //     // Generate a custom filename: random 4 digits + timestamp + file extension
    //     $filename = rand(1000, 9999) . '_' . time() . '.' . $file->getClientOriginalExtension();

    //     // Store the file in the specified directory and return its path
    //     return $file->storeAs($directory, $filename, 'public');
    // }
    public function register(Request $request)
    {
       // Custom validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'address' => 'nullable|string|max:255',
            // 'driving_licence_front' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            // 'aadhar_card_front' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            // 'pan_card_front' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            // 'current_address_proof_front' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->first()
            ], 422);
        }

        if($request->step==1){
            $check_terms_condition = UserTermsConditions::where(function ($query) use ($request) {
                $query->where('mobile', $request->mobile)
                    ->orWhere('email', $request->email);
            })
            ->where('status', 'success')
            ->first();

            // if ($check_terms_condition) {
            //     return response()->json([
            //         'status' => true,
            //         'terms_condition' => true,
            //         'message' => 'You have already verified the terms and conditions.',
            //     ], 200);
            // } else {
                $EsignResponse = $this->EsignVerification($request->name,$request->email,$request->address);
                $requestId = $EsignResponse['requests'][0]['request_id'] ?? null;
                $signingUrl = $EsignResponse['requests'][0]['signing_url'] ?? null;
                // dd($EsignResponse);
                // if (strpos($signingUrl, "https://esign.zoop.one") !== false) {
                //     $signingUrl = str_replace("https://esign.zoop.one", "https://esign.zoop.plus", $signingUrl);
                // }
                
                
                if(isset($responseData['response_code']) && $responseData['response_code'] == 403){
                     return response()->json([
                        'status' => false,
                        'terms_condition' => false,
                        'request_id' => null,
                        'signing_url' => null,
                        'message' => $EsignResponse['response_message'],
                    ], 403);
                }else{
                    if ($requestId && $signingUrl) {
                        return response()->json([
                            'status' => false,
                            'terms_condition' => false,
                            'request_id' => $requestId,
                            'signing_url' => $signingUrl,
                            'message' => 'eSign initiated successfully.',
                        ], 200);
                    } else {
                        return response()->json([
                            'status' => false,
                            'terms_condition' => false,
                            'request_id' => null,
                            'signing_url' => null,
                            'message' => 'Terms and conditions not verified.',
                        ], 403);
                    }
                    
                }
                
            // }
        }
        if($request->step==2){
            // $UserTermsConditions = UserTermsConditions::where('email',$request->email)->first();
            // $UserTermsConditions->status = 
            // UserTermsConditions::updateOrCreate(
            //     ['mobile' => $request->mobile], // Search by mobile
            //     [
            //         'email' => $request->email,
            //         'status' => 1,
            //         'accepted_at' => now(),
            //     ]
            // );
        }
       
        DB::beginTransaction();

        try {
            // Initialize paths to null
            // $drivinglicencePath = null;
            // $govtIdCardPath = null;
            // $cancelledChequePath = null;
            // $currentAddressProofPath = null;

            // // Handle file uploads with custom naming if files are provided
            // if ($request->hasFile('driving_licence_front')) {
            //     $drivinglicencePath = storeFileWithCustomName($request->file('driving_licence_front'), 'uploads/driving_licences');
            // }
            
            // if ($request->hasFile('aadhar_card_front')) {
            //     $govtIdCardPath = storeFileWithCustomName($request->file('aadhar_card_front'), 'uploads/aadhar_card');
            // }
            
            // if ($request->hasFile('pan_card_front')) {
            //     $cancelledChequePath = storeFileWithCustomName($request->file('pan_card_front'), 'uploads/cancelled_cheques');
            // }
            
            // if ($request->hasFile('current_address_proof_front')) {
            //     $currentAddressProofPath = storeFileWithCustomName($request->file('current_address_proof_front'), 'uploads/address_proofs');
            // }
            // Create the user
            $user = User::create([
                'name' => ucwords($request->name),
                'customer_id' => MakingCustomerId(),
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                // 'driving_licence_front' => $drivinglicencePath,
                // 'aadhar_card_front' => $govtIdCardPath,
                // 'pan_card_front' => $cancelledChequePath,
                // 'current_address_proof_front' => $currentAddressProofPath,
            ]);

            // / Commit the transaction
            DB::commit();
            // Return a response with the created user data
            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            // Rollback the transaction if anything goes wrong
            DB::rollBack();

            // Return error response
            return response()->json([
                'message' => 'User registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle user login and issue token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    {
     
        // Validate the login request
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->first(),
            ], 422);
        }
     
        // Determine if username is an email or mobile number
        $loginField = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
     
        // Additional check for mobile format
        if ($loginField === 'mobile' && !preg_match('/^\d{10}$/', $request->username)) {
            return response()->json([
                'message' => 'Invalid mobile number. It must be a 10-digit number.',
            ], 422);
        }
       
        // Attempt login
        if (Auth::attempt([$loginField => $request->username, 'password' => $request->password])) {
            $user = Auth::guard('sanctum')->user();
            // dd($user);
            // Use user's name as the token name
            $tokenName = str_replace(' ', '_', $user->name) . '_' . $user->id . '_token';

            // Delete any existing tokens with the same name before generating a new one
            $user->tokens()->where('tokenable_id', $user->id)->delete();
            // dd($tokenName);
            // Generate new token using Laravel Sanctum
            $token = $user->createToken($tokenName)->plainTextToken;
            $user->is_verified = CheckUserStatus($user->id);
            $user->rating = AdminRatings($user->id);
            // Return response with token
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
            ], 200);
        }
        // If login fails, return an error response
        return response()->json([
            'message' => 'The provided credentials are incorrect.',
        ], 422);
    }

    // Step 1: Request OTP
    public function requestotp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10|exists:users,mobile',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->first(),
            ], 422);
        }

        $mobile = $request->mobile;

        // Generate OTP
        // $otp = rand(100000, 999999);
        $otp = 1234;

        // Store OTP in a database or cache (e.g., Redis)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['mobile' => $mobile],
            ['otp' => $otp, 'created_at' => now()]
        );

        // Send OTP to mobile (using a hypothetical sendSms function)
        // $this->sendSms($mobile, "Your OTP is: $otp");

        return response()->json([
            'message' => 'OTP sent to your mobile number.',
        ], 200);
    }

     // Step 2: Verify OTP
     public function verifyOtp(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'mobile' => 'required|digits:10|exists:users,mobile',
             'otp' => 'required|digits:4',
         ]);
 
         if ($validator->fails()) {
             return response()->json([
                'status' => false,
                 'message' => $validator->errors()->first(),
                 'errors' => $validator->errors()->first(),
             ], 422);
         }
 
         $mobile = $request->mobile;
         $otp = $request->otp;
 
         // Check OTP in the database
         $record = DB::table('password_reset_tokens')
             ->where('mobile', $mobile)
             ->where('otp', $otp)
             ->first();
             
         if (!$record || now()->diffInSeconds($record->created_at) > 60) {
             return response()->json([
                 'message' => 'Invalid or expired OTP.',
             ], 422);
         }
 
         // Mark OTP as verified (optional, cleanup can be added)
         return response()->json([
             'message' => 'OTP verified successfully.',
         ], 200);
     }

     // Step 3: Reset Password
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10|exists:users,mobile',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->first(),
            ], 422);
        }

        $mobile = $request->mobile;
        $password = $request->password;

        // Update user's password
        $user = User::where('mobile', $mobile)->first();
        $user->password = Hash::make($password);
        $user->save();

        // Cleanup OTP record
        DB::table('password_reset_tokens')->where('mobile', $mobile)->delete();

        return response()->json([
            'message' => 'Password reset successfully.',
        ], 200);
    }

    protected function getAuthenticatedUser()
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        return $user;
    }
    public function userProfile()
    {
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
        
        // Check if the user exists
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404); // 404 Not Found
        }
    
        // Return the user profile data
        return response()->json([
            'data' => $user,
            'message' => 'User profile retrieved successfully.',
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user' => 'required|exists:users,id', // Ensure the ID exists in the users table
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        // dd($validator);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->first()
            ], 422);
        }
        // Find the user by ID
        $user = User::find($request->user);
    
        // If the user is not found, return a response
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404); // 404 Not Found
        }
    
        // Verify the current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect.',
            ], 400); // 400 Bad Request
        }
    
        // Update the password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully.',
        ], 200);
    }
    public function fetchBanners()
    {
        // Fetch active banners (status = 1)
        $banners = Banner::where('status', 1)->orderBy('id', 'desc')->get();

        // Check if any banners are found
        if ($banners->isEmpty()) {
            // Return response if no active banners are found
            return response()->json([
                'status' => false,
                'message' => 'No active banners available.',
                'data' => [],
            ], 404); // 404 Not Found
        }

        // Return response with banners data
        return response()->json([
            'status' => true,
            'message' => 'Banners fetched successfully.',
            'data' => $banners,
        ], 200); // 200 OK
    }

    public function fetchFaqs()
    {
        $faqs = Faq::orderBy('id', 'ASC')->get();
    
        if ($faqs->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No faqs available.',
                'data' => [],
            ], 404); 
        }

        return response()->json([
            'status' => true,
            'message' => 'Faqs fetched successfully.',
            'data' => $faqs,
        ], 200); 
    }

    // profile update
    public function updateProfile(Request $request){
         // Validate the request data
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id', // Ensure the ID exists in the users table
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'email' => [
                'required',
                'email',
                // Check that the email is unique except for the given user_id
                Rule::unique('users', 'email')->ignore($request->id, 'id'),
            ],
            'mobile' => [
                'required',
                'digits:10',
                // Check that the mobile is unique except for the given user_id
                Rule::unique('users', 'mobile')->ignore($request->id, 'id'),
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::where('id', $request->id)->first();
        $user->name = ucwords($request->name);
        $user->address = $request->address;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
       // Handle image upload (if provided)
        if ($request->hasFile('image')) {
            $image = storeFileWithCustomName($request->file('image'), 'uploads/user');
            $user->profile_image = $image;
        }
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully!',
            'data' => $user,
        ], 200); 
    }
    public function updateDocument(Request $request){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
        $validator = Validator::make($request->all(), [
            'driving_licence_front' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:5120',
            'driving_licence_back' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:5120',
            // 'aadhar_card_front' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:5120',
            // 'aadhar_card_back' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:5120',
            'pan_card_front' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:5120',
            'pan_card_back' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:5120',
            'current_address_proof_front' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:5120',
            'current_address_proof_back' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:5120',
            'passbook_front' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:5120',
            'profile_image' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:5120',
        ]);

        // Check if validation fails
        // dd($validator);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::where('id', $user->id)->first();
        
       // Handle image upload (if provided)
        if ($request->hasFile('driving_licence_front') || $request->hasFile('driving_licence_back')) {

            if($request->hasFile('driving_licence_front')){
                $driving_licence_front = storeFileWithCustomName($request->file('driving_licence_front'), 'uploads/driving_licences');
                $user->driving_licence_front = $driving_licence_front;
            }
            
            if($request->hasFile('driving_licence_back')){
                $driving_licence_back = storeFileWithCustomName($request->file('driving_licence_back'), 'uploads/driving_licences');
                $user->driving_licence_back = $driving_licence_back;
            }

            $user->driving_licence_status = 1;
          
            $existing_data = UserKycLog::where('user_id', $user->id)->where('document_type','Driving Licence')->where('status', 'Uploaded')->first();
            $store = new UserKycLog;
            if($existing_data){
                $store->status = 'Re-uploaded';
            }else{
                $store->status = "Uploaded";
            }
            $store->user_id = $user->id;
            $store->created_at = now()->toDateTimeString();
            $store->document_type = 'Driving Licence';
            $store->save();
            
        }
        if ($request->aadhar_number) {
            // if($request->hasFile('aadhar_card_front')){
            //     $aadhar_card_front = storeFileWithCustomName($request->file('aadhar_card_front'), 'uploads/aadhar_card');
            //     $user->aadhar_card_front = $aadhar_card_front;
            // }

            // if($request->hasFile('aadhar_card_back')){
            //     $aadhar_card_back = storeFileWithCustomName($request->file('aadhar_card_back'), 'uploads/aadhar_card');
            //     $user->aadhar_card_back = $aadhar_card_back;
            // }

            $user->aadhar_number = $request->aadhar_number;
            $user->aadhar_card_status = 2;

            $existing_data = UserKycLog::where('user_id', $user->id)->where('document_type','Aadhar Card')->where('status', 'Uploaded')->first();
            $store = new UserKycLog;
            if($existing_data){
                $store->status = 'Re-uploaded';
            }else{
                $store->status = "Uploaded";
            }
            $store->user_id = $user->id;
            $store->created_at = now()->toDateTimeString();
            $store->document_type = 'Aadhar Card';
            $store->save();

        }
        if ($request->hasFile('pan_card_front') || $request->hasFile('pan_card_back')) {
            if($request->hasFile('pan_card_front')){
                $pan_card_front = storeFileWithCustomName($request->file('pan_card_front'), 'uploads/pan_card');
                $user->pan_card_front = $pan_card_front;
            }

            if($request->hasFile('pan_card_back')){
                $pan_card_back = storeFileWithCustomName($request->file('pan_card_back'), 'uploads/pan_card');
                $user->pan_card_back = $pan_card_back;
            }
            
            $user->pan_card_status = 1;

            $existing_data = UserKycLog::where('user_id', $user->id)->where('document_type','Pan Card')->where('status', 'Uploaded')->first();
            $store = new UserKycLog;
            if($existing_data){
                $store->status = 'Re-uploaded';
            }else{
                $store->status = "Uploaded";
            }
            $store->user_id = $user->id;
            $store->created_at = now()->toDateTimeString();
            $store->document_type = 'Pan Card';
            $store->save();
        }
        if ($request->hasFile('current_address_proof_front') || $request->hasFile('current_address_proof_back')) {

            if($request->hasFile('current_address_proof_front')){
                $current_address_proof_front = storeFileWithCustomName($request->file('current_address_proof_front'), 'uploads/address_proofs');
                $user->current_address_proof_front = $current_address_proof_front;
            }

            if($request->hasFile('current_address_proof_back')){
                $current_address_proof_back = storeFileWithCustomName($request->file('current_address_proof_back'), 'uploads/address_proofs');
                $user->current_address_proof_back = $current_address_proof_back;
            }
            

            $user->current_address_proof_status = 1;

            $existing_data = UserKycLog::where('user_id', $user->id)->where('document_type','Current Address Proof')->where('status', 'Uploaded')->first();
            $store = new UserKycLog;
            if($existing_data){
                $store->status = 'Re-uploaded';
            }else{
                $store->status = "Uploaded";
            }
            $store->user_id = $user->id;
            $store->created_at = now()->toDateTimeString();
            $store->document_type = 'Current Address Proof';
            $store->save();
           
        }

        if ($request->hasFile('passbook_front')) {
            if($request->hasFile('passbook_front')){
                $passbook_front = storeFileWithCustomName($request->file('passbook_front'), 'uploads/passbook');
                $user->passbook_front = $passbook_front;
            }

            $user->passbook_status = 1;

            $existing_data = UserKycLog::where('user_id', $user->id)->where('document_type','Passbook')->where('status', 'Uploaded')->first();
            $store = new UserKycLog;
            if($existing_data){
                $store->status = 'Re-uploaded';
            }else{
                $store->status = "Uploaded";
            }
            $store->user_id = $user->id;
            $store->created_at = now()->toDateTimeString();
            $store->document_type = 'Passbook';
            $store->save();
        }
        if ($request->hasFile('profile_image')) {
            if($request->hasFile('profile_image')){
                $profile_image = storeFileWithCustomName($request->file('profile_image'), 'uploads/profile_image');
                $user->profile_image = $profile_image;
            }

            $user->profile_image_status = 1;

            $existing_data = UserKycLog::where('user_id', $user->id)->where('document_type','Profile Image')->where('status', 'Uploaded')->first();
            $store = new UserKycLog;
            if($existing_data){
                $store->status = 'Re-uploaded';
            }else{
                $store->status = "Uploaded";
            }
            $store->user_id = $user->id;
            $store->created_at = now()->toDateTimeString();
            $store->document_type = 'Profile Image';
            $store->save();
        }

        $user->status = 1;
        $user->kyc_uploaded_at = now()->toDateTimeString();
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profile document updated successfully!',
            'data' => $user,
        ], 200); 
    }
    
    public function revokeTokens()
    {
        try {

            $user = $this->getAuthenticatedUser();
            if ($user instanceof \Illuminate\Http\JsonResponse) {
                return $user; // Return the response if the user is not authenticated
            }
            // Delete tokens where tokenable_id matches the user ID
            DB::table('personal_access_tokens')
                ->where('tokenable_id', $user->id)
                ->delete();
            return response()->json([
                'status' => true,
                'message' => 'All tokens for the user have been removed successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to remove tokens.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function ProductList(Request $request)
    {
        // Capture the search term from the request
        $search = $request->input('search'); // Assuming 'search' is passed as a query parameter

        // Retrieve products based on the search criteria
        $products = Product::query()
        ->select(
            'id',
            'title',
            'position',
            'types',
            'short_desc',
            'is_driving_licence_required',
            'image',
            'is_rent',
            'status'
        )
        ->when($search, function ($query) use ($search) {
            // Group search conditions with OR logic
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('types', 'like', '%' . $search . '%');
            });
        })
        ->with([
            'rentalprice' => function ($query) {
                $query->select('id', 'product_id', 'duration','subscription_type', 'deposit_amount', 'rental_amount'); // Select only necessary columns
            },
            'category:id,title',      // Load specific columns for 'category'
            'subCategory:id,title',   // Load specific columns for 'subcategory'
            'features:id,product_id,title'       // Load specific columns for 'features'
        ])
        ->where('status', 1) // Filter active products
        ->where('is_rent', 1) // Filter active products
        ->orderBy('position', 'ASC') // First order by position
        ->orderBy('title', 'ASC') // Then order by title
        ->get();

        // Process each product to set rental price details
        foreach ($products as $product) {
            $rental = $product->rentalprice->first();
            $product->subscription_type = $rental ? ucwords($rental->subscription_type) : 0;
            $product->deposit_amount = $rental ? $rental->deposit_amount : 0;
            $product->rental_duration = $rental ? $rental->duration : 0;
            $product->rental_amount = $rental ? $rental->rental_amount : 0;
        }
        // Return the product list as a JSON response
        return response()->json([
            'status' => true,
            'data' => $products,
            'message' => 'Getting rent product list.',
        ], 200);
    }
    public function SellingProductList(Request $request)
    {
        // Capture the search term from the request
        $search = $request->input('search'); // Assuming 'search' is passed as a query parameter

        // Retrieve products based on the search criteria
        $products = Product::query()->when($search, function ($query) use ($search) {
            // Group search conditions with OR logic
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('types', 'like', '%' . $search . '%');
            });
        })
        ->with([
            'category:id,title',      // Load specific columns for 'category'
            'subCategory:id,title',   // Load specific columns for 'subcategory'
            'features:id,product_id,title'       // Load specific columns for 'features'
        ])
        ->where('status', 1) // Filter active products
        ->where('is_selling', 1) // Filter active products
        ->orderBy('position', 'ASC') // First order by position
        ->orderBy('title', 'ASC') // Then order by title
        ->get();

        // Process each product to set rental price details
        // Return the product list as a JSON response
        return response()->json([
            'status' => true,
            'data' => $products,
            'message' => 'Getting selling product list.',
        ], 200);
    }
    public function SellingQueryRequest(Request $request){
        $user = $this->getAuthenticatedUser();

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'remarks' => 'required|string|max:1000',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $store = new SellingQuery;
            $store->user_id = $user->id;
            $store->product_id = $request->product_id;
            $store->remarks = $request->remarks;
            $store->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Query submitted successfully.',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to submit selling query. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }

    }
    
    public function SellingProductDetails($id)
    {
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }

        // Retrieve the product by ID and ensure it's active (status = 1)
        $data = Product::where('id', $id)
            ->where('status', 1)
            ->with([
                'ProductImages:product_id,image', // Eager load product images
                'category:id,title',             // Eager load category with specific columns
                'subCategory:id,title',           // Eager load sub-category with specific columns
                'features:id,product_id,title'           // Eager load sub-category with specific columns
            ])
            ->first();

 
        // Check if product exists
        if (!$data) {
            return response()->json(['status' => false, 'message' => 'Product not found'], 404);
        }

        // Combine all images into a single array
        $allImages = collect($data->ProductImages)
            ->pluck('image') // Get all images from the relationship
            ->prepend($data->image) // Add the main image at the beginning
            ->unique() // Ensure no duplicate images
            ->toArray(); // Convert to array

        $allfeatures = collect($data->features)
            ->pluck('title') // Get all images from the relationship
            ->unique() // Ensure no duplicate images
            ->toArray(); 
        $product_features = [];
        if(count($allfeatures)>0){
            foreach($allfeatures as $k=>$fitem){
                $product_features[] =$fitem;
            }
        }
        $related_products = Product::select(
            'id',
            'title',
            'position',
            'types',
            'short_desc',
            'image',
            'category_id',
            'sub_category_id',
            'status'
        )
        ->where('id', '!=', $data->id) // Exclude the current product
        ->where('status', 1) // Ensure the product is active
        ->where('is_selling', 1) // Ensure the product is active
        ->where(function ($query) use ($data) {
            $query->where('category_id', $data->category_id) // Match the same category
                    ->orWhere('sub_category_id', $data->sub_category_id); // Or match the same sub-category
        })
        ->orderBy('position', 'ASC') // Order by position first
        ->orderBy('title', 'ASC') // Then order by title
        ->limit(10) // Limit to 10 results
        ->get();
            
        // Prepare product details object
        $product_data = (object) [];
        $product_data->id = $data->id;
        $product_data->title = $data->title;
        $product_data->types = $data->types;
        $product_data->short_desc = $data->short_desc;
        $product_data->category = $data->category ? $data->category->title : null;
        $product_data->sub_category = $data->subCategory ? $data->subCategory->title : null;
        $product_data->status = $data->status;
        $product_data->all_features = $product_features;
        $product_data->all_images = $allImages; // Set combined images array
        $product_data->display_price = $data->display_price;
        $product_data->related_products = $related_products;
        $product_data->is_driving_licence_required = $data->is_driving_licence_required;
        $product_data->customer_reviews = ProductReviews($data->id);
        // Return the product details as a JSON response
        $features = $allfeatures;
        return response()->json([
            'status' => true,
            'data' => $product_data,
            'message' => 'Getting selling product details.'
        ], 200);
    }
    public function ProductDetails($id)
    {
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }

        // Retrieve the product by ID and ensure it's active (status = 1)
        $data = Product::where('id', $id)
            ->where('status', 1)
            ->with([
                'rentalprice' => function ($query) {
                    $query->select('id', 'product_id', 'duration', 'subscription_type','deposit_amount', 'rental_amount');
                },
                'ProductImages:product_id,image', // Eager load product images
                'category:id,title',             // Eager load category with specific columns
                'subCategory:id,title',           // Eager load sub-category with specific columns
                'features:id,product_id,title'           // Eager load sub-category with specific columns
            ])
            ->first();

 
        // Check if product exists
        if (!$data) {
            return response()->json(['status' => false, 'message' => 'Product not found'], 404);
        }

        // Combine all images into a single array
        $allImages = collect($data->ProductImages)
            ->pluck('image') // Get all images from the relationship
            ->prepend($data->image) // Add the main image at the beginning
            ->unique() // Ensure no duplicate images
            ->toArray(); // Convert to array

        $allfeatures = collect($data->features)
            ->pluck('title') // Get all images from the relationship
            ->unique() // Ensure no duplicate images
            ->toArray(); 
        $product_features = [];
        if(count($allfeatures)>0){
            foreach($allfeatures as $k=>$fitem){
                $product_features[] =$fitem;
            }
        }
        $related_products = Product::select(
            'id',
            'title',
            'position',
            'types',
            'short_desc',
            'image',
            'category_id',
            'sub_category_id',
            'status'
        )
        ->where('id', '!=', $data->id) // Exclude the current product
        ->where('status', 1) // Ensure the product is active
        ->where(function ($query) use ($data) {
            $query->where('category_id', $data->category_id) // Match the same category
                    ->orWhere('sub_category_id', $data->sub_category_id); // Or match the same sub-category
        })
        ->orderBy('position', 'ASC') // Order by position first
        ->orderBy('title', 'ASC') // Then order by title
        ->limit(10) // Limit to 10 results
        ->get();
            
        // Prepare product details object
        $product_data = (object) [];
        // $product_data->stock_qty = $data->stock_qty;
        $product_data->id = $data->id;
        $product_data->title = $data->title;
        $product_data->types = $data->types;
        $product_data->short_desc = $data->short_desc;
        // $product_data->long_desc = $data->long_desc;
        $product_data->category = $data->category ? $data->category->title : null;
        $product_data->sub_category = $data->subCategory ? $data->subCategory->title : null;
        // $product_data->is_selling = $data->is_selling;
        // $product_data->base_price = $data->base_price;
        // $product_data->display_price = $data->display_price;
        // $product_data->is_rent = $data->is_rent;
        // $product_data->rent_duration = $data->rent_duration;
        // $product_data->per_rent_price = $data->per_rent_price;
        $product_data->status = $data->status;
        $product_data->all_features = $product_features;
        $product_data->all_images = $allImages; // Set combined images array
        $product_data->rental_price = $data->rentalprice;
        $product_data->related_products = $related_products;
        $product_data->is_driving_licence_required = $data->is_driving_licence_required;
        $product_data->customer_reviews = ProductReviews($data->id);
        // Return the product details as a JSON response
        $features = $allfeatures;
        return response()->json([
            'status' => true,
            'data' => $product_data,
            'message' => 'Getting product details.'
        ], 200);
    }

    public function ProductFilter(Request $request)
    {
        $search = $request->input('filter');
        // If no filter value is provided, return empty array
        if (!$search) {
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'No search value provided.',
            ], 200);
        }
    
        // Proceed with filtering if search term exists
        $products = Product::query()
            ->select(
                'id',
                'title',
                'position',
                'types',
                'short_desc',
                'is_driving_licence_required',
                'image',
                'status'
            )
            ->where('status', 1)
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('types', 'like', '%' . $search . '%');
            })
            ->orderBy('position', 'ASC')
            ->orderBy('title', 'ASC')
            ->get();
    
        return response()->json([
            'status' => true,
            'data' => $products,
            'message' => count($products) > 0 ? 'Getting product list.' : 'Data not found!',
        ], 200);
    }
    
    public function HomePage()
    {
        // Fetching the banners
        $banners = Banner::where('status', 1)->orderBy('id', 'desc')->get();
        $why_ewent = WhyEwent::where('status', 1)->orderBy('id', 'desc')->get();
        
        // Fetching the FAQs
        $faqs = Faq::orderBy('id', 'ASC')->get();
        
        // Fetching the products with eager loading
        $products = Product::select('id', 'title', 'position', 'types', 'short_desc', 'image', 'status', 'is_driving_licence_required')->where('status', 1)
            ->with([
                'rentalprice' => function ($query) {
                    $query->select('id', 'product_id', 'duration', 'subscription_type', 'deposit_amount', 'rental_amount'); // Select only necessary columns
                }     // Load specific columns for 'features'
            ])
            ->orderBy('position', 'ASC')
            ->orderBy('title', 'ASC') // Order products by title
            ->limit(10)
            ->get();
            foreach ($products as $product) {
                $rental = $product->rentalprice->first();
                $product->subscription_type = $rental ? ucwords($rental->subscription_type) : 0;
                $product->deposit_amount = $rental ? $rental->deposit_amount : 0;
                $product->rental_duration = $rental ? $rental->duration : 0;
                $product->rental_amount = $rental ? $rental->rental_amount : 0;
            }
        // Check if there are any banners, FAQs, or products
        if ($banners->isEmpty() && $faqs->isEmpty() && $products->isEmpty()) {
            // Return a response if no data is found
            return response()->json([
                'status' => false,
                'message' => 'No data found',
            ], 404);
        }
    
        // Prepare a single array for the API response
        $response = [
            'why_ewent' => $why_ewent,
            'banners' => $banners,
            'faqs' => $faqs,
            'products' => $products
        ];
    
        // Return the data if found
        return response()->json([
            'status' => true,
            'response' => $response,
            'message' => 'Home page data',
        ], 200);
    }

    public function DocumentStatus(){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
        $documents= [];
       
        $data = User::select('id', 'driving_licence_front', 'driving_licence_back','driving_licence_status', 'aadhar_card_front', 'aadhar_card_back','aadhar_number','aadhar_card_status', 'pan_card_front', 'pan_card_back','pan_card_status', 'current_address_proof_front','current_address_proof_back', 'current_address_proof_status','passbook_front', 'passbook_status','profile_image','profile_image_status')->where('id',$user->id)->first();
         // Check if product exists
        if (!$data) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }

        $documents['Driving Licence'] = [
            'front' =>$data->driving_licence_front,
            'back'=>$data->driving_licence_back,
            'status' =>$data->driving_licence_status,
        ];

        $documents['Aadhar Card'] = [
            // 'front' =>$data->aadhar_card_front,
            // 'back'=>$data->aadhar_card_back,
            'aadhar_number'=>$data->aadhar_number,
            'status' =>$data->aadhar_card_status,
        ];

        $documents['Pan Card'] = [
            'front' =>$data->pan_card_front,
            'back'=>$data->pan_card_back,
            'status' =>$data->pan_card_status,
        ];

        $documents['Current Address Proof'] = [
            'front' =>$data->current_address_proof_front,
            'back'=>$data->current_address_proof_back,
            'status' =>$data->current_address_proof_status,
        ];
        $documents['Passbook'] = [
            'front' =>$data->passbook_front,
            'status' =>$data->passbook_status,
        ];
        $documents['Profile Image'] = [
            'front' =>$data->profile_image,
            'status' =>$data->profile_image_status,
        ];


        $documents['Driving Licence']['history'] = UserKycLog::where('user_id', $user->id)->where('document_type', 'Driving Licence')->orderBy('id', 'ASC')->get()->map(function ($item) {
            $item->date = \Carbon\Carbon::parse($item->created_at)->format('d-m-Y h:i A'); // Format Date
            $item->status = 'Driving Licence '.$item->status;
            return $item;
        })->toArray();

        $documents['Aadhar Card']['history'] = UserKycLog::where('user_id', $user->id)->where('document_type', 'Aadhar Card')->orderBy('id', 'ASC')->get()->map(function ($item) {
            $item->date = \Carbon\Carbon::parse($item->created_at)->format('d-m-Y h:i A'); // Format Date
            $item->status = 'Aadhar Card '.$item->status;
            return $item;
        })->toArray();

        $documents['Pan Card']['history'] = UserKycLog::where('user_id', $user->id)->where('document_type', 'Pan Card')->orderBy('id', 'ASC')->get()->map(function ($item) {
            $item->date = \Carbon\Carbon::parse($item->created_at)->format('d-m-Y h:i A'); // Format Date
            $item->status = 'Pan Card '.$item->status;
            return $item;
        })->toArray();

        $documents['Current Address Proof']['history'] = UserKycLog::where('user_id', $user->id)->where('document_type', 'Current Address Proof')->orderBy('id', 'ASC')->get()->map(function ($item) {
            $item->date = \Carbon\Carbon::parse($item->created_at)->format('d-m-Y h:i A'); // Format Date
            $item->status = 'Address Proof '.$item->status;
            return $item;
        })->toArray();
        
        $documents['Passbook']['history'] = UserKycLog::where('user_id', $user->id)->where('document_type', 'Passbook')->orderBy('id', 'ASC')->get()->map(function ($item) {
            $item->date = \Carbon\Carbon::parse($item->created_at)->format('d-m-Y h:i A'); // Format Date
            $item->status = 'Passbook '.$item->status;
            return $item;
        })->toArray();

        $documents['Profile Image']['history'] = UserKycLog::where('user_id', $user->id)->where('document_type', 'Profile Image')->orderBy('id', 'ASC')->get()->map(function ($item) {
            $item->date = \Carbon\Carbon::parse($item->created_at)->format('d-m-Y h:i A'); // Format Date
            $item->status = 'Profile Image '.$item->status;
            return $item;
        })->toArray();

        // dd($documents);
        // Return the data if found
        return response()->json([
            'status' => true,
            'response' => $documents,
            'message' => 'User Document Status',
        ], 200);
    }

    public function OfferList(){
        $data = Offer::where('status', 'active')->orderBy('coupon_code', 'ASC')->get();
        if(count($data)==0){
            return response()->json(['status'=>false, 'message'=>'offer not found!'], 404);
        }
        return response()->json(['status'=>true, 'response'=>$data, 'message'=>'Offer Listing'], 200);
    }

    public function OrderHistory(){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
        $data = Order::with('product','vehicle','exchange_vehicle')->where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        if(count($data)==0){
            return response()->json(['status'=>false, 'message'=>'order not found!'], 404);
        }
        $result = [];
        foreach($data as $key => $item){
            $histories = $item->exchange_vehicle()->orderBy('id', 'ASC')->get();
        
            $result[$key] = [
                'order_number' => $item->order_number,
                'model' => $item->product ? $item->product->title : "N/A",
                'subscription_type' => $item->subscription ? ucwords($item->subscription->subscription_type) : "N/A",
                'deposit_amount' => $item->deposit_amount,
                'rental_amount' => $item->rental_amount,
                'payment_status' => ucwords($item->payment_status),
                'rent_duration' => $item->rent_duration . ' Days',
                'status' => $item->rent_status,
                'order_date' => date('d-m-Y h:i A', strtotime($item->created_at)),
            ];

            foreach($histories as $history){
                $result[$key]['history'][] = [
                    'vehicle' => $history->stock ? $history->stock->vehicle_number : "N/A",
                    'start_date' => date('d-m-Y h:i A', strtotime($history->start_date)),
                    'end_date' => date('d-m-Y h:i A', strtotime($history->end_date)),
                    'status' => ucwords($history->status),
                    'date' => date('d-m-Y h:i A', strtotime($history->exchanged_at)),
                ];
            }

            if(isset($item->vehicle)){
                $last_item = $item->vehicle;
                $result[$key]['history'][] = [
                    'vehicle' => $last_item->stock ? $last_item->stock->vehicle_number : "N/A",
                    'start_date' => date('d-m-Y h:i A', strtotime($last_item->start_date)),
                    'end_date' => date('d-m-Y h:i A', strtotime($last_item->end_date)),
                    'status' => ucwords($last_item->status),
                    'date' => date('d-m-Y h:i A', strtotime($last_item->assigned_at)),
                ];
            }
        }


        return response()->json(['status'=>true, 'response'=>$result, 'message'=>'Order Listing'],200);
    }

    public function paymentHistory(){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }

        $data = Order::with('product','subscription','payments')->where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        $result = [];

        foreach($data as $key => $item) {
           
            foreach($item->payments as $index=>$sub_item){
                $result[$index] = [
                    'order_number' => $item->order_number,
                    'model' => $item->product ? $item->product->title : "N/A",
                    'subscription_type' => $item->subscription ? ucwords($item->subscription->subscription_type) : "N/A",
                    'payment_for' => ucwords(str_replace('_', ' ', $sub_item->order_type)),
                    'amount'=>$sub_item->amount,
                    'transaction_id'=>$sub_item->transaction_id,
                    'payment_method'=>ucwords($sub_item->payment_method),
                    'payment_date'=>date('d-m-Y h:i A', strtotime($sub_item->payment_date)),
                ];
            }
        }
        return response()->json([
            'status' => true,
            'order' => $result,
        ], 200);
      
    }
    public function SellOrderHistory($user_id){
        $data = Order::where('user_id', $user_id)->where('order_type', 'Sell')->orderBy('id', 'DESC')->get();
        if(count($data)==0){
            return response()->json(['status'=>false, 'message'=>'Order not found!'], 404);
        }

        return response()->json(['status'=>true, 'response'=>$data, 'message'=>'Order listing'],200);
    }
    public function RentOrderHistory($user_id){
        $data = Order::where('user_id', $user_id)->where('order_type', 'Rent')->orderBy('id', 'DESC')->get();
        if(count($data)==0){
            return response()->json(['status'=>false, 'message'=>'Order not found!'], 404);
        }

        return response()->json(['status'=>true, 'response'=>$data, 'message'=>'Order listing'],200);
    }

    public function OrderDetails($order_id){
        $order = Order::where('id', $order_id)->first();
        if(!$order){
            return response()->json(['status'=>false, 'message'=>'data not found!'], 404);
        }
        return response()->json(['status'=>true, 'response'=>$order, 'message'=>'Data successfully retrieved'],200);
    }

    public function CompanyPolicy(){
        $data = Policy::orderBy('title', 'DESC')->get();
        if(count($data)==0){
            return response()->json(['status'=>false, 'message'=>'Data not found!'], 404);
        }

        return response()->json(['status'=>true, 'response'=>$data, 'message'=>'Data successfully retrieved'],200);
    }
    public function CompanyPolicyDetails($id){
        $data = Policy::where('id', $id)->first();
        if(!$data){
            return response()->json(['status'=>false, 'message'=>'data not found!'], 404);
        }
        return response()->json(['status'=>true, 'response'=>$data, 'message'=>'Data successfully retrieved'],200);
    }

    public function bookNow(Request $request){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id', // Ensure 'id' column exists
            'is_driving_licence_required' => 'required', // If it's a boolean
            // 'subscription_id' => 'required', // Ensure 'id' column exists
            'subscription_type' => 'required|string|max:255',
            'deposit_amount' => 'required|numeric', // Ensure it's a number
            'rental_amount' => 'required|numeric',
            'total_amount' => 'required|numeric',
        ]);
        
       
        
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                // 'message' => 'Validation failed. Please check the errors below.',
                'message' => $validator->errors()->first(), // Return all errors instead of only the first
            ], 422);
        }
        // Check User Verification
        if ($user->is_verified!=="verified") {
            return response()->json([
                'status' => false, 
                'message' => "Your KYC status is currently $user->is_verified Please verify your KYC to continue."
            ], 404);
        }

        if ($user->vehicle_assign_status == "suspended") {
            return response()->json([
                'status' => false,
                'message' => 'Sorry! Your account is suspended. Please contact the administrator for assistance.',
            ], 404);
        } 
        // Check Driving Licence Verification
        if ($request->is_driving_licence_required==1 && $user->driving_licence_status!=2) {
            return response()->json([
                'status' => false, 
                'message' => "Your driving licence verification is pending. Please complete the verification to continue."
            ], 404);
        }

        // Assuming assigned_vehicle holds the assigned vehicle data
        $assigned_vehicle = AsignedVehicle::where('user_id', $user->id)->where('status', 'assigned')->get()->count();
        if($assigned_vehicle>0){
            return response()->json([
                'status' => false, 
                'message' => "You already have an assigned vehicle. Please use it or return it before booking a new one."
            ], 404);
        }

        $RentalPrice = RentalPrice::where('product_id', $request->product_id)->where('subscription_type', $request->subscription_type)->first();
        if(!$RentalPrice){
            return response()->json([
                'status' => false, 
                 'message' => "This vehicle is not available for the selected duration."
            ], 404);
        }

        $total_amount = $RentalPrice->deposit_amount+$RentalPrice->rental_amount;
        if($request->total_amount!=$total_amount){
            return response()->json([
                'status' => false, 
                  'message' => "The total amount does not match the required amount. Please check and try again."
            ], 404);
        }
        // dd($validator);
        DB::beginTransaction();
        try{
            $existing_order = Order::where('user_id', $user->id)->orderBy('id', 'DESC')->first();
          
            if($existing_order){
                if($existing_order->rent_status =="pending"){
                    $existing_order->update([
                        'user_id' => $user->id,
                        'product_id' => (int)$request->product_id,
                        'deposit_amount' =>$RentalPrice->deposit_amount,
                        'rental_amount' => $RentalPrice->rental_amount,
                        'total_price' => $total_amount,
                        'final_amount' => $total_amount,
                        'subscription_id' => (int)$RentalPrice->id,
                        'quantity' => 1,
                        'payment_mode' => "Online",
                        // 'shipping_address' => $request->shipping_address,
                        'rent_duration' => $RentalPrice->duration,
                        'rent_status' => "pending",
                    ]);
                    $order = $existing_order;
                    $message = "Order updated successfully";
                    DB::commit();

                    $InitiateSaleResponse = $this->iciciInitiateSale($order->id,'new');
                    // Check responseCode
                    if (isset($InitiateSaleResponse['responseCode']) && $InitiateSaleResponse['responseCode'] === 'R1000') {
                         return response()->json([
                            'status' => true,
                            'response' => "Transaction has been successfully generated.",
                            'merchantTxnNo' => $InitiateSaleResponse['merchantTxnNo'] ?? null,
                            'redirect_url' => isset($InitiateSaleResponse['redirectURI'], $InitiateSaleResponse['tranCtx'])
                                    ? $InitiateSaleResponse['redirectURI'] . '?tranCtx=' . $InitiateSaleResponse['tranCtx']
                                    : null,
                            // 'data' => [
                                
                            //     'showOTPCapturePage' => $InitiateSaleResponse['showOTPCapturePage'] ?? null,
                            //     'generateOTPURI'     => $InitiateSaleResponse['generateOTPURI'] ?? null,
                            //     'verifyOTPURI'       => $InitiateSaleResponse['verifyOTPURI'] ?? null,
                            //     'authorizeURI'       => $InitiateSaleResponse['authorizeURI'] ?? null,
                            //     'secureHash'         => $InitiateSaleResponse['secureHash'] ?? null,
                            // ]
                        ], 200);

                    }

                    // If responseCode is not R1000
                    return response()->json([
                        'status' => false,
                        'response' => 'Failed to initiate transaction.',
                        'error' => $InitiateSaleResponse
                    ], 400);
    
                }elseif ($existing_order->rent_status == "ready to assign") {
                    return response()->json([
                        'status' => false,
                        'message' => 'You already have an order. Please wait for the cab to be assigned by the admin or cancel the order to proceed.',
                    ], 404);
                } elseif ($existing_order->user->vehicle_assign_status == "deallocate") {
                    return response()->json([
                        'status' => false,
                        'message' => 'You have been deallocated. Please return the vehicle and contact the admin.',
                    ], 403);
                } elseif ($existing_order->rent_status == "active") {
                    return response()->json([
                        'status' => false,
                        'message' => 'You already have an active order. Please complete it before creating a new one.',
                    ], 404);
                } elseif ($existing_order->rent_status == "suspended") {
                    return response()->json([
                        'status' => false,
                        'message' => 'Sorry! Your account is suspended. Please contact the administrator for assistance.',
                    ], 403);
                } 
            }
            // else{
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_type' => 'Rent',
                    'order_number' => generateOrderNumber(),
                    'product_id' => (int)$request->product_id,
                    'deposit_amount' =>$RentalPrice->deposit_amount,
                    'rental_amount' => $RentalPrice->rental_amount,
                    'total_price' => $total_amount,
                    'final_amount' => $total_amount,
                    'subscription_id' => (int)$RentalPrice->id,
                    'quantity' => 1,
                    'payment_status' => "pending",
                    // 'shipping_address' => $request->shipping_address,
                    'rent_duration' => $RentalPrice->duration,
                    'rent_status' => "pending",
                ]);

                $message = "Order created successfully";

                DB::commit();

                $InitiateSaleResponse = $this->iciciInitiateSale($order->id,'new');
                // Check responseCode
                if (isset($InitiateSaleResponse['responseCode']) && $InitiateSaleResponse['responseCode'] === 'R1000') {
                   return response()->json([
                        'status' => true,
                        'response' => "Transaction has been successfully generated.",
                        'merchantTxnNo' => $InitiateSaleResponse['merchantTxnNo'] ?? null,
                        'redirect_url' => isset($InitiateSaleResponse['redirectURI'], $InitiateSaleResponse['tranCtx'])
                                ? $InitiateSaleResponse['redirectURI'] . '?tranCtx=' . $InitiateSaleResponse['tranCtx']
                                : null,
                        // 'data' => [
                            
                        //     'showOTPCapturePage' => $InitiateSaleResponse['showOTPCapturePage'] ?? null,
                        //     'generateOTPURI'     => $InitiateSaleResponse['generateOTPURI'] ?? null,
                        //     'verifyOTPURI'       => $InitiateSaleResponse['verifyOTPURI'] ?? null,
                        //     'authorizeURI'       => $InitiateSaleResponse['authorizeURI'] ?? null,
                        //     'secureHash'         => $InitiateSaleResponse['secureHash'] ?? null,
                        // ]
                    ], 200);

                }

                // If responseCode is not R1000
                return response()->json([
                    'status' => false,
                    'response' => 'Failed to initiate transaction.',
                    'error' => $InitiateSaleResponse
                ], 400);

        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to create order.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function bookingNewPayment(Request $request){
       
        DB::beginTransaction();
        try{
            $status = $request->status;
            $order_id = $request->order_id;
            $order_amount = $request->order_amount;
            $razorpay_order_id = $request->razorpay_order_id;
            $razorpay_payment_id = $request->razorpay_payment_id;
            $razorpay_signature = $request->razorpay_signature;
            if($status==true){

                $order = Order::find($order_id);

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

                $fetchResponse = $this->PaymentFetch($razorpay_payment_id,$order_id);
                if($fetchResponse['status']){
                    $captureResponse = $this->PaymentCaptured($razorpay_payment_id,$order_amount);
                    if ($captureResponse['status']=="captured") {
                        if($captureResponse['data']['status']=="captured"){
                            $order_type = $order->subscription?Str::snake($order->subscription->subscription_type):"";
                            $payment = Payment::find($fetchResponse['payment_id']);
                            $payment->order_id = $order->id;
                            $payment->user_id = $order->user_id;
                            $payment->order_type = 'new_subscription_'.$order_type;
                            $payment->payment_method = $captureResponse['data']['method'];
                            $payment->currency = $captureResponse['data']['currency'];
                            $payment->payment_status = 'completed';
                            $payment->transaction_id = now()->format('dmyHis');
                            $payment->razorpay_order_id = $razorpay_order_id;
                            $payment->razorpay_payment_id = $razorpay_payment_id;
                            $payment->razorpay_signature = $razorpay_signature;
                            $payment->amount = $order->final_amount;
                            $payment->payment_date = now()->toDateTimeString();
                            $payment->save();
                            if($payment){
                                // Deposit Amount
                                $payment_item = new PaymentItem;
                                $payment_item->payment_id = $payment->id;
                                $payment_item->product_id = $order->product_id;
                                $payment_item->payment_for = 'new_subscription_'.$order_type;
                                $payment_item->duration = $order->rent_duration;
                                $payment_item->type = 'deposit';
                                $payment_item->amount = $order->deposit_amount;
                                $payment_item->save();
            
                                // Rental Amount
                                $payment_item = new PaymentItem;
                                $payment_item->payment_id = $payment->id;
                                $payment_item->product_id = $order->product_id;
                                $payment_item->payment_for = 'new_subscription_'.$order_type;
                                $payment_item->duration = $order->rent_duration;
                                $payment_item->type = 'rental';
                                $payment_item->amount = $order->rental_amount;
                                $payment_item->save();
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
                        }
                        return response()->json([
                            'status' => true,
                            'message' => $captureResponse['message'],
                            'data' => $captureResponse['data']
                        ], 200);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => $captureResponse['message'],
                            'error' => $captureResponse['error']
                        ], 500);
                    }
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => "Payment data not found in the response.",
                    ], 500);
                }
            }else{
                return response()->json([
                    'status' => false,
                    'message' => "Payment failed. Please try again.",
                ], 500);
            }    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to update payment.',
                'error' => $e->getMessage(),
            ], 500);
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

    private function PaymentCaptured($razorpay_payment_id,$amount){
        $api_key = env('RAZORPAY_KEY_ID');
        $api_secret = env('RAZORPAY_KEY_SECRET');
        
       $curl = curl_init();
        
        // Razorpay API URL for Payment Fetch
        $url = "https://api.razorpay.com/v1/payments/$razorpay_payment_id";
        
        // Curl Configuration
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET", // GET instead of POST
            CURLOPT_USERPWD => $api_key . ":" . $api_secret,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
        ]);
        
        // Execute Curl Request
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // Handle Response
        // Log::error('Payment Capture Failed', [
        //     'http_code' => $httpCode,
        //     'response' => $response
        // ]);
         $responseData = json_decode($response, true);
        if ($responseData['status'] == "captured") {
            return [
                'status' => true,
                'data' => $responseData,
                'message' => "Payment captured successfully."
            ];
        } else {
            return [
                'status' => false,
                'message' => "Failed to capture payment.",
                'error' => json_decode($response, true)
            ];
        }
    }
    private function PaymentFetch($razorpay_payment_id, $order_id)
    {
        // dd($razorpay_payment_id, $order_id);s
        $api_key = env('RAZORPAY_KEY_ID');
        $api_secret = env('RAZORPAY_KEY_SECRET');
        
        // Initialize Curl
        $curl = curl_init();
        $url = "https://api.razorpay.com/v1/payments/$razorpay_payment_id";

        // Curl Configuration
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET", // Corrected to GET
            CURLOPT_USERPWD => $api_key . ":" . $api_secret,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
        ]);

        // Execute Curl Request
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // Handle Response
        if ((int) $httpCode === 200) {
            $responseData = json_decode($response, true);
            // Ensure the payment data is available
            if (isset($responseData['id'])) {
                $order = Order::find($order_id);
                $order_type = $order->subscription ? Str::snake($order->subscription->subscription_type) : "";

                // Create Payment Record
                $payment = Payment::firstOrNew(['razorpay_payment_id' => $razorpay_payment_id]);
               
                $payment->order_id = $order->id;
                $payment->user_id = $order->user_id;
                $payment->order_type = 'new_subscription_' . $order_type;
                $payment->payment_method = $responseData['method'] ?? 'N/A';
                $payment->currency = $responseData['currency'] ?? 'INR';
                $payment->payment_status = $responseData['status'] ?? 'failed';
                $payment->transaction_id = now()->format('dmyHis');
                $payment->razorpay_order_id = $responseData['order_id'] ?? '';
                $payment->razorpay_payment_id = $razorpay_payment_id;
                $payment->razorpay_signature = $responseData['notes']['razorpay_signature'] ?? '';
                $payment->amount = ($responseData['amount'] ?? 0) / 100; // Convert to actual amount
                $payment->payment_date = now();
                $payment->save();
                DB::commit();
                return [
                    'status' => true,
                    'payment_id' => $payment->id,
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Payment data not found in the response.',
                    'payment_id' => null,
                ];
            }
        } else {
            return [
                'status' => false,
                'message' => "Failed to fetch payment details. HTTP Code: $httpCode",
                'payment_id' => null,
            ];
        }
    }

    public function bookNowRenewal(Request $request){
            $order_id = $request->order_id;
            $order_amount = $request->order_amount;
            
            $order = Order::with('subscription')->find($order_id);
            if(!$order){
                return response()->json([
                    'status' => false,
                    'message' => "Order not found.",
                ], 404);
            }
            $assignRider = AsignedVehicle::where('order_id', $order->id)->first();
            if(!$assignRider){
                return response()->json([
                    'status' => false,
                    'message' => "Renewal not allowed. Please try on active ride.",
                ], 403);
            }
            if($assignRider->status!="overdue"){
                return response()->json([
                    'status' => false,
                    'message' => "Renewal not allowed. Please try after the subscription end date.",
                ], 403);
            }

            $amount = number_format($order_amount, 2, '.', '');
            $orderAmount = number_format($order->rental_amount, 2, '.', '');

            if ($orderAmount !== $amount) {
                return response()->json([
                    'status' => false,
                    'message' => "Sorry, the payment amount (₹$amount) does not match the renewal subscription amount (₹$orderAmount).",
                ], 403);
            }

            $InitiateSaleResponse = $this->iciciInitiateSale($order->id,'renew');
            // Check responseCode
            if (isset($InitiateSaleResponse['responseCode']) && $InitiateSaleResponse['responseCode'] === 'R1000') {
                    return response()->json([
                    'status' => true,
                    'response' => "Transaction has been successfully generated.",
                    'merchantTxnNo' => $InitiateSaleResponse['merchantTxnNo'] ?? null,
                    'redirect_url' => isset($InitiateSaleResponse['redirectURI'], $InitiateSaleResponse['tranCtx'])
                            ? $InitiateSaleResponse['redirectURI'] . '?tranCtx=' . $InitiateSaleResponse['tranCtx']
                            : null,
                ], 200);

            }

            // If responseCode is not R1000
            return response()->json([
                'status' => false,
                'response' => 'Failed to initiate transaction.',
                'error' => $InitiateSaleResponse
            ], 400);
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

     protected function MobilizationRequest($value){
        $stock = Stock::find($value);
        $vehiclesUrl = 'https://app.loconav.sensorise.net/integration/api/v1/vehicles/'.$stock->vehicle_track_id.'/immobilizer_requests';
        $payload = [
            "value" => 'MOBILIZE',
        ];
        // dd($payload);
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
            if(isset($response['data']['id'])){
                $stock->immobilizer_status = "MOBILIZE";
                $stock->immobilizer_request_id = null;
                $stock->save();
            }
        }
            Log::error('mobilization_request', [
                'response' => $response
            ]);
    }
    public function bookingRenewPayment(Request $request){
       
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
        
        $order_id = $request->order_id;
        $status = $request->status;
        $order_amount = $request->order_amount;
        $razorpay_order_id = $request->razorpay_order_id;
        $razorpay_payment_id = $request->razorpay_payment_id;
        $razorpay_signature = $request->razorpay_signature;

        $order = Order::with('subscription')->find($order_id);
        if(!$order){
            return response()->json([
                'status' => false,
                'message' => "Order not found.",
            ], 404);
        }
        $assignRider = AsignedVehicle::where('order_id', $order->id)->first();
        if($assignRider->status!="overdue"){
            return response()->json([
                'status' => false,
                'message' => "Renewal not allowed. Please try after the subscription end date.",
            ], 403);
        }

        DB::beginTransaction();
        try{
            if($status==true){
                $amount = number_format($order_amount, 2, '.', '');
                $orderAmount = number_format($order->rental_amount, 2, '.', '');

                if ($orderAmount !== $amount) {
                    return response()->json([
                        'status' => false,
                        'message' => "Sorry, the payment amount (₹$amount) does not match the renewal subscription amount (₹$orderAmount).",
                    ], 403);
                }

                $existing_payment = Payment::where('razorpay_payment_id',$razorpay_payment_id)->first();
                if($order->payment_status=="completed" && $existing_payment){
                    return response()->json([
                        'status' => false,
                        'message' => "Payment already completed for this subscription.",
                    ], 403);
                }
                $fetchResponse = $this->PaymentFetch($razorpay_payment_id,$order_id);
                if($fetchResponse['status']){
                    $captureResponse = $this->PaymentCaptured($razorpay_payment_id,$order_amount);
                    if ($captureResponse['status']=='captured') {
                        if($captureResponse['data']['status']=="captured"){
                            $order_type = $order->subscription?Str::snake($order->subscription->subscription_type):"";
                            $payment = Payment::find($fetchResponse['payment_id']);
                            $payment->order_id = $order->id;
                            $payment->user_id = $order->user_id;
                            $payment->order_type = 'renewal_subscription_'.$order_type;
                            $payment->payment_method = $captureResponse['data']['method'];
                            $payment->currency = $captureResponse['data']['currency'];
                            $payment->payment_status = 'completed';
                            $payment->transaction_id = now()->format('dmyHis');
                            $payment->razorpay_order_id = $razorpay_order_id;
                            $payment->razorpay_payment_id = $razorpay_payment_id;
                            $payment->razorpay_signature = $razorpay_signature;
    
                            $payment->amount = $order->subscription ? $order->subscription->rental_amount : $order->rental_amount;
                            $payment->payment_date = now()->toDateTimeString();
                            $payment->save();

                            if($payment){
                                // Rental Amount
                                $payment_item = new PaymentItem;
                                $payment_item->payment_id = $payment->id;
                                $payment_item->product_id = $order->product_id;
                                $payment_item->payment_for = 'renewal_subscription_'.$order_type;
                                $payment_item->type = 'rental';
                                $payment_item->vehicle_id = $assignRider->vehicle_id;
                                $payment_item->amount = $order->subscription ? $order->subscription->rental_amount : $order->rental_amount;
                                $payment_item->duration = $order->subscription ? $order->subscription->duration : $order->rent_duration;
                                $payment_item->save();
                
                                // Update Order
                                $startDate = Carbon::parse($assignRider->end_date);
                                $endDate = $startDate->copy()->addDays($payment_item->duration);
                                
                                $order->payment_mode = "Online";
                                $order->payment_status = "completed";
                                $order->rental_amount = $payment_item->amount;
                                $order->total_price = $order->deposit_amount+$payment_item->amount;
                                $order->final_amount = $order->deposit_amount+$payment_item->amount;
                                $order->rent_duration = $payment_item->duration;
                                $order->rent_start_date = $startDate;
                                $order->rent_end_date = $endDate;
                                $order->subscription_type = 'renewal_subscription_'.$order_type;
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
                                
                                return response()->json([
                                    'status' => true,
                                    'message' => "Payment completed and subscription renewed successfully.",
                                ], 200);
                            }
                        }
                        return response()->json([
                            'status' => true,
                            'message' => $captureResponse['message'],
                            'data' => $captureResponse['data']
                        ], 200);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => $captureResponse['message'],
                            'error' => $captureResponse['error']
                        ], 500);
                    }
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => "Payment data not found in the response.",
                    ], 500);
                }
            }else{
                return response()->json([
                    'status' => false,
                    'message' => "Payment failed. Please try again.",
                ], 500);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Failed', [
                'response' => $e->getMessage()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Failed to update payment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function bookingCancel($order_id){
        DB::beginTransaction();
        try{
            $order = Order::find($order_id);
            if($order->rent_status=="active"){
                $order->cancel_request = "Yes";
                $order->cancel_request_at = now();
                $order->save();
                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => "Your cancellation request has been successfully submitted.",
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                  'message' => "Cancellation request cannot be processed at this moment.",
                ], 403);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to update payment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function myActiveSubscription(){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
        $order = Order::with('vehicle','product','subscription')->whereIn('rent_status', ['pending', 'active', 'ready to assign', 'suspended', 'deallocated'])->where('user_id', $user->id)->first();
        if($order){
            $data = [];
            // if($order->payment_status=="pending"){
            //     $data = [
            //         'product_id'=>$order->product_id,
            //         'is_driving_licence_required'=>$order->product->is_driving_licence_required,
            //         'subscription_type'=>$order->subscription->subscription_type,
            //     ];
            // }
            $data= [
                'id' => $order->id,
                'product_id'=>$order->product_id,
                'is_driving_licence_required'=>$order->product->is_driving_licence_required,
                'subscription_type'=>$order->subscription->subscription_type,
                'order_type' =>$order->order_type,
                'order_number'=>$order->order_number,
                'deposit_amount'=>$order->deposit_amount,
                'rental_amount'=>$order->rental_amount,
                'final_amount'=>$order->final_amount,
                'payment_mode'=>$order->payment_mode,
                
                'payment_status'=>$order->payment_status,
                'rent_duration'=>$order->rent_duration.' Days',
                'rent_status' => ($order->vehicle && $order->vehicle->status === 'overdue') ? 'overdue' : $order->rent_status,
                'cancel_request'=>$order->cancel_request,
                'cancel_request_at'=>$order->cancel_request_at?date('d-m-Y h:i A', strtotime($order->cancel_request_at)):"N/A",
                'model'=>$order->product?$order->product->title:"N/A",
                'vehicle'=>$order->vehicle?$order->vehicle->stock->vehicle_number:"N/A",
                'vehicle_status' =>$order->vehicle?$order->vehicle->status:"N/A",
                'rent_start_date' =>$order->vehicle?date('d-m-Y h:i A', strtotime($order->vehicle->start_date)):"N/A",
                'rent_end_date' =>$order->vehicle?date('d-m-Y h:i A', strtotime($order->vehicle->end_date)):"N/A",
                'assigned_at' =>$order->vehicle?date('d-m-Y h:i A', strtotime($order->vehicle->assigned_at)):"N/A",
            ];
            return response()->json([
                'status' => true, 
                'data' => $data, 
                'message' => "You have a subscription."
            ], 200);
        }else{
            return response()->json([
                'status' => false, 
                'message' => "No active subscription found."
            ], 404);
        }
        

    }

    public function CurrentLocation(Request $request){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
        
        // Check if the user exists
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404); // 404 Not Found
        }
        $validator = Validator::make($request->all(), [
                    'latitude' => 'required|string|max:255',
                    'longitude' => 'required|string|max:255',
                ]);

                // Check if validation fails
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => $validator->errors()->first(),
                    ], 422);
                }
           try {
                $response = UserCurrentLocation($request->latitude,$request->longitude);
                $address = null;
                if (!empty($response['display_name'])) {
                    $address = $response['display_name'];
                }

                DB::beginTransaction();

                $new = new UserLocationLog;
                $new->user_id = $user->id;
                $new->address = $address;
                $new->latitude = $request->latitude;
                $new->longitude = $request->longitude;
                $new->created_at = now();
                $new->save();

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Location retrieved and saved successfully.',
                ], 200);

            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                    // 'error' => $e->getMessage(),
                ], 500);
            }
    }

    protected function EsignVerification($signer_name,$signer_email,$signer_city)
    {
        $baseUrl = env('ESIGN_ZOOP_BASE_URL');
        $url = $baseUrl . 'contract/esign/v5/init';
        // dd($url);
        // $url = "https://live.zoop.one/contract/esign/v5/init"; // Test base URL for Zoop's eSign v5 init

        $appId = ENV('ZOOP_APP_ID');                 // Your test App ID
        $apiKey = ENV('ZOOP_APP_KEY');         // Your test API Key

        // Load and base64 encode a local PDF file
        $pdfPath = public_path('assets/users_terms_conditions.pdf'); // Make sure this path is correct
        if (!file_exists($pdfPath)) {
            return response()->json(['error' => 'PDF file not found.'], 404);
        }
        $pdfBase64 = base64_encode(file_get_contents($pdfPath));

        // Prepare payload
        $data = [
            "document" => [
                "name" => "Agreement Esigning",
                "data" => $pdfBase64
            ],
            "signers" => [
                [
                    "signer_name" => $signer_name,
                    "signer_email" => $signer_email,
                    "signer_city" => $signer_city,
                    "signer_purpose" => "Digital Sign",
                    "sign_coordinates" => [
                        [
                            "page_num" => 0,
                            "x_coord" => 270,
                            "y_coord" => 60
                        ]
                    ]
                ],
            ],
            "txn_expiry_min" => "10080",
            "white_label" => "Y",
            "redirect_url" => secure_url('api/customer/esign/thankyou'),
            "response_url" => secure_url('api/customer/esign/webhook'),
            "esign_type" => "AADHAAR",
            "email_template" => [
                "org_name" => "Zoop.One"
            ]
        ];
        // Convert payload to JSON
        $jsonData = json_encode($data);

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "app-id: $appId",
            "api-key: $apiKey"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        // Execute the request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // Close cURL
        curl_close($ch);
        $responseData = json_decode($response, true);
        if($httpCode==200){
            UserTermsConditions::updateOrCreate(
                ['email' => $responseData['requests'][0]['signer_email'] ?? null], // Lookup criteria
                [
                    'group_id' => $responseData['group_id'] ?? null,
                    'request_timestamp' => now(),
                    'request_id' => $responseData['requests'][0]['request_id'] ?? null,
                    'status' => 'pending',
                    'response_payload' => $response,
                ]
            );
        }
        // Return the response
        return $responseData;
    }

    public function webhookHandler(Request $request)
    {
        // Log the full incoming payload
        \Log::info('Zoop Webhook Called', $request->all());

        $data = $request->all();

        // Extract main fields
        $requestId = $data['request_id'] ?? null;
        $requestTimestamp = $data['request_timestamp'] ?? null;
        $responseTimestamp = $data['response_timestamp'] ?? null;

       if (isset($data['success']) && $data['success'] == true) {
            $status = 'success';
        } else {
            $status = 'pending';
        }
        

        // Extract signer & document details if available
        $signer = $data['result']['signer'] ?? [];
        $document = $data['result']['document'] ?? [];

        $signedAt = $document['signed_at'] ?? null;
        $signedUrl = $document['signed_url'] ?? null;
        $signerEmail = $signer['email'] ?? null;
        $signerName = $signer['fetched_name'] ?? null;
        $signerCity = $signer['city'] ?? null;
        $signerState = $signer['state_or_province'] ?? null;
        $signerPostalCode = $signer['postal_code'] ?? null;

        // Validate request ID
        if (!$requestId) {
            \Log::warning('Webhook received without request_id', $data);
            return response()->json(['message' => 'Missing request_id'], 400);
        }

        // Convert full payload to JSON string
        $payload = is_array($data) ? json_encode($data) : $data;

        // Find and update or create record
        $record = UserTermsConditions::where('request_id', $requestId)->first();

        $updateData = [
            'status' => $status,
            'request_timestamp' => $requestTimestamp,
            'response_timestamp' => $responseTimestamp,
            'signed_at' => $signedAt,
            'signed_url' => $signedUrl,
            'signer_email' => $signerEmail,
            'signer_name' => $signerName,
            'signer_city' => $signerCity,
            'signer_state' => $signerState,
            'signer_postal_code' => $signerPostalCode,
            'response_payload' => $payload,
        ];

        if ($record) {
            $record->update($updateData);
        } else {
            // Ensure request_id is included if creating new
            $updateData['request_id'] = $requestId;
            UserTermsConditions::create($updateData);
        }

        return response()->json(['message' => 'Webhook processed'], 200);
    }

    public function EsignThankyou(Request $request)
    {
        $action = $request->query('action'); // e.g., esign-success or esign-failed
        return view('esign.thanks');
        // return redirect()->route('digilocker.aadhar.redirecting');
    }
    public function redirectDigilockerThankyou(){
         return view('esign.thanks');
    }
    public function DigilockerThankyou(Request $request)
    {
        $action = $request->query('action'); // e.g., esign-success or esign-failed
         return view('esign.thanks');
        // return redirect()->route('digilocker.aadhar.redirecting');
    }
    public function DigilockerInit(){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
        
        // Check if the user exists
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404); // 404 Not Found
        }

        $baseUrl = env('ZOOP_BASE_URL');
        $url = $baseUrl . 'identity/digilocker/v1/init';

        $appId = env('ZOOP_APP_ID');   // Your test App ID
        $apiKey = env('ZOOP_APP_KEY');
        $redirect = secure_url("api/customer/digilocker/aadhar/thankyou");
        $response = secure_url("api/customer/digilocker/aadhar/webhook");
        
        $data = [
            "docs" => ["ADHAR"],
            "purpose" => "KYC Verification",
            "response_url" => $response,
            "redirect_url" => $redirect,
            "fast_track" => "Y",
            "pinless" => true
        ];
         
         \Log::info("Redirect URL: " . $redirect);
        \Log::info("Response URL: " . $response);
        // Convert payload to JSON
        $jsonData = json_encode($data);
        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "app-id: $appId",
            "api-key: $apiKey"
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $responseData = json_decode($response, true);

        if ($httpCode === 200 && ($responseData['success'] ?? false)) {
            DB::beginTransaction();
            try {
                DigilockerDocument::updateOrCreate(
                    ['request_id' => $responseData['request_id']],
                    [
                        'user_id' => $user->id,
                        'webhook_security_key' => $responseData['webhook_security_key'] ?? null,
                        'request_timestamp' => now(),
                        'sdk_url' => $responseData['sdk_url'] ?? null,
                    ]
                );

                DB::commit();

                return response()->json([
                    'success' => true,
                    'data' => $responseData,
                    'message' => 'Digilocker request initialized successfully.'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store Digilocker data.',
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => $responseData['response_message'] ?? 'Unknown error',
                'code' => $responseData['response_code'] ?? 'N/A',
                'metadata' => $responseData['metadata'] ?? null
            ], $httpCode);
        }
    }
    
    public function DigilockerFetch($request_id){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
        
        // Check if the user exists
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404); // 404 Not Found
        }

        $baseUrl = env('ZOOP_BASE_URL');
        $url = $baseUrl . "identity/digilocker/v1/fetch/{$request_id}";

        $appId = env('ZOOP_APP_ID');
        $apiKey = env('ZOOP_APP_KEY');

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options for GET request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true); // explicitly set GET
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "app-id: $appId",
            "api-key: $apiKey"
        ]);

        // Execute the request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $responseData = json_decode($response, true);
        // Return a proper response
        // dd($responseData);
        if ($httpCode === 200) {
            return response()->json([
                'success' => true,
                'data' => $responseData,
                'message' => 'Document fetched successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $responseData['response_message'] ?? 'Failed to fetch data',
                'code' => $responseData['response_code'] ?? $httpCode,
                'data' => $responseData
            ], $httpCode);
        }
        // $DigilockerDocument = DigilockerDocument::where('request_id',$request_id)->where('user_id',$user->id)->first();

    }
    public function generateAadhaarPdfFromXml($user_id)
    {
        $doc = DigilockerDocument::where('user_id',$user_id)->where('success',1)->where('response_message','Transaction Successful')->where('document_name','Aadhaar Card')->orderBy('id','DESC')->first();
        if(!$doc){
            return response()->json(['error' => 'not active request'], 404);
        }
       
        
        if (!$doc->raw_xml) {
            return response()->json(['error' => 'No XML data found'], 404);
        }

        // Parse XML safely
        try {
            $xml = simplexml_load_string($doc->raw_xml);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid XML format'], 500);
        }

        // Extract data from XML
        $uidData = $xml->CertificateData->KycRes->UidData ?? null;

        if (!$uidData) {
            return response()->json(['error' => 'UID data not found'], 500);
        }

        $name = (string)$uidData->Poi['name'];
        $uid = (string)$uidData['uid']; 
        // $maskedUid = str_repeat('x', 8) . substr($uid, -4);
        $dob = (string)$uidData->Poi['dob'];
        $gender = (string)$uidData->Poi['gender'];
        $address = [
            'house' => (string)$uidData->Poa['house'],
            'street' => (string)$uidData->Poa['street'],
            'vtc' => (string)$uidData->Poa['vtc'],
            'dist' => (string)$uidData->Poa['dist'],
            'state' => (string)$uidData->Poa['state'],
            'pc' => (string)$uidData->Poa['pc'],
            'country' => (string)$uidData->Poa['country'],
        ];

        if($doc->response_message=="Transaction Successful"){
            $user = User::find($user_id);
            if(!$user){
                return response()->json(['error' => 'user not found'], 403);
            }
            $user->aadhar_number = $uid;
            $user->aadhar_card_status = 2; //Verified
            $user->save();
            $existing_data = UserKycLog::where('user_id', $user->id)->where('document_type','Aadhar Card')->where('status', 'Uploaded')->first();
            $store = new UserKycLog;
            $store->status = "Uploaded";
            $store->user_id = $user->id;
            $store->created_at = now()->toDateTimeString();
            $store->document_type = 'Aadhar Card';
            $store->save();
        }
        

        $photoBase64 = (string)$uidData->Pht;
        $photoDataUri = 'data:image/jpeg;base64,' . $photoBase64;

        // Pass data to view
        $pdf = Pdf::loadView('aadhaar.pdf', [
            'maskedUid' => $uid,
            'name' => $name,
            'dob' => $dob,
            'gender' => $gender,
            'address' => $address,
            'photo' => $photoDataUri,
        ]);

        return $pdf->download('aadhaar-details.pdf');
    }


    public function webhookDigilockerHandler(Request $request)
    {
        // \Log::info('Zoop Webhook Called', $request->all());

        $data = $request->all();

        if (!isset($data['result'])) {
            \Log::warning('Zoop Webhook: No result field in payload', $data);
            return response()->json(['status' => false, 'message' => 'No documents found'], 400);
        }

        DB::beginTransaction();
        try {
            
            foreach ($data['result'] as $doc) {
                // \Log::info('Processing Document:', $doc);
                if($doc['doctype']=="ADHAR"){
                    $issued = $doc['issued'] ?? null;
                    $uidDataXml = $doc['data_xml'] ?? null;
                    
                    $saveData = [
                        'webhook_security_key' => $data['webhook_security_key'] ?? null,
                        'request_timestamp' => $data['request_timestamp'] ?? now(),
                        'success' => $data['success'],
                        'response_code' => $data['response_code'] ?? null,
                        'response_message' => $data['response_message'] ?? null,
                        'billable' => $data['metadata']['billable'] ?? null,

                        'document_name' => $issued['name'] ?? null,
                        'document_status' => $doc['status'] ?? null,
                        'fetched_at' => $doc['fetched_at'] ?? null,
                        'issuer' => $issued['issuer'] ?? null,
                        'issuer_id' => $issued['issuerid'] ?? null,
                        'issue_date' => $issued['date'] ?? null,
                        'document_uri' => $issued['uri'] ?? null,
                        'mime_types' => implode(',', $issued['mime'] ?? []),
                        'raw_xml' => $uidDataXml,

                        'kyc_code' => $this->extractKycCode($uidDataXml),
                        'kyc_response_status' => $this->extractKycStatus($uidDataXml),
                        'kyc_timestamp' => $this->extractKycTimestamp($uidDataXml),
                    ];

                    // \Log::info('Saving DigilockerDocument:', $saveData);

                   $details =  DigilockerDocument::updateOrCreate(
                        ['request_id' => $data['request_id']],
                        $saveData
                    );

                    if (!empty($uidDataXml) && ($data['response_message'] ?? '') === "Transaction Successful") {
                        $xml = simplexml_load_string($uidDataXml);
                        $uidData = $xml->CertificateData->KycRes->UidData ?? null;

                        if ($uidData) {
                            $uid = (string)$uidData['uid'];

                            $userId = $details->user_id ?? null;
                            if ($userId && ($user = User::find($userId))) {
                                $user->aadhar_number = $uid;
                                $user->aadhar_card_status = 2; // Verified
                                $user->save();

                                $existing_data = UserKycLog::where('user_id', $user->id)->where('document_type','Aadhar Card')->where('status', 'Uploaded')->first();
                                $store = new UserKycLog;
                                if($existing_data){
                                    $store->status = 'Re-uploaded';
                                }else{
                                    $store->status = "Uploaded";
                                }
                                $store->user_id = $user->id;
                                $store->created_at = now()->toDateTimeString();
                                $store->document_type = 'Aadhar Card';
                                $store->save();

                                \Log::info("Aadhaar number {$uid} saved to user ID {$user->id}");
                            } else {
                                \Log::warning("User not found or invalid user ID for request_id: " . $data['request_id']);
                                \Log::debug("user_id from details: " . json_encode($details->user_id));
                            }
                        } else {
                            \Log::warning("UID data not found in XML for request_id: " . $data['request_id']);
                            \Log::debug("Parsed XML: " . $uidDataXml);
                        }
                    } else {
                        \Log::warning("UID XML missing or transaction not successful.");
                        \Log::debug("Conditions: uidDataXml=" . (!empty($uidDataXml) ? 'present' : 'missing') . ", response_message=" . ($data['response_message'] ?? 'null'));
                    }

                }
                // \Log::info('Document saved/updated successfully');
            }

            DB::commit();
            // \Log::info('All documents committed successfully.');
            return response()->json(['status' => true, 'message' => 'Digilocker document(s) stored']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Zoop Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    private function extractKycCode($xml)
    {
        if (!$xml) return null;
        $xmlObj = simplexml_load_string($xml);
        return (string) ($xmlObj->CertificateData->KycRes['code'] ?? '');
    }

    private function extractKycStatus($xml)
    {
        if (!$xml) return null;
        $xmlObj = simplexml_load_string($xml);
        return (string) ($xmlObj->CertificateData->KycRes['ret'] ?? '');
    }

    private function extractKycTimestamp($xml)
    {
        if (!$xml) return null;
        $xmlObj = simplexml_load_string($xml);
        return (string) ($xmlObj->CertificateData->KycRes['ts'] ?? '');
    }

    private function iciciInitiateSale($order_id,$type){
        $order = Order::find($order_id);
        if($type=='new'){
            $formattedAmount = number_format((float)$order->final_amount, 2, '.', ''); // Always "100.00" format
        }else{
             $formattedAmount = number_format((float)$order->rental_amount, 2, '.', ''); // Always "100.00" format
        }
       

        $data = [
            "merchantId"=> env('ICICI_MARCHANT_ID'),
            "merchantTxnNo"=> $order->order_number.'_'.date('YmdHis'),
            "amount"=> $formattedAmount,
            "currencyCode"=> "356",
            "payType"=> "0",       // This is to capture payment details on PG payament page
            "customerEmailID"=> optional($order->user)->email??"testmail123@gmail.com",
            "transactionType"=> "SALE",
            "txnDate"=> date('YmdHis'),
            // "returnURL"=> 'http://127.0.0.1:8000/api/customer/icici/thankyou',
            "returnURL"=> secure_url('api/customer/icici/thankyou'),
            "customerMobileNo"=> "91".optional($order->user)->mobile??"9876543210",
        ];
        // Create secureHash
        $hashKey = implode('', [
            $data["amount"],
            $data["currencyCode"],
            $data["customerEmailID"],
            $data["customerMobileNo"],
            $data["merchantId"],
            $data["merchantTxnNo"],
            $data["payType"],
            $data["returnURL"],
            $data["transactionType"],
            $data["txnDate"]
        ]);
        
        $data['secureHash'] = hash_hmac('sha256', $hashKey, env('ICICI_MARCHANT_SECRET_KEY'));

        // Send request to Phicommerce using cURL
        $ch = curl_init(env('ICICI_PAYMENT_INITIATE_BASH_URL'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return response()->json(['error' => $error], 500);
        }

        curl_close($ch);
        $InitiateSaleResponse = json_decode($response, true);
        // dd($InitiateSaleResponse);
        if (isset($InitiateSaleResponse['responseCode']) && $InitiateSaleResponse['responseCode'] === 'R1000') {
            OrderMerchantNumber::updateOrCreate(
                ['order_id' => $order_id],
                [
                    'type'  => $type ?? 'new',
                    'merchantTxnNo'  => $InitiateSaleResponse['merchantTxnNo'] ?? null,
                    'redirect_url'   => $InitiateSaleResponse['redirectURI'] ?? null,
                    'secureHash'     => $InitiateSaleResponse['secureHash'] ?? null,
                    'tranCtx'        => $InitiateSaleResponse['tranCtx'] ?? null,
                    'amount'         => $formattedAmount ?? 0.00,
                ]
            );
            $payment = Payment::where('order_id', $order_id)
                //   ->where('order_type', 'like', 'new_subscription_%')
                  ->where('payment_status','authorized')
                  ->first();

            if ($payment) {
                $payment->update([
                    'icici_merchantTxnNo' => $InitiateSaleResponse['merchantTxnNo'],
                    'payment_status' => 'authorized',
                    'user_id' => $order->user_id,
                    'payment_date' => now()->toDateTimeString(),
                    'amount' => $formattedAmount ?? 0.00,
                ]);
            } else {
                Payment::create([
                    'order_id' => $order_id,
                    'user_id' => $order->user_id,
                    'payment_status' => 'authorized',
                    'icici_merchantTxnNo' => $InitiateSaleResponse['merchantTxnNo'],
                    'payment_date' => now()->toDateTimeString(),
                    'amount' => $formattedAmount ?? 0.00,
                ]);
            }
        }
        // Return JSON decoded response to mobile app
        return json_decode($response, true);
    }
    
    public function iciciInitiateSaleConfirmed($merchantTxnNo){
        $OrderMerchantNumber = OrderMerchantNumber::where('merchantTxnNo',$merchantTxnNo)->first();
        if(!$OrderMerchantNumber){
              return response()->json([
                    'status' => false,
                    'message' => 'No data found by this merchantTxnNo.',
                ], 400);
        }
        $merchantID = env('ICICI_MARCHANT_ID');
        $transactionType = 'STATUS';

        // Retrieve these from DB if needed
        $originalTxnNo = $merchantTxnNo;
        $amount = $OrderMerchantNumber->amount; // Ideally, fetch actual amount from your DB using this txn no

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
        curl_close($ch);

        $InitiateSaleResponse = json_decode($response, true);
        // dd($InitiateSaleResponse);
        if (isset($InitiateSaleResponse['responseCode']) && $InitiateSaleResponse['responseCode'] === '000' && $InitiateSaleResponse['txnStatus'] === 'SUC') {
            $bookingResponse = $this->bookingNewICICIPayment($merchantTxnNo,$InitiateSaleResponse['txnID'],$InitiateSaleResponse['paymentMode'],$InitiateSaleResponse['paymentDateTime']);
             return response()->json(json_decode($response, true));
        }else{
            // Return the parsed response
            return response()->json(json_decode($response, true));
        }
        
    }

    public function ICICIThankyou(Request $request)
    {
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
            $message = 'No data found by this merchantTxnNo.';
            return view('icici.thanks', compact('message'));
        }
        // Case: Payment success
        if (
            isset($response['respDescription']) &&
            $response['respDescription'] === 'Transaction successful'
        ) {
            PaymentLog::create([
                'gateway' => 'ICICI',
                'transaction_id' => $response['txnID'] ?? null,
                'merchant_txn_no' => $response['merchantTxnNo'] ?? null,
                'response_payload' => json_encode($response),
                'status' => $response['responseCode'] ?? null,
                'message' => isset($response['respDescription']) ? $response['respDescription'] . '(completed)' : null,
            ]);
            if($OrderMerchantNumber->type==='new'){
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
            }else{

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
            $decodedResponse = json_decode($bookingResponse->getContent(), true);
            // Extract message
            if($decodedResponse['status']==true){
                $success_message = $decodedResponse['message'] ?? 'Payment processed successfully.';
            }else{
                $message = $decodedResponse['message'] ?? 'Payment processed successfully.';
            }
            
            return view('icici.thanks', compact('response','success_message','message'));
        }else{
             return view('icici.thanks', compact('response','success_message','message'));
        } 
    }


}
