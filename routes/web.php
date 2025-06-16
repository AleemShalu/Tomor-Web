<?php
include('validation.php');

use App\Http\Controllers\AccountDeletionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Web\Admin\AdminController;
use App\Http\Controllers\Web\Admin\AdminDashboardController;
use App\Http\Controllers\Web\Admin\Auth\AuthController;
use App\Http\Controllers\Web\Admin\FeedbackAndComplaint\FeedbackAndComplaintController;
use App\Http\Controllers\Web\Admin\Financial\FinancialController;
use App\Http\Controllers\Web\Admin\Settings\AccountSettingsController;
use App\Http\Controllers\Web\Admin\Settings\Platform\WebPlatformSettingsController;
use App\Http\Controllers\Web\Admin\Settings\PlatformSettingsController;
use App\Http\Controllers\Web\Admin\Settings\ServiceDefinitionController;
use App\Http\Controllers\Web\Admin\Settings\StoreSettingsController;
use App\Http\Controllers\Web\Admin\StorePromoterController;
use App\Http\Controllers\Web\Admin\User\SpecialNeeds\SpecialNeedsController;
use App\Http\Controllers\Web\Admin\User\UsherController;
use App\Http\Controllers\Web\IndexController;
use App\Http\Controllers\Web\LanguageController;
use App\Http\Controllers\Web\Owner\Auth\GoogleController;
use App\Http\Controllers\Web\Owner\DashboardController;
use App\Http\Controllers\Web\Owner\DataFeedController;
use App\Http\Controllers\Web\Owner\ExportController;
use App\Http\Controllers\Web\Owner\Notitfaction\NotificationController;
use App\Http\Controllers\Web\Owner\Rating\RatingController;
use App\Http\Controllers\Web\Owner\Report\ReportController;
use App\Http\Controllers\Web\Owner\Store\Bank\BankAccountController;
use App\Http\Controllers\Web\Owner\Store\Branch\BranchManageController;
use App\Http\Controllers\Web\Owner\Store\Branch\StoreBranchController;
use App\Http\Controllers\Web\Owner\Store\Employee\BranchEmployeesController;
use App\Http\Controllers\Web\Owner\Store\Order\OrderController;
use App\Http\Controllers\Web\Owner\Store\Product\OfferProductController;
use App\Http\Controllers\Web\Owner\Store\Product\ProductController;
use App\Http\Controllers\Web\Owner\Store\StoreController;
use App\Http\Controllers\Web\Owner\Store\StoreManageController;
use App\Http\Controllers\Web\Owner\System\PasswordSetController;
use App\Http\Controllers\Web\Owner\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [IndexController::class, 'landingPage'])->name('landing-page');
Route::post('/set-language', [LanguageController::class, 'setLanguage'])->name('setLanguage');
Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])->name('json_data_feed');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Route for the getting the data feed
    Route::fallback(function () {
        return view('owner/utility/404');
    });
});

// Google authentication routes
Route::get('auth/google', [GoogleController::class, 'signInwithGoogle']);
Route::get('callback/google', [GoogleController::class, 'callbackToGoogle']);


// Google Delete Account routes
Route::get('/request-delete', [AccountDeletionController::class, 'showRequestForm'])->name('showRequestForm');
Route::post('/request-delete', [AccountDeletionController::class, 'requestDelete'])->name('requestDelete');
Route::get('/delete-account/{token}', [AccountDeletionController::class, 'deleteAccount'])->name('deleteAccount');;

//for testing
Route::get('/status', [UserController::class, 'show']);
Route::get('/export-employees', [UserController::class, 'exportEmployees'])->name('export.employees');
Route::get('/export/{storeId}/{userId}', [ExportController::class, 'exportEmployees'])->name('export.employee');
Route::get('/export/store-details/pdf/{storeId}', [ExportController::class, 'exportStoreDetailsPdf'])->name('export.store-details-pdf');


//terms and conditions
Route::get('/terms-of-store', function () {
    return view('store_terms');
})->name('store_terms');

// Login routes Owner
Route::post('/login', [\App\Http\Controllers\Web\Owner\Auth\AuthController::class, 'login'])->name('login.owner.submit');

// Add the following group to wrap all routes with the 'password.set' middleware
Route::middleware(['auth', 'owner.verified', 'platform.status', 'user.status'])->group(function () {

    Route::group(['middleware' => 'role:owner', '2fa'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::post('/password/update/new-user', [PasswordSetController::class, 'update'])->name('password.update.new-user');

        Route::middleware(['password.set'])->group(function () {
            Route::get('/password/set', [PasswordSetController::class, 'index'])->name('password.set');

            // Store routes
            Route::get('/store', [StoreController::class, 'index'])->name('store');
            Route::get('/store/create', [StoreController::class, 'create'])->name('store.create');
            Route::post('/store/store', [StoreController::class, 'store'])->name('store.store');

            //Exports
            Route::post('/store/export-excel', [StoreController::class, 'exportExcel'])->name('store.export-excel');
            Route::post('/store/export-pdf', [StoreController::class, 'exportPDF'])->name('store.export-pdf');

            //Store Update
            Route::put('/store/update-commercial-name', [StoreController::class, 'updateCommercialName'])->name('store.update-commercial-name');
            Route::put('/store/update-contact-information', [StoreController::class, 'updateContactInformation'])->name('store.update-contact-information');
            Route::put('/store/update-additional-information', [StoreController::class, 'updateAdditionalInformation'])->name('store.update-additional-information');
            Route::put('/store/update-range-time-order', [StoreController::class, 'updateRangeTimeOrder'])->name('store.update-range-time-order');
            Route::put('/store/update-website-logo', [StoreController::class, 'updateWebsiteAndLogo'])->name('store.update-website-logo');


            // Store Manage routes
            Route::get('/store/{id}/manage', [StoreManageController::class, 'indexStore'])->name('store.manage');
            Route::get('/branch/{id}/manage', [StoreManageController::class, 'indexBranch'])->name('branch.manage');
            Route::get('/employee/{id}/manage', [StoreManageController::class, 'indexEmployees'])->name('employee.manage');
            Route::get('/product/{id}/manage', [StoreManageController::class, 'indexProducts'])->name('product.manage');
            Route::post('/store/upload-menu', [StoreController::class, 'uploadMenu'])->name('store.upload-menu');
            Route::delete('/store/destroy-menu', [StoreController::class, 'destroyMenu'])->name('store.destroy-menu');
            Route::put('/store/update-menu', [StoreController::class, 'updateMenu'])->name('store.update-menu');
            Route::get('/settings/{id}/manage', [StoreManageController::class, 'indexSettings'])->name('settings.manage');

            // Store branch routes
            Route::get('/branch/{branch}', [BranchManageController::class, 'indexBranch'])->name('branch');
            Route::get('/branch/create/{storeId}', [StoreBranchController::class, 'create'])->name('branch.create'); // error !
            Route::post('/branch/store', [StoreBranchController::class, 'store'])->name('branch.store');

            // Branch Manage routes
            Route::get('/branch-orders/{branchId}', [BranchManageController::class, 'indexOrders'])->name('branch.orders');
            Route::get('/branch-orders-live/{branchId}', [BranchManageController::class, 'indexOrdersLive'])->name('branch.orders.live');
            Route::get('/branch-get-orders-live/{branchId}', [BranchManageController::class, 'getOrdersLive'])->name('branch.get-orders.live');
            Route::get('/preview-order/{branchId}/{orderId}', [BranchManageController::class, 'previewOrder'])->name('preview-order');
            Route::get('/generate-report-order/{orderId}', [\App\Http\Controllers\Web\Owner\Store\Order\OrderController::class, 'generateReport'])->name('generate-report-order');
            Route::get('/orders/export/pdf/{branchId}', [OrderController::class, 'exportPDF'])->name('orders.export.pdf');
            Route::get('/orders/export/excel/{branchId}', [OrderController::class, 'exportExcel'])->name('orders.export.excel');
            Route::get('/orders/export/csv/{branchId}', [OrderController::class, 'exportCSV'])->name('orders.export.csv');

            //invoice routes
//            Route::get('/preview-invoice/{invoiceId}', [InvoiceController::class, 'previewInvoice'])->name('preview-invoice');
//            Route::get('/invoices/export/pdf/{branchId}', [InvoiceController::class, 'exportPDF'])->name('invoices.export.pdf');
//            Route::get('/invoices/export/excel/{branchId}', [InvoiceController::class, 'exportExcel'])->name('invoices.export.excel');
//            Route::get('/invoices/export/csv/{branchId}', [InvoiceController::class, 'exportCSV'])->name('invoices.export.csv');
//            Route::get('/invoices/{invoiceId}/pdf', [InvoiceController::class, 'printInvoicePDF'])->name('invoice.pdf');

            Route::get('/branch-employees/{branchId}', [BranchManageController::class, 'indexEmployees'])->name('branch.employees');
            Route::get('/branch-settings/{branchId}', [BranchManageController::class, 'indexSettings'])->name('branch.settings');
            Route::put('/work-status/update', [BranchManageController::class, 'updateWorkStatus'])->name('updateWorkStatus');
            Route::put('/branch/update/contact', [BranchManageController::class, 'updateBranchContact'])->name('updateBranchContact');
            Route::put('/branch/update/commercial', [BranchManageController::class, 'updateBranchCommercial'])->name('updateBranchCommercial');
            Route::put('/branch/update/location', [BranchManageController::class, 'updateBranchLocation'])->name('updateBranchLocation');
            Route::put('/branch/update/bank', [BranchManageController::class, 'updateBranchBank'])->name('updateBranchBank');


            // Employee routes
            Route::get('/employee', [BranchEmployeesController::class, 'index'])->name('employee');
            Route::get('/employee/create/{id}', [BranchEmployeesController::class, 'create'])->name('employee.create');
            Route::post('/employee/store', [BranchEmployeesController::class, 'store'])->name('employee.store'); //update using role
            Route::get('/store/{storeId}/employee/edit/{employeeId}', [BranchEmployeesController::class, 'edit'])->name('employee.edit');
            Route::get('/store/{storeId}/employee/preview/{employeeId}', [BranchEmployeesController::class, 'previewEmployee'])->name('employee.preview');
            Route::put('/employee/update', [BranchEmployeesController::class, 'update'])->name('employee.update');
            Route::delete('/employee/delete/{id}', [BranchEmployeesController::class, 'destroy'])->name('employee.delete');

            // Bank routes
            Route::get('/bank/{storeId}/create', [BankAccountController::class, 'create'])->name('bank.create');
            Route::post('/bank/store', [BankAccountController::class, 'store'])->name('bank.store');
            Route::get('/bank/{store}/accounts/{account}', [BankAccountController::class, 'show'])->name('bank.accounts.show');
            Route::get('/bank/print-pdf/{store}/{account}', [BankAccountController::class, 'printPDF'])->name('bank.print-pdf');

            //Product
//            Route::get('/product', [ProductController::class, 'index'])->name('product');
            Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
            Route::get('/product/create/{id}', [ProductController::class, 'create'])->name('product.create');
            Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
            Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
            Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
            Route::delete('/product/delete', [ProductController::class, 'delete'])->name('product.delete');
//            Route::post('/product/destroy/{store_id}/all', [ProductController::class, 'destroyAll'])->name('product.destroy-all');
            Route::post('/product/import-json', [ProductController::class, 'importJson'])->name('product.import.json');
            Route::get('/products/{storeId}', [ProductController::class, 'indexProducts'])->name('products');
            Route::delete('/products/images/{id}', [ProductController::class, 'destroyImage']);
            Route::post('/products/fetch', [ProductController::class, 'fetchProducts'])->name('products.fetch');
            Route::post('/products/delete-selected', [ProductController::class, 'deleteProductsSelected'])->name('products.deleteSelected');

            //offer product
            Route::prefix('/product/offer/{storeId}')->group(function () {
                Route::get('/', [OfferProductController::class, 'index'])->name('offer.product');
                Route::post('/', [OfferProductController::class, 'store'])->name('offer.store');
                Route::get('/create', [OfferProductController::class, 'createOffer'])->name('offer.create');
                Route::post('/store', [OfferProductController::class, 'storeOffer'])->name('offer.storeOffer');
                Route::get('/get-products-for-offer/{offerId}', [OfferProductController::class, 'getProductsForOffer'])->name('offer.getProductsForOffer');
                Route::get('/{offerId}/edit', [OfferProductController::class, 'editOffer'])->name('offer.edit');
                Route::put('/{offerId}', [OfferProductController::class, 'updateOffer'])->name('offer.update');
                Route::delete('/{offerId}', [OfferProductController::class, 'destroy'])->name('offer.destroy');
            });

            //rating
            Route::get('/rating', [RatingController::class, 'indexRating'])->name('rating.index');
            Route::get('/feedback', [RatingController::class, 'indexFeedback'])->name('feedback.index');

        });
    });
});

//notification
Route::post('/notifications/send-to-user', [NotificationController::class, 'sendToUser'])->name('notifications.send-to-user');
Route::post('/notifications/send-to-role/{roleName}', [NotificationController::class, 'sendToRole'])->name('notifications.send-to-role');
Route::put('/notifications/{notificationId}/mark-as-read', [NotificationController::class, 'markNotificationAsRead'])->name('notifications.mark-as-read');
Route::get('/notifications', [NotificationController::class, 'getUserNotifications'])->name('notifications.user-notifications');


// Admin Part
Route::prefix('admin')->group(function () {

    Route::get('', [AuthController::class, 'loginView']);

    // Login routes
    Route::get('/login', [AuthController::class, 'loginView'])->name('login.admin');
    Route::post('/login', [AuthController::class, 'login'])->name('login.admin.submit');
    Route::post('/logout/admin', [AuthController::class, 'logoutAdmin'])->name('logout.admin');

    // Admin-only routes
    Route::group(['middleware' => ['auth', 'role:admin']], function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/dashboard/user-growth', [AdminDashboardController::class, 'getUserGrowth']);
        Route::get('/dashboard/store-growth', [AdminDashboardController::class, 'getStoreGrowth']);
        Route::get('/dashboard/service-cost-distribution', [AdminDashboardController::class, 'getServiceCostDistribution']);
        Route::get('/dashboard/platform-rating-distribution', [AdminDashboardController::class, 'getPlatformRatingDistribution']);
        Route::get('/dashboard/user-statistics', [AdminDashboardController::class, 'getUserStatistics']);

        // User management
        Route::get('/users', [\App\Http\Controllers\Web\Admin\User\UserController::class, 'users'])->name('admin.users');
        Route::get('/user/{id}/view', [\App\Http\Controllers\Web\Admin\User\UserController::class, 'view'])->name('admin.user.view');
        Route::get('/user/register', [\App\Http\Controllers\Web\Admin\User\UserController::class, 'register'])->name('admin.users.register');
        Route::post('user/register/store', [\App\Http\Controllers\Web\Admin\User\UserController::class, 'store'])->name('admin.users.register.store'); //update using role
        Route::get('/user/{id}/edit', [\App\Http\Controllers\Web\Admin\User\UserController::class, 'edit'])->name('admin.users.edit');
        Route::post('/user/{id}', [\App\Http\Controllers\Web\Admin\User\UserController::class, 'update'])->name('admin.users.update');
        Route::post('/user/delete/{id}', [\App\Http\Controllers\Web\Admin\User\UserController::class, 'delete'])->name('admin.users.delete');
        Route::get('/user/{id}/update_status/{status}', [\App\Http\Controllers\Web\Admin\User\UserController::class, 'updateUserStatus'])->name('admin.users.update_status');

        //Special Needs
        Route::get('/user/special-needs', [SpecialNeedsController::class, 'usersSpecialNeeds'])->name('admin.users.special-needs');
        Route::get('/user/special-needs/{id}', [SpecialNeedsController::class, 'view'])->name('admin.users.special-needs.view');
        Route::get('/user/special-needs/{id}/edit', [SpecialNeedsController::class, 'edit'])->name('admin.users.special-needs.edit');
        Route::post('/user/special-needs/update', [SpecialNeedsController::class, 'update'])->name('admin.users.special-needs.update');

        //ajax
        Route::get('/users/special-needs/ajax', [SpecialNeedsController::class, 'usersSpecialNeedsAjax'])->name('admin.users.special-needs.ajax');

        //Usher
        Route::get('/user/ushers', [UsherController::class, 'index'])->name('admin.usher');
        Route::get('/user/ushers/create', [UsherController::class, 'create'])->name('admin.usher.create');
        Route::post('/user/ushers/store', [UsherController::class, 'store'])->name('admin.usher.store');
        Route::get('/user/ushers/{usher}', [UsherController::class, 'show'])->name('admin.usher.show');
        Route::get('/user/ushers/{usher}/edit', [UsherController::class, 'edit'])->name('admin.usher.edit');
        Route::post('/user/ushers/update', [UsherController::class, 'update'])->name('admin.usher.update');
        //Route::get('/user/ushers/{id}/delete', [UsherController::class, 'destroy'])->name('admin.usher.delete');


        // Store Management
        Route::get('/stores', [\App\Http\Controllers\Web\Admin\Store\StoreController::class, 'index'])->name('admin.store.index');
        Route::get('/store/{id}/show', [\App\Http\Controllers\Web\Admin\Store\StoreController::class, 'show'])->name('admin.store.show');
        Route::get('/store/create', [\App\Http\Controllers\Web\Admin\Store\StoreController::class, 'create'])->name('admin.store.create');
        Route::post('/store/store', [\App\Http\Controllers\Web\Admin\Store\StoreController::class, 'store'])->name('admin.store.store');
        Route::get('/store/{id}/edit', [\App\Http\Controllers\Web\Admin\Store\StoreController::class, 'edit'])->name('admin.store.edit');
        Route::post('/store/update', [\App\Http\Controllers\Web\Admin\Store\StoreController::class, 'update'])->name('admin.store.update');
        Route::post('/store/update-commercial-name', [\App\Http\Controllers\Web\Admin\Store\StoreController::class, 'updateCommercialName'])->name('admin.store.update-commercial-name');
        Route::put('/store/update-contact-information', [\App\Http\Controllers\Web\Admin\Store\StoreController::class, 'updateContactInformation'])->name('admin.store.update-contact-information');
        Route::put('/store/update-additional-information', [\App\Http\Controllers\Web\Admin\Store\StoreController::class, 'updateAdditionalInformation'])->name('admin.store.update-additional-information');

        //Promoter
        Route::prefix('promoters')->group(function () {
            Route::get('index', [StorePromoterController::class, 'index'])->name('admin.promoters.index');
            Route::get('create', [StorePromoterController::class, 'create'])->name('admin.promoters.create');
            Route::post('store', [StorePromoterController::class, 'store'])->name('admin.promoters.store');
            Route::put('update/{id}', [StorePromoterController::class, 'update'])->name('admin.promoters.update');

            Route::get('{id}/show', [StorePromoterController::class, 'show'])->name('admin.promoters.show');

            Route::get('{id}/edit', [StorePromoterController::class, 'edit'])->name('admin.promoters.edit');
            Route::get('{id}/activate', [StorePromoterController::class, 'activate'])->name('admin.promoters.activate');
            Route::get('{id}/deactivate', [StorePromoterController::class, 'deactivate'])->name('admin.promoters.deactivate');
        });


        // Store Settings (Active)
        Route::post('/store/update_status/{id}', [\App\Http\Controllers\Web\Admin\Store\StoreController::class, 'updateStoreStatus'])->name('admin.store.update_status');

        //Branch
        Route::get('/store/{storeId}/branch/{branchId}/show', [\App\Http\Controllers\Web\Admin\Store\Branch\BranchController::class, 'show'])->name('admin.branch.show');

        // Order Management
        Route::prefix('order')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\Admin\Order\OrderController::class, 'index'])->name('admin.order.index'); //done
            Route::get('/{id}', [\App\Http\Controllers\Web\Admin\Order\OrderController::class, 'show'])->name('admin.order.show'); //done
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\Admin\Order\OrderController::class, 'edit'])->name('admin.order.edit'); //done
            Route::put('/{id}/update', [\App\Http\Controllers\Web\Admin\Order\OrderController::class, 'update'])->name('admin.order.update'); //done
        });


        //ajax
//        Route::get('/orders/ajax', [\App\Http\Controllers\Web\Admin\Order\OrderController::class, 'ordersAjax'])->name('admin.order.ajax');  //letar

        //Report
        Route::prefix('order/reports')->group(function () {
            Route::get('/excel', [\App\Http\Controllers\Web\Admin\Order\OrderController::class, 'orderReportsExcel'])->name('admin.order.reports.excel');
            Route::get('/csv', [\App\Http\Controllers\Web\Admin\Order\OrderController::class, 'orderReportsCsv'])->name('admin.order.reports.csv');
            Route::get('/pdf', [\App\Http\Controllers\Web\Admin\Order\OrderController::class, 'orderReportsPdf'])->name('admin.order.reports.pdf');
        });


        // Products Store
        Route::get('/product/{id}', [\App\Http\Controllers\Web\Admin\Store\Product\ProductController::class, 'index'])->name('admin.product.index');

        //Financial Management
        Route::prefix('financial')->group(function () {
            Route::get('index', [FinancialController::class, 'index'])->name('admin.financial.index');
            Route::get('invoices', [FinancialController::class, 'indexInvoices'])->name('admin.financial.invoices.index');
            Route::get('index-store/{id}', [FinancialController::class, 'indexStoreInvoices'])->name('admin.financial.invoices.store.index');
            Route::get('store-analysis', [FinancialController::class, 'indexStoreAnalysis'])->name('admin.financial.store-analysis.index');
            Route::get('store-analysis/fetch', [FinancialController::class, 'fetchStoreAnalysis'])->name('admin.financial.store-analysis.fetch');
            Route::get('invoices/{id}/show', [FinancialController::class, 'showInvoice'])->name('admin.financial.invoices.show');
            Route::get('invoices/fetch', [FinancialController::class, 'fetchInvoices'])->name('admin.financial.invoices.fetch');
            Route::get('invoices/export-excel', [FinancialController::class, 'exportInvoices'])->name('admin.financial.invoices.export-excel');
            Route::get('invoices/store/{id}/export-excel', [FinancialController::class, 'exportStoreInvoices'])->name('admin.financial.store.invoices.export-excel');

        });

        Route::prefix('notifications')->group(function () {
            // Notifications Management
            Route::get('/', [\App\Http\Controllers\Web\Admin\Notification\NotificationController::class, 'index'])->name('admin.notifications.index');
            Route::get('/create', [\App\Http\Controllers\Web\Admin\Notification\NotificationController::class, 'create'])->name('admin.notifications.create');
            Route::post('/send-notification', [\App\Http\Controllers\Web\Admin\Notification\NotificationController::class, 'sendNotificationToUsers'])->name('send-notification-to-users');
            Route::get('/dhamen', [NotificationController::class, 'showDhamenNotifications'])->name('admin.notifications.dhamen.index');

        });

        // Feedback And Complaint Management
        Route::get('/feedback-and-complaint', [FeedbackAndComplaintController::class, 'index'])->name('admin.feedback-and-complaint.index'); //done
        Route::get('/feedback-and-complaint/settings', [FeedbackAndComplaintController::class, 'settings'])->name('admin.feedback-and-complaint.settings'); //done


        //feedback based on orders only
        Route::prefix('feedback')->group(function () {
            // Feedback Management
            Route::get('/users', [FeedbackAndComplaintController::class, 'indexUsersFeedback'])->name('admin.feedback.users.index'); //done
            Route::get('/', [FeedbackAndComplaintController::class, 'indexOrdersFeedback'])->name('admin.feedback.index'); //done
            Route::get('/{id}', [FeedbackAndComplaintController::class, 'showFeedback'])->name('admin.feedback.show');
            Route::get('/{id}/edit', [FeedbackAndComplaintController::class, 'editFeedback'])->name('admin.feedback.edit');
            Route::put('/{id}', [FeedbackAndComplaintController::class, 'updateFeedback'])->name('admin.feedback.update');
            Route::delete('/{id}', [FeedbackAndComplaintController::class, 'destroyFeedback'])->name('admin.feedback.destroy');
        });


        Route::prefix('complaint')->group(function () {
            // Complaint Management
            Route::get('/', [FeedbackAndComplaintController::class, 'indexComplaint'])->name('admin.complaint.index'); //done
            Route::get('/{id}', [FeedbackAndComplaintController::class, 'showComplaint'])->name('admin.complaint.show');
            Route::get('/{id}/edit', [FeedbackAndComplaintController::class, 'editComplaint'])->name('admin.complaint.edit');
            Route::put('/{id}', [FeedbackAndComplaintController::class, 'updateComplaint'])->name('admin.complaint.update');
            Route::delete('/{id}', [FeedbackAndComplaintController::class, 'destroyComplaint'])->name('admin.complaint.destroy');
        });

        // Settings

        // Account Settings
        Route::get('/settings/account', [AccountSettingsController::class, 'index'])->name('admin.settings.account');
        Route::post('/settings/account/update', [AccountSettingsController::class, 'update'])->name('admin.settings.account.update');
        Route::post('/settings/account/update-password', [AccountSettingsController::class, 'updatePassword'])->name('admin.settings.account.update-password');

        Route::post('/update-security-settings', [AccountSettingsController::class, 'updateSecuritySettings'])->name('update-security-settings');

        // Platform Settings
        Route::get('/settings/platform', [PlatformSettingsController::class, 'index'])->name('admin.settings.platform');
        Route::post('/settings/platform/update', [PlatformSettingsController::class, 'changeStatusPlatform'])->name('admin.settings.platform.update');

        // Store Settings
        Route::get('/settings/store', [StoreSettingsController::class, 'index'])->name('admin.settings.store');
        Route::post('/settings/update-terms', [StoreSettingsController::class, 'update'])->name('admin.settings.update-terms');
        Route::post('/settings/update-store-range', [StoreSettingsController::class, 'update_branch_range'])->name('admin.settings.update-store-range');
        Route::post('/settings/update-customer-range', [StoreSettingsController::class, 'update_customer_range'])->name('admin.settings.update-customer-range');
        Route::post('/settings/update-service-definition', [ServiceDefinitionController::class, 'update'])->name('admin-service-definition.update');
        Route::post('/settings/update-payment-time', [StoreSettingsController::class, 'update_payment_time'])->name('admin.settings.update-payment-time');

        // Web Platform Settings
        Route::prefix('settings/platform/web')->group(function () {
            // General Web Platform Settings
            Route::get('/general', [WebPlatformSettingsController::class, 'general'])->name('admin.settings.platform.web.general');
            // User Management
            Route::get('/users', [WebPlatformSettingsController::class, 'users'])->name('admin.settings.platform.web.users');
            // Content Management
            Route::get('/content', [WebPlatformSettingsController::class, 'content'])->name('admin.settings.platform.web.content');
            // Update Terms & Conditions
            Route::post('/update-terms', [WebPlatformSettingsController::class, 'updateTermsConditions'])->name('admin.settings.platform.web.update-terms');
            // Update Privacy Policies
            Route::post('/update-privacy', [WebPlatformSettingsController::class, 'updatePrivacyPolicies'])->name('admin.settings.platform.web.update-privacy');
        });

        //ajax
        Route::get('/stores/fetch', [\App\Http\Controllers\Web\Admin\Store\StoreController::class, 'stores_ajax'])->name('admin.store.fetch');
        Route::get('/stores/search/owners', [\App\Http\Controllers\Web\Admin\Store\StoreController::class, 'search_owners_ajax'])->name('admin.store.search.owner');

    });
});

//for guest
Route::get('/support/inquiries', [ReportController::class, 'indexInquire'])->name('support.inquiries');
Route::post('/support/inquiries/search', [ReportController::class, 'searchComplaint'])->name('support.inquiries.search');

Route::get('/support/compliant', [ReportController::class, 'createComplaint'])->name('support.compliant');
Route::post('/support/compliant', [ReportController::class, 'storeComplaint'])->name('support.complaint');
Route::get('/support/compliant/{uuid}/show', [ReportController::class, 'showComplaint'])->name('support.compliant.show');

Route::get('/support/feedback', [ReportController::class, 'createFeedback'])->name('support.feedback');
Route::post('/support/feedback', [ReportController::class, 'storeFeedback'])->name('support.feedback.store');

Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');

Route::fallback(function () {
    return view('owner/utility/404');
});

// Catch-all route for 404 errors
Route::fallback(function () {
    return view('errors.404');
});


// Redirecting 'terms-of-service' to 'privacy-policy'
Route::get('terms-of-service', function () {
    return view('errors.404');
})->name('terms.show');