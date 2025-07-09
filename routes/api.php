<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OrderController;

Route::prefix('customer')->group(function () {
    // User Registration Route
    Route::post('register', [AuthController::class, 'register']);

        // User Login Route
        Route::post('login', [AuthController::class, 'login']);
        Route::post('requestotp', [AuthController::class, 'requestotp']);
        Route::post('verifyOtp', [AuthController::class, 'verifyOtp']);
        Route::post('resetPassword', [AuthController::class, 'resetPassword']);

     // Protected routes for authenticated users
    Route::middleware(['auth.sanctum.custom'])->group(function () {
        // User Profile Route
        Route::get('profile', [AuthController::class, 'userProfile']);
        Route::get('home-page',[AuthController::class,'HomePage']);
        Route::get('banners', [AuthController::class, 'fetchBanners']);
        Route::get('faqs', [AuthController::class, 'fetchFaqs']);
        Route::get('product-list', [AuthController::class, 'ProductList']);
        Route::get('selling/product-list', [AuthController::class, 'SellingProductList']);
        Route::get('selling/product-details/{id}', [AuthController::class, 'SellingProductDetails']);
        Route::get('product-details/{id}', [AuthController::class, 'ProductDetails']);
        Route::get('product/filter', [AuthController::class, 'ProductFilter']);
        Route::post('selling/query-request', [AuthController::class, 'SellingQueryRequest']);
        
        // Change Password Route
        Route::post('changePassword', [AuthController::class, 'changePassword']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
        Route::get('document-status', [AuthController::class, 'DocumentStatus']);
        Route::post('update-document', [AuthController::class, 'updateDocument']);
        Route::get('revokeTokens', [AuthController::class, 'revokeTokens']);

        Route::get('offer-list', [AuthController::class,'OfferList']);
        Route::get('order-history', [AuthController::class,'OrderHistory']);
        Route::get('sell-order-history/{user_id}', [AuthController::class,'SellOrderHistory']);
        Route::get('rent-order-history/{user_id}', [AuthController::class,'RentOrderHistory']);
        Route::get('order-details/{order_id}', [AuthController::class, 'OrderDetails']);
        Route::get('company-policies', [AuthController::class, 'CompanyPolicy']);
        Route::get('company-policy/details/{id}', [AuthController::class, 'CompanyPolicyDetails']);
        Route::post('book-now', [AuthController::class, 'bookNow']);
        // Route::post('booking-new-payment', [AuthController::class, 'bookingNewPayment']);
        // Route::post('booking-renew-payment', [AuthController::class, 'bookingRenewPayment']);
        Route::get('booking-cancel/{order_id}', [AuthController::class, 'bookingCancel']);
        Route::get('my-active-subscription', [AuthController::class, 'myActiveSubscription']);

        Route::get('order/existing-payment-type', [OrderController::class, 'ExistingPaymentType']);
        Route::post('apply/coupon', [OrderController::class, 'ApplyCoupon']);
       
        Route::post('order/create', [OrderController::class, 'createOrder']);
        Route::get('payment/history', [AuthController::class, 'paymentHistory']);
        Route::post('current-location', [AuthController::class, 'CurrentLocation']);

        Route::get('/current-location', function () {
            return response()->json([
                'success' => false,
                'message' => 'GET method not allowed for this route. Please use POST.'
            ], 405);
        });

        // Digilocker
        Route::get('digilocker/aadhar/init', [AuthController::class, 'DigilockerInit']);
        Route::get('digilocker/aadhar/fetch/{request_id}', [AuthController::class, 'DigilockerFetch']);
        
    });
    Route::get('digilocker/aadhar/download/{user_id}', [AuthController::class, 'generateAadhaarPdfFromXml'])->name('digilocker.aadhar.download');

    Route::post('booking-new-payment', [AuthController::class, 'bookingNewPayment']);
    Route::post('booking-new-icici-payment', [AuthController::class, 'bookingNewICICIPayment']);
    Route::post('booking-renew-payment', [AuthController::class, 'bookNowRenewal']);
    Route::get('esign/verification', [AuthController::class, 'EsignVerification']);
    
    
    Route::match(['GET', 'POST'], 'esign/thankyou', [AuthController::class, 'EsignThankyou']);
    Route::post('esign/webhook', [AuthController::class, 'webhookHandler']);
    
    // Digilocker
    
    Route::match(['GET', 'POST'], 'digilocker/aadhar/thankyou', [AuthController::class, 'DigilockerThankyou']);

    // Route::get('digilocker/aadhar/redirecting', [AuthController::class, 'redirectDigilockerThankyou'])->name('digilocker.aadhar.redirecting');
    Route::post('digilocker/aadhar/webhook', [AuthController::class, 'webhookDigilockerHandler']);
    Route::post('/icici/initiate-sale', [AuthController::class, 'iciciInitiateSale']);
    Route::match(['GET', 'POST'], 'icici/thankyou', [AuthController::class, 'ICICIThankyou']);
    Route::get('/icici/initiate-sale/confirmed/{merchantTxnNo}', [AuthController::class, 'iciciInitiateSaleConfirmed']);
});
