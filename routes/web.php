<?php

use Illuminate\Support\Facades\Route;
// Livewire Components
use App\Livewire\AdminLogin;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CronController;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Livewire\Admin\{CustomerAdd, Dashboard, CustomerIndex, CustomerDetails,OrderIndex,OfferIndex, PolicyDetails, OrderDetail,CityIndex,PincodeIndex,RiderEngagement,PaymentSummary,PaymentUserSummary,UserPaymentHistory,PaymentVehicleSummary,RefundSummary,ChangePassword};
use App\Livewire\Product\{
    MasterCategory, MasterSubCategory, MasterProduct, AddProduct, UpdateProduct,
    GalleryIndex, StockProduct, MasterProductType,ProductWiseVehicle,VehicleList,MasterSubscription,VehicleCreate,VehicleUpdate,VehicleDetail,VehiclePaymentSummary,BomPartList,SellingQuery
};
use App\Livewire\Master\{BannerIndex, FaqIndex, WhyEwentIndex,EmployeeManagementList,EmployeeManagementCreate,EmployeeManagementUpdate,DesignationIndex,DesignationPermissionList};

// Public Route for Login

Route::get('admin/login', action: AdminLogin::class)->name('login');
Route::get('admin/reset-password', action: ChangePassword::class)->name('admin.reset-password');

Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Default Root Route
Route::get('/', function () { return redirect()->route('login');});
// Admin Routes - Authenticated and Authorized
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    // Dashboard and Customer Routes
    Route::get('dashboard', Dashboard::class)->name('admin.dashboard');
    Route::group(['prefix' => 'rider'], function () {
        Route::get('add', CustomerAdd::class)->name('admin.customer.add')->middleware('check.permission');
        Route::get('verification/list', CustomerIndex::class)->name('admin.customer.verification.list')->middleware('check.permission');
        Route::get('engagement/list', RiderEngagement::class)->name('admin.customer.engagement.list')->middleware('check.permission');
        Route::get('details/{id}', CustomerDetails::class)->name('admin.customer.details')->middleware('check.permission');
    });
    // Product Routes
    Route::group(['prefix' => 'models'], function () {
        Route::get('/list', MasterProduct::class)->name('admin.product.index')->middleware('check.permission');
        Route::get('/categories', MasterCategory::class)->name('admin.product.categories');
        Route::get('/sub-categories', MasterSubCategory::class)->name('admin.product.sub_categories');

        Route::get('/keywords', MasterProductType::class)->name('admin.product.type');
        Route::get('/new', AddProduct::class)->name('admin.product.add')->middleware('check.permission');
        Route::get('/update/{productId}', UpdateProduct::class)->name('admin.product.update')->middleware('check.permission');
        Route::get('/gallery/{product_id}', GalleryIndex::class)->name('admin.product.gallery');
        Route::get('/subscriptions', MasterSubscription::class)->name('admin.model.subscriptions')->middleware('check.permission');
    });

    Route::group(['prefix' => 'stock'], function () {
        Route::get('/list', StockProduct::class)->name('admin.product.stocks');
        Route::get('/vehicle/{product_id}', ProductWiseVehicle::class)->name('admin.product.stocks.vehicle');
    });
    Route::group(['prefix' => 'bom-parts'], function () {
        Route::get('/', BomPartList::class)->name('admin.bom_part.list');
        // Route::get('/vehicle/{product_id}', ProductWiseVehicle::class)->name('admin.product.stocks.vehicle');
    });
    Route::group(['prefix' => 'bom-parts'], function () {
      Route::get('/', BomPartList::class)->name('admin.bom_part.list');
      // Route::get('/vehicle/{product_id}', ProductWiseVehicle::class)->name('admin.product.stocks.vehicle');
  });
  Route::group(['prefix' => 'selling-query'], function () {
    Route::get('/', SellingQuery::class)->name('admin.selling_query.list');
});
    Route::group(['prefix' => 'vehicle'], function () {
        Route::get('/list', VehicleList::class)->name('admin.vehicle.list')->middleware('check.permission');
        Route::get('/create', VehicleCreate::class)->name('admin.vehicle.create')->middleware('check.permission');
        Route::get('/update/{id}', VehicleUpdate::class)->name('admin.vehicle.update')->middleware('check.permission');
        Route::get('/details/{vehicle_id}', VehicleDetail::class)->name('admin.vehicle.detail')->middleware('check.permission');
        Route::get('/payment/summary/{vehicle_id}', VehiclePaymentSummary::class)->name('admin.vehicle.payment-summary');
    });
    // Order Management
    Route::group(['prefix'=>'order'], function(){
        Route::get('/list', OrderIndex::class)->name('admin.order.list');
        Route::get('/details/{id}', OrderDetail::class)->name('admin.order.detail');
    });
    // Payment Management
    Route::group(['prefix'=>'payment'], function(){
        Route::get('/summary/{model_id?}/{vehicle_id?}', PaymentSummary::class)->name('admin.payment.summary')->middleware('check.permission');
        Route::get('/vehicle/summary/{model_id?}/{vehicle_id?}', PaymentVehicleSummary::class)->name('admin.payment.vehicle.summary')->middleware('check.permission');
        Route::get('/user-history/{user_id}', PaymentUserSummary::class)->name('admin.payment.user_history')->middleware('check.permission');
        Route::get('/user/payment-history', UserPaymentHistory::class)->name('admin.payment.user_payment_history')->middleware('check.permission');
        Route::get('/refund-summary', RefundSummary::class)->name('admin.payment.refund.summary');
    });
    // Offer Management
    Route::group(['prefix'=>'offer'], function(){
        Route::get('/list', OfferIndex::class)->name('admin.offer.list');
    });

    // Master Routes
    Route::group(['prefix' => 'master'], function () {
        Route::get('/banner', BannerIndex::class)->name('admin.banner.index')->middleware('check.permission');
        Route::get('/faq', FaqIndex::class)->name('admin.faq.index')->middleware('check.permission');
        Route::get('/why-ewent',WhyEwentIndex::class)->name('admin.why-ewent')->middleware('check.permission');
        Route::get('/policy-details',PolicyDetails::class)->name('admin.policy-details')->middleware('check.permission');
    });

    // Employee Management

    Route::group(['prefix'=>'employee'], function(){
        Route::get('list', EmployeeManagementList::class)->name('admin.employee.list')->middleware('check.permission');
        Route::get('create', EmployeeManagementCreate::class)->name('admin.employee.create')->middleware('check.permission');
        Route::get('update/{id}', EmployeeManagementUpdate::class)->name('admin.employee.update')->middleware('check.permission');
        Route::get('/designations', DesignationIndex::class)->name('admin.designation.index')->middleware('check.permission');
        Route::get('/designation/permission/{id}', DesignationPermissionList::class)->name('admin.designation.permission')->middleware('check.permission');
    });

    // Location Management
    Route::group(['prefix'=>'location'], function(){
        Route::get('/city', CityIndex::class)->name('admin.city.index');
        Route::get('/pincodes', PincodeIndex::class)->name('admin.pincode.index');
    });
});

// Cron
Route::group(['prefix'=>'cron'], function(){
    Route::get('/test', [CronController::class,'TestLog']);
    Route::get('/vehicles/daily-timeline', [CronController::class,'DailyVehicleLog']);
    Route::get('/vehicles/check/payment-overdue', [CronController::class,'VehiclePaymentOverDue']);
    Route::get('/vehicles/overdue/immobilizer-requests', [CronController::class,'OverDueImmobilizerRequests']);
});

Route::get('/scrape-employees', function () {
    $client = new Client([
        'base_uri' => 'https://ewent.quickdemo.in',
        'cookies' => true, // Required for session
        'timeout' => 10.0,
        'allow_redirects' => true,
    ]);

    // Step 1: Fetch login page and extract CSRF token
    try {
        $loginPage = $client->get('/admin/login');
        $html = (string) $loginPage->getBody();
        $crawler = new Crawler($html);

        $token = $crawler->filter('input[name="_token"]')->attr('value');
    } catch (\Exception $e) {
        return '❌ Failed to load login form: ' . $e->getMessage();
    }

    // Step 2: Login using CSRF token and valid credentials
    try {
        $loginResponse = $client->post('/internal-admin-login', [
            'headers' => [
                'Accept' => '*/*',
                'User-Agent' => 'Mozilla/5.0 (ScraperBot)',
            ],
            'form_params' => [
                '_token' => $token,
                'email' => 'admin@gmail.com',   // ✅ Use actual admin login
                'password' => 'secret',         // ✅ Use actual password
            ],
        ]);
    } catch (\Exception $e) {
        return '❌ Login request failed: ' . $e->getMessage();
    }

    if ($loginResponse->getStatusCode() !== 200) {
        return '❌ Login failed.';
    }

    // Step 3: Fetch employee list page
    try {
        $response = $client->get('/admin/employee/list');
    } catch (\Exception $e) {
        return '❌ Failed to load employee list: ' . $e->getMessage();
    }

    if ($response->getStatusCode() !== 200) {
        return '❌ Employee list page not available.';
    }

    // Step 4: Parse and store employee data
    $html = $response->getBody()->getContents();
    $crawler = new Crawler($html);

    $employees = [];
    $count = 0;

    // Step 3: Scrape paginated results
    $employees = [];
    $page = 1;
    $totalCount = 0;

    while (true) {
        try {
            $response = $client->get("/admin/employee/list?page={$page}");
        } catch (\Exception $e) {
            break; // Stop if page fails
        }

        $html = (string) $response->getBody();
        $crawler = new Crawler($html);

        $rows = $crawler->filter('table tbody tr');

        if ($rows->count() === 0) {
            break; // No more data
        }

        $rows->each(function ($node) use (&$employees, &$totalCount) {
            $columns = $node->filter('td');

            if ($columns->count() >= 3) {
                $fullText = $columns->eq(1)->text();
                $designation = $columns->eq(2)->text();

                preg_match('/^(.*?)\s*\+91\s?(\d{10})$/s', $fullText, $matches);

                $name = trim($matches[1] ?? '');
                $phone = trim($matches[2] ?? '');

                if ($name && $phone) {
                    $employees[] = [
                        'name' => $name,
                        'phone' => $phone,
                        'designation' => $designation,
                    ];
                    $totalCount++;
                }
            }
        });

        $page++; // Go to next page
    }

    return response()->json([
        'total_employees' => $totalCount,
        'data' => $employees,
    ]);
    return "✅ Scraped and stored {$count} employee(s) successfully!";
});

