<?php

use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\AdminController as AdminAdminController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;
use App\Http\Controllers\Admin\CategoriesController as AdminCategoriesController;
use App\Http\Controllers\Admin\DealController as AdminDealController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmailTemplateController as AdminEmailTemplateController;
use App\Http\Controllers\Admin\IndexController as AdminIndexController;
use App\Http\Controllers\Admin\ListingsController as AdminListingsController;
use App\Http\Controllers\Admin\LocationController as AdminLocationController;
use App\Http\Controllers\Admin\CitiesController as AdminCitiesController;
use App\Http\Controllers\Admin\PaymentGatewayController as AdminPaymentGatewayController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\PlatformSettingsController as AdminPlatformSettingsController;
use App\Http\Controllers\Admin\ReportsController as AdminReportsController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\SubCategoriesController as AdminSubCategoriesController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\SupportController as AdminSupportController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Admin\VendorManagementController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ListingsController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PaystackController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Vendor\AnalyticsController as VendorAnalyticsController;
use App\Http\Controllers\Vendor\DealController as VendorDealController;
use App\Http\Controllers\Vendor\SubscriptionController as VendorSubscriptionController;
use App\Http\Controllers\Vendor\SupportController as VendorSupportController;
use App\Http\Controllers\Vendor\UpgradeController;
use App\Http\Controllers\Vendor\OnboardingController;
use App\Http\Controllers\Vendor\VendorTrainingController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
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

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminIndexController::class, 'index'])->name('login');
    Route::post('login', [AdminIndexController::class, 'postLogin']);
    Route::get('logout', [AdminIndexController::class, 'logout']);

    Route::get('dashboard', [AdminDashboardController::class, 'index']);

    Route::get('profile', [AdminAdminController::class, 'profile']);
    Route::post('profile', [AdminAdminController::class, 'updateProfile']);
    Route::post('profile_pass', [AdminAdminController::class, 'updatePassword']);

    Route::get('settings', [AdminSettingsController::class, 'settings']);
    Route::post('settings', [AdminSettingsController::class, 'settingsUpdates']);
    Route::post('smtp_settings', [AdminSettingsController::class, 'smtp_settings']);
    Route::post('social_login_settings', [AdminSettingsController::class, 'social_login_settings']);
    Route::post('homepage_settings', [AdminSettingsController::class, 'homepage_settings']);
    Route::post('aboutus_settings', [AdminSettingsController::class, 'aboutus_settings']);
    Route::post('contactus_settings', [AdminSettingsController::class, 'contactus_settings']);
    Route::post('terms_of_service', [AdminSettingsController::class, 'terms_of_service']);
    Route::post('privacy_policy', [AdminSettingsController::class, 'privacy_policy']);
    Route::post('addthisdisqus', [AdminSettingsController::class, 'addthisdisqus']);
    Route::post('headfootupdate', [AdminSettingsController::class, 'headfootupdate']);

    Route::get('users', [AdminUsersController::class, 'userslist']);
    Route::get('users/adduser', [AdminUsersController::class, 'addeditUser']);
    Route::post('users/adduser', [AdminUsersController::class, 'addnew']);
    Route::get('users/adduser/{id}', [AdminUsersController::class, 'editUser']);
    Route::get('users/delete/{id}', [AdminUsersController::class, 'delete']);

    Route::get('categories', [AdminCategoriesController::class, 'categories']);
    Route::get('categories/addcategory', [AdminCategoriesController::class, 'addeditCategory']);
    Route::get('categories/addcategory/{id}', [AdminCategoriesController::class, 'editCategory']);
    Route::post('categories/addcategory', [AdminCategoriesController::class, 'addnew']);
    Route::get('categories/delete/{id}', [AdminCategoriesController::class, 'delete']);

    Route::get('subcategories', [AdminSubCategoriesController::class, 'subcategories']);
    Route::get('subcategories/addsubcategory', [AdminSubCategoriesController::class, 'addeditSubCategory']);
    Route::get('subcategories/addsubcategory/{id}', [AdminSubCategoriesController::class, 'editSubCategory']);
    Route::post('subcategories/addsubcategory', [AdminSubCategoriesController::class, 'addnew']);
    Route::get('subcategories/delete/{id}', [AdminSubCategoriesController::class, 'delete']);
    Route::get('ajax_subcategories/{id}', [AdminSubCategoriesController::class, 'ajax_subcategories']);

    Route::get('locations', [AdminLocationController::class, 'locations']);
    Route::get('locations/addlocation', [AdminLocationController::class, 'addeditLocation']);
    Route::get('locations/addlocation/{id}', [AdminLocationController::class, 'editLocation']);
    Route::post('locations/addlocation', [AdminLocationController::class, 'addnew']);
    Route::get('locations/delete/{id}', [AdminLocationController::class, 'delete']);

    Route::prefix('cities')->name('admin.cities.')->group(function () {
        Route::get('/', [AdminCitiesController::class, 'index'])->name('index');
        Route::get('/create', [AdminCitiesController::class, 'create'])->name('create');
        Route::post('/', [AdminCitiesController::class, 'store'])->name('store');
        Route::get('/{city}/edit', [AdminCitiesController::class, 'edit'])->name('edit');
        Route::put('/{city}', [AdminCitiesController::class, 'update'])->name('update');
        Route::delete('/{city}', [AdminCitiesController::class, 'destroy'])->name('destroy');
        Route::post('/{city}/toggle-featured', [AdminCitiesController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::post('/{city}/toggle-status', [AdminCitiesController::class, 'toggleStatus'])->name('toggle-status');
    });

    Route::get('listings', [AdminListingsController::class, 'listings']);
    Route::get('listings/featured_listing/{id}/{status}', [AdminListingsController::class, 'featured_listing']);
    Route::get('listings/status_listing/{id}/{status}', [AdminListingsController::class, 'status_listing']);
    Route::get('listings/delete_listing/{id}', [AdminListingsController::class, 'delete']);

    Route::get('plan', [AdminPlanController::class, 'plan_list']);
    Route::get('plan/add', [AdminPlanController::class, 'add_plan']);
    Route::get('plan/edit/{id}', [AdminPlanController::class, 'edit_plan']);
    Route::post('plan/addedit', [AdminPlanController::class, 'addnew']);
    Route::get('plan/delete/{id}', [AdminPlanController::class, 'delete']);

    Route::get('transaction', [AdminTransactionController::class, 'transaction_list']);
    Route::get('income/daily', [AdminTransactionController::class, 'daily_income']);
    Route::get('income/week', [AdminTransactionController::class, 'week_income']);
    Route::get('income/month', [AdminTransactionController::class, 'month_income']);
    Route::get('income/year', [AdminTransactionController::class, 'year_income']);

    Route::get('payment_gateway', [AdminPaymentGatewayController::class, 'list']);
    Route::get('payment_gateway/edit/{id}', [AdminPaymentGatewayController::class, 'edit']);
    Route::post('payment_gateway/paypal', [AdminPaymentGatewayController::class, 'paypal']);
    Route::post('payment_gateway/stripe', [AdminPaymentGatewayController::class, 'stripe']);
    Route::post('payment_gateway/razorpay', [AdminPaymentGatewayController::class, 'razorpay']);
    Route::post('payment_gateway/paystack', [AdminPaymentGatewayController::class, 'paystack']);
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('about-us', [IndexController::class, 'about_us']);
Route::get('about', [IndexController::class, 'about_us'])->name('about');
Route::get('terms-conditions', [IndexController::class, 'termsandconditions']);
Route::get('privacy-policy', [IndexController::class, 'privacypolicy']);

Route::get('contact', [IndexController::class, 'contact_us'])->name('contact');
Route::post('contact_send', [IndexController::class, 'contact_send']);

Route::get('categories', [CategoriesController::class, 'categories_list'])->name('categories.index');
Route::get('categories/{cat_slug}/{cat_id}', [CategoriesController::class, 'sub_categories_list']);

Route::get('listings/{cat_slug}/{sub_cat_slug}/{sub_cat_id}', [ListingsController::class, 'listings_sub_categories']);

Route::get('listings', [ListingsController::class, 'listings']);
Route::get('listings/{listing_slug}/{id}', [ListingsController::class, 'single_listing'])->name('listing.show');

Route::get('user_listings/{id}', [ListingsController::class, 'user_listings']);

Route::post('submit_review', [ListingsController::class, 'submit_review']);
Route::post('inquiry_send', [ListingsController::class, 'inquiry_send']);

Route::get('login', [UserController::class, 'login']);
Route::post('login', [UserController::class, 'postLogin']);

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('auth/facebook', [FacebookController::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);

Route::get('register', [UserController::class, 'register']);
Route::post('register', [UserController::class, 'postRegister']);

Route::get('dashboard', [UserController::class, 'dashboard']);
Route::get('profile', [UserController::class, 'profile']);
Route::post('profile', [UserController::class, 'editprofile']);
Route::get('logout', [UserController::class, 'logout']);

Route::post('phone_update', [UserController::class, 'phone_update']);

Route::get('password/email', [ForgotPasswordController::class, 'forget_password']);
Route::post('password/email', [ForgotPasswordController::class, 'forget_password_submit']);
Route::get('password/reset/{token}', [ForgotPasswordController::class, 'reset_password']);
Route::post('password/reset', [ForgotPasswordController::class, 'reset_password_submit']);

Route::get('submit_listing', [ListingsController::class, 'submit_listing']);
Route::post('submit_listing', [ListingsController::class, 'addnew']);
Route::get('edit_listing/{id}', [ListingsController::class, 'editlisting']);
Route::get('delete_listing/{id}', [ListingsController::class, 'delete']);
Route::get('listing/galleryimage_delete/{id}', [ListingsController::class, 'gallery_image_delete']);
Route::get('ajax_subcategories/{id}', [ListingsController::class, 'ajax_subcategories']);

Route::post('submit_review', [ListingsController::class, 'submit_review']);
Route::post('inquiry_send', [ListingsController::class, 'inquiry_send']);

Route::get('pricing', [UserController::class, 'plan_list']);

// Module 1: Subscription Routes
Route::get('pricing', [PricingController::class, 'index'])->name('pricing');
Route::post('subscription/checkout', [SubscriptionController::class, 'checkout'])->middleware('auth')->name('subscription.checkout');
Route::get('subscription/success', [SubscriptionController::class, 'success'])->middleware('auth')->name('subscription.success');
Route::get('subscription/cancel', [SubscriptionController::class, 'cancel'])->middleware('auth')->name('subscription.cancel');
Route::get('subscription/portal', [SubscriptionController::class, 'portal'])->middleware('auth')->name('subscription.portal');

// Admin Subscription Management
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('subscriptions', [AdminSubscriptionController::class, 'index'])->name('admin.subscriptions.index');
    Route::get('subscriptions/{subscription}', [AdminSubscriptionController::class, 'show'])->name('admin.subscriptions.show');
    Route::post('subscriptions/{subscription}/cancel', [AdminSubscriptionController::class, 'cancel'])->name('admin.subscriptions.cancel');

    // Admin Deal Management
    Route::get('deals', [AdminDealController::class, 'index'])->name('admin.deals.index');
    Route::get('deals/{deal}', [AdminDealController::class, 'show'])->name('admin.deals.show');
    Route::post('deals/{deal}/approve', [AdminDealController::class, 'approve'])->name('admin.deals.approve');
    Route::post('deals/{deal}/reject', [AdminDealController::class, 'reject'])->name('admin.deals.reject');
    Route::post('deals/{deal}/pause', [AdminDealController::class, 'pause'])->name('admin.deals.pause');
    Route::post('deals/{deal}/request-changes', [AdminDealController::class, 'requestChanges'])->name('admin.deals.request-changes');
    Route::post('deals/{deal}/feature', [AdminDealController::class, 'feature'])->name('admin.deals.feature');
    Route::post('deals/{deal}/unfeature', [AdminDealController::class, 'unfeature'])->name('admin.deals.unfeature');
    Route::post('deals/bulk/{action}', [AdminDealController::class, 'bulkAction'])->name('admin.deals.bulk');
    Route::delete('deals/{deal}', [AdminDealController::class, 'destroy'])->name('admin.deals.destroy');

    Route::get('platform-settings', [AdminPlatformSettingsController::class, 'index'])->name('admin.platform-settings.index');
    Route::put('platform-settings', [AdminPlatformSettingsController::class, 'update'])->name('admin.platform-settings.update');
    Route::post('platform-settings/test-email', [AdminPlatformSettingsController::class, 'testEmail'])->name('admin.platform-settings.test-email');

    Route::get('email-templates', [AdminEmailTemplateController::class, 'index'])->name('admin.email-templates.index');
    Route::get('email-templates/{id}/edit', [AdminEmailTemplateController::class, 'edit'])->name('admin.email-templates.edit');
    Route::put('email-templates/{id}', [AdminEmailTemplateController::class, 'update'])->name('admin.email-templates.update');
    Route::get('email-templates/{id}/preview', [AdminEmailTemplateController::class, 'preview'])->name('admin.email-templates.preview');
    Route::post('email-templates/{id}/test', [AdminEmailTemplateController::class, 'test'])->name('admin.email-templates.test');
    Route::post('email-templates/{id}/reset', [AdminEmailTemplateController::class, 'resetToDefault'])->name('admin.email-templates.reset');

    Route::get('support', [AdminSupportController::class, 'index'])->name('admin.support.index');
    Route::get('support/{id}', [AdminSupportController::class, 'show'])->name('admin.support.show');
    Route::post('support/{id}/assign', [AdminSupportController::class, 'assign'])->name('admin.support.assign');
    Route::post('support/{id}/reply', [AdminSupportController::class, 'reply'])->name('admin.support.reply');
    Route::post('support/{id}/update-status', [AdminSupportController::class, 'updateStatus'])->name('admin.support.update-status');

    Route::get('activity-log', [AdminActivityLogController::class, 'index'])->name('admin.activity-log.index');

    Route::get('reports', [AdminReportsController::class, 'index'])->name('admin.reports.index');
    Route::get('reports/vendor-growth', [AdminReportsController::class, 'vendorGrowth'])->name('admin.reports.vendor-growth');
    Route::get('reports/revenue', [AdminReportsController::class, 'revenue'])->name('admin.reports.revenue');
    Route::get('reports/deal-performance', [AdminReportsController::class, 'dealPerformance'])->name('admin.reports.deal-performance');
    Route::get('reports/top-performers', [AdminReportsController::class, 'topPerformers'])->name('admin.reports.top-performers');
    Route::get('reports/export', [AdminReportsController::class, 'export'])->name('admin.reports.export');

    Route::get('analytics', [AdminAnalyticsController::class, 'index'])->name('admin.analytics.index');
    Route::get('analytics/export/csv', [AdminAnalyticsController::class, 'exportCsv'])->name('admin.analytics.export.csv');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('vendors', VendorManagementController::class);
});

Route::middleware(['auth', 'vendor'])->prefix('vendor/onboarding')->name('vendor.onboarding.')->group(function () {
    Route::get('/', [OnboardingController::class, 'index'])->name('index');
    Route::get('/stripe/connect', [OnboardingController::class, 'connectStripe'])->name('stripe.connect');
    Route::get('/stripe/callback', [OnboardingController::class, 'stripeCallback'])->name('stripe.callback');
    Route::get('/profile', [OnboardingController::class, 'showProfileForm'])->name('profile');
    Route::post('/profile', [OnboardingController::class, 'saveProfile'])->name('profile.save');
});

Route::middleware(['auth', 'vendor', 'vendor.onboarded'])->prefix('vendor/subscription')->name('vendor.subscription.')->group(function () {
    Route::get('/', [VendorSubscriptionController::class, 'index'])->name('index');
    Route::post('/upgrade', [VendorSubscriptionController::class, 'upgrade'])->name('upgrade');
    Route::post('/downgrade', [VendorSubscriptionController::class, 'downgrade'])->name('downgrade');
});

// Vendor Deal Management (Authenticated & Onboarded)
Route::middleware(['auth', 'vendor', 'vendor.onboarded'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/deals', [VendorDealController::class, 'index'])->name('deals.index');
    Route::get('/deals/create', [VendorDealController::class, 'create'])->name('deals.create');
    Route::post('/deals', [VendorDealController::class, 'store'])->name('deals.store');
    Route::get('/deals/{deal}/edit', [VendorDealController::class, 'edit'])->name('deals.edit');
    Route::put('/deals/{deal}', [VendorDealController::class, 'update'])->name('deals.update');
    Route::post('/deals/{deal}/pause', [VendorDealController::class, 'pause'])->name('deals.pause');
    Route::post('/deals/{deal}/resume', [VendorDealController::class, 'resume'])->name('deals.resume');
    Route::delete('/deals/{deal}', [VendorDealController::class, 'destroy'])->name('deals.destroy');

    // AI Insights (Pro/Enterprise only)
    Route::middleware(['subscription.feature:ai_scoring_enabled'])->group(function () {
        Route::get('/deals/{deal}/ai-insights', [VendorDealController::class, 'aiInsights'])->name('deals.ai-insights');
        Route::post('/deals/{deal}/rescore', [VendorDealController::class, 'rescore'])->name('deals.rescore');
    });

    // Analytics (Basic+)
    Route::middleware(['subscription.feature:analytics_access'])->group(function () {
        Route::get('/analytics', [VendorAnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/export/csv', [VendorAnalyticsController::class, 'exportCsv'])->name('analytics.export.csv');
        Route::get('/analytics/export/pdf', [VendorAnalyticsController::class, 'exportPdf'])->name('analytics.export.pdf');
    });

    // Support (Basic+)
    Route::middleware(['subscription.feature:analytics_access'])->group(function () {
        Route::get('/support', [VendorSupportController::class, 'index'])->name('support.index');
        Route::get('/support/create', [VendorSupportController::class, 'create'])->name('support.create');
        Route::post('/support', [VendorSupportController::class, 'store'])->name('support.store');
        Route::get('/support/{id}', [VendorSupportController::class, 'show'])->name('support.show');
        Route::post('/support/{id}/reply', [VendorSupportController::class, 'reply'])->name('support.reply');
    });

    // Vendor Training
    Route::get('/training', [VendorTrainingController::class, 'index'])->name('training.index');
    Route::get('/training/course/{courseNumber}', [VendorTrainingController::class, 'show'])->name('training.show');
    Route::post('/training/course/{courseNumber}/complete', [VendorTrainingController::class, 'complete'])->name('training.complete');

    // Vendor Upgrade & Stats
    Route::get('/upgrade', [UpgradeController::class, 'index'])->name('upgrade.index');
    Route::post('/upgrade', [UpgradeController::class, 'upgrade'])->name('upgrade.process');
    Route::post('/upgrade/dismiss/{suggestion}', [UpgradeController::class, 'dismissSuggestion'])->name('upgrade.dismiss');
    Route::get('/stats/monthly', function () {
        $commissionService = app(\App\Services\CommissionService::class);
        $stats = $commissionService->getMonthlyStats(Auth::id());

        return response()->json($stats);
    })->name('stats.monthly');
});

// Public Deal Routes - Module 3
Route::get('/deals', [DealController::class, 'index'])->name('deals.index');
Route::get('/deals/{slug}', [DealController::class, 'show'])->name('deals.show');
Route::post('/deals/{slug}/track-click', [DealController::class, 'trackClick'])->name('deals.track-click');
Route::get('/deals/{slug}/claim-purchase', [DealController::class, 'claimPurchase'])->name('deals.claim-purchase');
Route::post('/deals/{slug}/claim-purchase', [DealController::class, 'processClaim'])
    ->middleware('vendor.capacity')
    ->name('deals.process-claim');

// Search Routes
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');

// Category Routes
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Voucher Routes
Route::get('/voucher/{confirmationCode}', [VoucherController::class, 'show'])->name('vouchers.show');
Route::get('/voucher/{confirmationCode}/pdf', [VoucherController::class, 'downloadPdf'])->name('vouchers.pdf');
Route::post('/voucher/{confirmationCode}/email', [VoucherController::class, 'emailVoucher'])->name('vouchers.email');

// Vendor Profile Routes
Route::get('/vendor/{id}', [VendorController::class, 'show'])->name('vendor.show');

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('payment_method/{plan_id}', [UserController::class, 'payment_method']);

Route::post('paypal/pay', [PaypalController::class, 'paypal_pay']);
Route::get('paypal/success', [PaypalController::class, 'paypal_success']);
Route::get('paypal/fail', [PaypalController::class, 'paypal_fail']);

Route::get('stripe/pay', [StripeController::class, 'stripe_pay']);
Route::get('stripe/success', [StripeController::class, 'stripe_success']);
Route::get('stripe/fail', [StripeController::class, 'stripe_fail']);

Route::post('razorpay_get_order_id', [RazorpayController::class, 'get_order_id']);
Route::post('razorpay-success', [RazorpayController::class, 'payment_success']);

Route::post('pay', [PaystackController::class, 'redirectToGateway'])->name('pay');
Route::get('payment/callback', [PaystackController::class, 'handleGatewayCallback']);

// Business listing (CTA target)
Route::get('/business/list', [BusinessController::class, 'create'])->name('business.create');

// Clear Cache
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');

    return '<h1>Cache facade value cleared</h1>';
});

// Clear View cache
Route::get('/clear-view', function () {
    Artisan::call('view:clear');

    return '<h1>View cache cleared</h1>';
});

// TEMP: Monthly reset test route (remove after testing)
Route::get('/test-reset', function () {
    Artisan::call('vendors:reset-monthly-counters');
    return 'Reset command executed. Check logs.';
});
