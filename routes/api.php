<?php
include('validation.php');

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BankAccountController;
use App\Http\Controllers\Api\BankCardController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\CustomerVehicleController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderRatingController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ServiceDefinitionController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\StorePromoterController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AppVersionController;
use App\Http\Controllers\FirebasePushController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['return_json', 'api.locale_and_timezone', 'platform.status'])->group(function () {

    // route not found
    Route::fallback(function () {
        return response()->json([
            "message" => __('locale.api.errors.not_found_message'),
        ], Response::HTTP_NOT_FOUND);
    });

    // check connection
    Route::get('check-server-connection', function () {
        return response()->json([
            "message" => __('locale.api.server_connection_success'),
        ], Response::HTTP_OK);
    });


    Route::get('/app-version', [AppVersionController::class, 'getLatestVersion']);
    Route::get('/app-versions', [AppVersionController::class, 'getAllVersions']);

    // auth
    Route::prefix('auth')->name('auth.')->group(function () {

        // registration
        Route::prefix('register')->name('register.')->group(function () {
            Route::post('create', [AuthController::class, 'register'])->name('create');
            Route::post('request-verify-phone', [AuthController::class, 'requestVerifyPhoneForRegistration'])->name('request_verify_phone')
                ->middleware(['throttle:resend-phone-otp-code']);
            Route::post('verify-phone', [AuthController::class, 'verifyPhoneForRegistration'])->name('verify_phone');
        });

        // login
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('login-with-phone', [AuthController::class, 'loginWithPhone'])->name('login_with_phone')
            ->middleware(['throttle:resend-phone-otp-code']);
        Route::post('login-with-phone-verify', [AuthController::class, 'verifiyLoginWithPhone'])->name('login_with_phone_verify');

        // account verification
        Route::prefix('verify')->name('verify.')->group(function () {
            //
        });

        // forget password
        Route::prefix('forget-password')->name('forget_password.')->group(function () {
            Route::post('email', [AuthController::class, 'forgetPasswordWithEmail'])->name('email')
                ->middleware(['throttle:resend-email-otp-code']);
            Route::post('phone', [AuthController::class, 'forgetPasswordWithPhone'])->name('phone')
                ->middleware(['throttle:resend-phone-otp-code']);
            Route::prefix('verify')->name('verify.')->group(function () {
                Route::post('email', [AuthController::class, 'verifyForgetPasswordWithEmail'])->name('email');
                Route::post('phone', [AuthController::class, 'verifyForgetPasswordWithPhone'])->name('phone');
            });
        });

        // reset password
        Route::prefix('reset-password')->name('reset_password.')->group(function () {
            Route::post('email', [AuthController::class, 'resetPasswordWithEmail'])->name('email');
            Route::post('phone', [AuthController::class, 'resetPasswordWithPhone'])->name('phone');
        });
    });

    // types (no auth)
    Route::prefix('types')->group(function () {
        Route::get('special-needs-types-list', [TypeController::class, 'getSpecialNeedsTypes']);
    });
});

Route::middleware(['auth:sanctum', 'return_json', 'api.locale_and_timezone', 'platform.status'])->group(function () {
    // auth user
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('get-auth-user', [AuthController::class, 'getAuthUser'])->name('get_auth_user');
        Route::post('change-password', [AuthController::class, 'changePassword'])->name('change_password');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        // account verification
        Route::prefix('verify')->name('verify.')->group(function () {
            Route::post('request-email', [AuthController::class, 'requestVerifyAuthEmail'])->name('request_email')
                ->middleware(['throttle:resend-email-otp-code']);
            Route::post('request-phone', [AuthController::class, 'requestVerifyAuthPhone'])->name('request_phone')
                ->middleware(['throttle:resend-phone-otp-code']);
            Route::post('email', [AuthController::class, 'verifyAuthEmail'])->name('email');
            Route::post('phone', [AuthController::class, 'verifyAuthPhone'])->name('phone');
        });
    });

    // user profile
    Route::prefix('user-profile')->group(function () {
        Route::post('view', [UserProfileController::class, 'show']);
        Route::match(['put', 'patch', 'post'], 'update', [UserProfileController::class, 'update']);
        Route::match(['put', 'patch', 'post'], 'update-profile-photo', [UserProfileController::class, 'updateUserProfilePhoto']);
        Route::middleware(['api.role:customer'])->group(function () {
            Route::match(['put', 'patch', 'post'], 'update-special-needs-profile', [UserProfileController::class, 'updateSpecialNeedsProfile']);
            Route::match(['delete', 'post'], 'delete-special-needs-profile', [UserProfileController::class, 'destroySpecialNeedsProfile']);
            Route::match(['delete', 'post'], 'delete-user', [UserProfileController::class, 'destroy']);
            // Route::post('delete-user-profile', [UserProfileController::class, 'destroy']);
        });
    });

    // types (with auth)
    Route::prefix('types')->withoutMiddleware(['auth:sanctum',])->group(function () {
        Route::get('countries-list', [TypeController::class, 'getCountries']);
        Route::get('cities-list', [TypeController::class, 'getCities']);
        Route::get('languages-list', [TypeController::class, 'getLanguages']);
        Route::get('business-types-list', [TypeController::class, 'getBusinessTypes']);
        Route::get('coupon-types-list', [TypeController::class, 'getCouponTypes']);
        Route::get('discount-types-list', [TypeController::class, 'getDiscountTypes']);
        // Route::get('special-needs-types-list', [TypeController::class, 'getSpecialNeedsTypes']);
        Route::get('employee-roles-list', [TypeController::class, 'getEmployeeRoles']);
    });

    // bank accounts
    Route::prefix('bank-accounts')->middleware(['api.role:owner'])->group(function () {
        Route::post('list', [BankAccountController::class, 'index']);
        Route::post('create', [BankAccountController::class, 'store']);
        Route::post('view', [BankAccountController::class, 'show']);
        Route::post('update/{id}', [BankAccountController::class, 'update']);
        Route::post('delete', [BankAccountController::class, 'destroy']);
    });

    // branches
    Route::prefix('branches')->group(function () {
        Route::middleware(['api.role:owner|worker_supervisor'])->group(function () {
            Route::post('list', [BranchController::class, 'index']);
            Route::middleware(['api.role:owner'])->group(function () {
                Route::post('create', [BranchController::class, 'store']);
                Route::post('view', [BranchController::class, 'show']);
                Route::match(['put', 'patch', 'post'], 'update/{id}', [BranchController::class, 'update']);
                Route::post('delete', [BranchController::class, 'destroy']);
            });
        });

        Route::middleware(['api.role:customer'])->group(function () {
            Route::post('list-of-customer-favorite-branches', [BranchController::class, 'getCustomerFavoriteBranches']);
            Route::post('toggle-customer-favorite-branch', [BranchController::class, 'toggleCustomerFavoriteBranch']);
        });

//        Route::middleware(['api.role:customer'])->group(function () {

//        });

        Route::middleware(['api.role:worker_supervisor'])->group(function () {
            Route::post('update-branch-work-status', [BranchController::class, 'updateBranchWorkStatus']);
        });
        Route::middleware(['api.role:owner|worker_supervisor'])->group(function () {
            Route::post('view-branch-stats', [BranchController::class, 'viewBranchStats']);
            Route::post('export-branch-stats', [BranchController::class, 'exportBranchStats']);
        });
        Route::get('{id}/orders-count', [BranchController::class, 'getOrdersCountsInBranch']);
        Route::post('record-branch-visitor', [BranchController::class, 'recordBranchVistor']);
        Route::post('check-branch-working-status', [BranchController::class, 'checkBranchWorkingStatus']);
    });


    // products
    Route::prefix('products')->group(function () {
        Route::post('list', [ProductController::class, 'index']);
        Route::post('view', [ProductController::class, 'show']);
        Route::middleware(['api.role:owner'])->group(function () {
            Route::post('create', [ProductController::class, 'store']);
            Route::match(['put', 'patch', 'post'], 'update/{id}', [ProductController::class, 'update']);
            Route::post('delete', [ProductController::class, 'destroy']);
        });

        Route::post('product-categories-list', [ProductController::class, 'getProductCategories']);
        Route::post('filtered-product-categories-list', [ProductController::class, 'getFilteredProductCategories']);
    });

    // orders
    Route::prefix('orders')->group(function () {
        Route::post('view', [OrderController::class, 'show']);
        Route::post('get-order-status', [OrderController::class, 'getOrderStatus']);
        // orders with firebase
        Route::prefix('firebase')->group(function () {
            Route::post('create-order', [OrderController::class, 'addOrderFromFirebase']);
            Route::post('receive-order', [OrderController::class, 'getOrderFromFirebase']);
        });
        // order rating
        Route::post('create-rating', [OrderRatingController::class, 'store']);
        // customer orders
        Route::prefix('customers')->middleware(['api.role:customer'])->group(function () {
            Route::post('list', [OrderController::class, 'indexForCustomer']);
            Route::post('order-processing-counts', [OrderController::class, 'orderProcessingCounts']);
            Route::post('cancel-order', [OrderController::class, 'cancelOrder']);
        });
        // employee orders
        Route::prefix('employees')->middleware(['api.role:worker_supervisor|worker'])->group(function () {
            Route::post('list', [OrderController::class, 'indexForEmployee']);
            Route::post('refund-order-list', [OrderController::class, 'refundOrderList']);
            Route::post('making-order', [OrderController::class, 'makingOrderPage']);
            Route::post('update-status', [OrderController::class, 'changeOrderStatus']);
            Route::post('upload-images', [OrderController::class, 'uploadOrderImages']);
            Route::post('count-orders-by-weekday', [OrderController::class, 'countOrdersByWeekdayForEmployee']);
            Route::post('count-orders-by-week', [OrderController::class, 'countOrdersByWeeksForEmployee']);
            Route::post('count-orders-by-three-months', [OrderController::class, 'countOrdersByThreeMonthsForEmployee']);
        });
        // store order summary
        Route::prefix('stores')->middleware(['api.role:owner'])->group(function () {
            Route::post('sales-by-weekday', [OrderController::class, 'salesByWeekdayOfStore']);
            Route::post('sales-by-week', [OrderController::class, 'salesByWeekOfStore']);
            Route::post('sales-by-three-months', [OrderController::class, 'salesByThreeMonthsOfStore']);
        });
    });

    // coupons
    Route::prefix('coupons')->group(function () {
        Route::post('list-of-coupons', [CouponController::class, 'index']);
        Route::post('create-coupon', [CouponController::class, 'store']);
        Route::post('view-coupon', [CouponController::class, 'show']);
        Route::match(['put', 'patch', 'post'], 'update-coupon/{id}', [CouponController::class, 'update']);
        // Route::post('delete-coupon', [CouponController::class, 'destroy']);
        Route::post('list-of-coupons-for-stores', [CouponController::class, 'couponsForStores']);
    });

    // customer routes
    Route::prefix('customers')->middleware(['api.role:customer'])->group(function () {
        //  vehicles
        Route::post('list-of-vehicles', [CustomerVehicleController::class, 'index']);
        Route::post('create-vehicle', [CustomerVehicleController::class, 'store']);
        Route::post('view-vehicle', [CustomerVehicleController::class, 'show']);
        Route::match(['put', 'patch', 'post'], 'update-vehicle/{id}', [CustomerVehicleController::class, 'update']);
        Route::post('delete-vehicle', [CustomerVehicleController::class, 'destroy']);

        // bank cards
        Route::prefix('bank-cards')->group(function () {
            Route::post('list', [BankCardController::class, 'index']);
            Route::post('create', [BankCardController::class, 'store']);
            Route::post('view', [BankCardController::class, 'show']);
            Route::post('update-default', [BankCardController::class, 'update_default_bank_card']);
            Route::match(['put', 'patch', 'post'], 'update/{id}', [BankCardController::class, 'update']);
            Route::post('delete', [BankCardController::class, 'destroy']);
        });

        //invoice
        Route::prefix('invoices')->group(function () {
            Route::post('list', [InvoiceController::class, 'list']);
            Route::post('view', [InvoiceController::class, 'show']);
        });
    });

    // employee routes
    Route::prefix('employees')->middleware(['api.role:owner|worker_supervisor|worker'])->group(function () {
        // employee work-time routes (only for worker_supervisor + worker)
        Route::middleware(['api.role:worker_supervisor|worker'])->group(function () {
            // employee workday
            Route::post('start-workday', [EmployeeController::class, 'startWorkDay']);
            Route::post('end-workday', [EmployeeController::class, 'endWorkDay']);
            Route::post('check-is-working-now', [EmployeeController::class, 'checkIsWorkingNow']);
            // employee workay breaks
            Route::post('start-break', [EmployeeController::class, 'startBreak']);
            Route::post('end-break', [EmployeeController::class, 'endBreak']);
            Route::post('check-is-on-break-now', [EmployeeController::class, 'checkIsOnBreakNow']);
        });

        // employees management routes (only for owner + worker_supervisor)
        Route::middleware(['api.role:owner|worker_supervisor'])->group(function () {
            Route::post('list', [EmployeeController::class, 'index']);
            Route::post('create', [EmployeeController::class, 'store']);
            Route::match(['put', 'patch', 'post'], 'update/{id}', [EmployeeController::class, 'update']);
            Route::post('export-excel', [EmployeeController::class, 'exportExcel']);
        });
        Route::post('show', [EmployeeController::class, 'show']);

        // Notifications
        //Route::post('notifications-app', [App\Http\Controllers\Api\NotificationController::class, 'indexNotificationApp']);
        //Route::post('notifications-store', [App\Http\Controllers\Api\NotificationController::class, 'indexNotificationStore']);

        // Mark notifications as read
        //Route::post('mark-notification-as-read-app', [App\Http\Controllers\Api\NotificationController::class, 'markNotificationAsReadApp']);
        //Route::post('mark-notification-as-read-store', [App\Http\Controllers\Api\NotificationController::class, 'markNotificationAsReadStore']);
    });

    // stores
    Route::prefix('stores')->middleware(['api.role:owner'])->group(function () {
        Route::post('list', [StoreController::class, 'index']);
        Route::post('create', [StoreController::class, 'store']);
        Route::post('view', [StoreController::class, 'show']);
        Route::match(['put', 'patch', 'post'], 'update-store/{id}', [StoreController::class, 'update']);
        Route::post('delete-store', [StoreController::class, 'destroy']);
        Route::post('add-pdf-menu', [StoreController::class, 'addPDFMenu']);
        Route::post('delete-pdf-menu', [StoreController::class, 'removePDFMenu']);
    });

    // services
    Route::prefix('service')->group(function () {
        Route::post('/service-definitions/{service_definition_id}', [ServiceDefinitionController::class, 'show']); //always service_definition_id id = 1
    });

    Route::prefix('store-promoters')->group(function () {
        Route::get('index', [StorePromoterController::class, 'index']);
    });

    // settings
    Route::prefix('settings')->withoutMiddleware(['auth:sanctum',])->group(function () {
        Route::get('location-configs', [SettingsController::class, 'getLocationConfig']);
        Route::get('customer-location-config', [SettingsController::class, 'getCustomerLocationConfig']);
        Route::get('branch-location-config', [SettingsController::class, 'getBranchLocationConfig']);
    });

    // firebase
    Route::post('send/notification', [FirebasePushController::class, 'sendFirebaseNotification'])->name('firebase.send');
    Route::post('handle/token-refresh', [FirebasePushController::class, 'handleTokenRefresh'])->name('firebase.token.refresh');
    Route::post('get/notifications', [FirebasePushController::class, 'getFirebaseNotifications'])->name('firebase.notifications');
    Route::post('mark/notification-as-read', [FirebasePushController::class, 'markNotificationAsRead'])->name('firebase.notification.markAsRead');
    Route::post('get/unread-notifications-count', [FirebasePushController::class, 'getUnreadNotificationsCount'])->name('firebase.notification.getUnreadCount');
    Route::post('mark/all-notifications-as-read', [FirebasePushController::class, 'markAllNotificationsAsRead'])->name('firebase.notifications.markAllAsRead');
});

Route::prefix('branches')->middleware(['return_json', 'api.locale_and_timezone', 'platform.status'])->group(function () {
    Route::post('list-of-branches-in-customer-area', [BranchController::class, 'getBranchesInCustomerArea']);
    Route::post('view-branch-in-customer-area', [BranchController::class, 'showBranchInCustomerArea']);
});

// Artisan commands
Route::get('/clear-cache', function () {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    return 'Cache cleared';
});

Route::get('optimize', function () {
    Artisan::call('route:cache');
    Artisan::call('config:cache');
    Artisan::call('optimize');
    return 'Routes and configuration cached';
});

Route::get('create-storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created';
});

Route::get('migrate-fresh-seed', function () {

    if (config('app.env') == 'local') {

        Artisan::call('migrate:fresh --seed');

        return 'Database migrated and seeded';
    }

    return 'Database migrated and seeded not allowed in production';
});

Route::get('get-environment', function () {
    $environment = App::environment();
    return response()->json(['environment' => $environment]);
});

Route::get('npm-install', function () {
    $output = shell_exec('npm install');
    return "<pre>$output</pre>";
});

Route::get('npm-build', function () {
    $output = shell_exec('npm run build');
    return "<pre>$output</pre>";
});

Route::get('show-logs', function () {

    $path = storage_path('logs/laravel.log');

    if (File::exists($path)) {

        $content = File::get($path);

        return $content;

    } else {

        return 'Log file not found';
    }
});

Route::get('clear-logs', function () {

    $path = storage_path('logs/laravel.log');

    if (File::exists($path)) {

        File::put($path, ''); // clear the log file

        return 'Log file cleared successfully';

    } else {
        return 'Log file not found';
    }
});

Route::post('suppliers/create', [SupplierController::class, 'create']);
Route::post('/notifications/callback', [NotificationController::class, 'handleCallback']);
Route::post('/payment/refund', [PaymentController::class, 'paymentRefund']);
Route::post('/check-payment-status', [PaymentController::class, 'checkPaymentStatus']);
//Route::post('/upload', [FileUploadController::class, 'upload']);
