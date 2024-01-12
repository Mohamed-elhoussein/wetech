<?php

use App\Events\BuyerRequestAccepted;
use App\Events\BuyerRequestUpdates;
use App\Events\MessageEvent;
use App\Http\Controllers\api\SubscribeApiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CouponsController;
use App\Http\Controllers\DashbordController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TargetsController;
use App\Http\Controllers\FrontController;

use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ProviderServicesController;
use App\Http\Controllers\QuickOffersController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ServiceCategoriesController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceSub2Controller;
use App\Http\Controllers\ServiceSub3Controller;
use App\Http\Controllers\ServiceSub4Controller;
use App\Http\Controllers\ServiceSubcategoriesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\StreetController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserChatReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\BackupsController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\MaintenanceOrderController;
use App\Http\Controllers\MaintenanceRequestTypeController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\OfferSettingController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\TranslateController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserPaymentsController;
use App\Http\Controllers\CartController;
use App\Models\AdminNotification;
use App\Models\BuyerRequest;
use App\Models\CanceledBuyerRequest;
use App\Models\ProviderServices;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Symfony\Component\DomCrawler\Crawler;

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

Route::get('/file/{filepath}', [FileController::class, 'download']);



// create user
Route::get('/db/backup', function () {


    return  App\Helpers\Backup\Backup::db_backup();


    //  return 'dlmdlkdkldkldd';
});






// create user
Route::get('/create', function () {
    return User::where('email', 'email@email')->update([

        'email' => 'email@email',
        'username' => 'admin',
        'number_phone' => '00000000',
        'role' => 'admin',
        'password' => Hash::make('admin'),
    ]);
});

Route::get('/error-500', [DashbordController::class, 'error500']);


/** login */

// Route::view('/login', 'auth.login1')->name('login')->middleware('guest');
// Route::post('/login', [LoginController::class, 'login']);

Auth::routes();

// Route::view('/login1', 'auth.login1');

Route::prefix('user/payment')->group(function () {

    Route::match(['get', 'post'], '/paypal', [UserPaymentsController::class, 'payWithpaypal']);
    Route::match(['get', 'post'], '/myfatoorah', [UserPaymentsController::class, 'payMyfatoraah']);
    Route::match(['get', 'post'], '/paypal/status', [UserPaymentsController::class, 'status'])->name('user.payement.status');
    Route::match(['get', 'post'], '/paypal/maintenance-request/status', [UserPaymentsController::class, 'maintenanceRequestPaymentStatus'])->name('user.maintenance-request.payement.status');
    Route::get('/myfatoorah/success', [UserPaymentsController::class, 'myfatoorahSuccess'])->name('user.myfatoorah.success');
});

Route::prefix('payment')->group(function () {

    Route::match(['get', 'post'], '/paypal', [PaymentController::class, 'payWithpaypal']);
    Route::match(['get', 'post'], '/myfatoorah', [PaymentController::class, 'payMyfatoraah']);
    Route::match(['get', 'post'], '/paypal/status', [PaymentController::class, 'status'])->name('payment.status');
    Route::get('/paypal/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/paypal/error',  [PaymentController::class, 'paymentError'])->name('payment.error');
    Route::get('/myfatoorah/success', [PaymentController::class, 'myfatoorahSuccess'])->name('myfatoorah.success');
    Route::get('/myfatoorah/error', [PaymentController::class, 'myfatoorahError'])->name('myfatoorah.error');
    Route::get('/{user_id}/process', [CartController::class, 'payment_order'])->name('my-fatoorah.payment');
    Route::get('/error', [CartController::class, 'fatoorah_error'])->name('my-fatoorah.error');
    Route::get('/success', [CartController::class, 'fatoorah_success'])->name('my-fatoorah.success');
});
Route::get('/subscribe/myfatoorah/success', [SubscribeApiController::class, 'subscribeSuccess'])->name('subscribe.success');
Route::get('/subscribe/myfatoorah/error', [SubscribeApiController::class, 'subscribeError'])->name('subscribe.error');
Route::get('subscribe/payment/status/', [SubscribeApiController::class, 'status'])->name('subscribe.status');

/* group auth */

Route::middleware('auth')->group(function () {

    // Product categories
    Route::resource('product-categories', ProductCategoryController::class);

    // Product types
    Route::resource('product-types', ProductTypeController::class);

    // Identities
    Route::get('/identities', [IdentityController::class, 'index'])->name('identities.index');
    Route::post('/identities/{identity}/accept', [IdentityController::class, 'approve'])->name('identities.accept');
    Route::post('/identities/{identity}/deny', [IdentityController::class, 'delete'])->name('identities.deny');

    // Skills
    Route::resource('skills', SkillController::class)->except(['show']);


    Route::view('/notify', 'notify_users')->middleware('can:access_to_app_notificattion');
    Route::post('/notify', [DashbordController::class, 'notify'])->middleware('can:access_to_app_notificattion');

    Route::get('/withdraw', [DashbordController::class, 'withdraw'])->middleware('can:access_to_withdraws');
    Route::post('/withdraw/bulk-action', [DashbordController::class, 'bulkActionWithdraw'])->name('withdraw.bulk-action');
    Route::get('/withdraw/status/{id}', [DashbordController::class, 'withdrawStatus'])->middleware('can:access_to_withdraws')->name('withdraw.status');

    Route::get('/app/rates', [DashbordController::class, 'appRates'])->middleware('can:access_to_app_rates');
    Route::get('/provider/service/rates', [RatingController::class, 'rates'])->middleware('can:access_to_service_rates')->name('rate.index');
    Route::get('/provider/service/rates/edit/{id}', [RatingController::class, 'edit'])->middleware('can:access_to_service_rates')->name('rate.edit');
    Route::post('/provider/service/rates/edit/{id}', [RatingController::class, 'update'])->middleware('can:access_to_service_rates');
    Route::get('/provider/service/rates/delete/{id}', [RatingController::class, 'delete'])->middleware('can:access_to_service_rates')->name('rate.delete');
    Route::get('/payments', [PaymentController::class, 'index'])->middleware('can:access_to_payments');
    Route::get('/payments/export', [PaymentController::class, 'export'])->middleware('can:access_to_payments')->name('payments.export');
    Route::get('/subscribers', [SubscribeController::class, 'index'])->middleware('can:access_to_subscribers');
    Route::get('/backups', [BackupsController::class, 'index'])->middleware('can:access_to_backup');
    Route::get('/backups/delete/{name}', [BackupsController::class, 'delete'])->name('backup.delete')->middleware('can:access_to_backup');
    Route::get('/statistic', [StatisticController::class, 'index'])->middleware('can:access_to_statistic');
    Route::post('/quick/offers/{id}', [ProviderServicesController::class, 'quickOffers'])->middleware('can:access_to_quick_offers');
    // subscribes packes
    Route::prefix('subscribes/packes')->as('subscribe.pack')->middleware('can:access_to_subscriber_packes')->group(function () {
        Route::get('/', [SubscribeController::class, 'subscribes'])->name('.index');
        Route::view('/create', 'subscribes.create')->name('.create');
        Route::post('/create', [SubscribeController::class, 'store']);
        Route::get('/edit/{id}', [SubscribeController::class, 'edit'])->name('.edit');
        Route::post('/edit/{id}', [SubscribeController::class, 'update']);
        Route::get('/delete/{id}', [SubscribeController::class, 'delete'])->name('.delete');
    });
    // translate
    Route::prefix('translate')->as('translate')->middleware('can:access_to_users')->group(function () {
        Route::get('/', [TranslateController::class, 'index']);
        Route::post('/store', [TranslateController::class, 'store'])->name('.store');
    });
    /*  logout */
    Route::get('/logout', [LoginController::class, 'logout']);

    /**dashbord */
    Route::get('/dashbord', [DashbordController::class, 'dashbord'])->name('dashbord');

    /*  providers  */
    Route::prefix('provider')->as('provider')->middleware('can:access_to_users')->group(function () {
        Route::get('create', [ProviderController::class, 'create'])->name('.create');
        Route::post('store', [ProviderController::class, 'store'])->name('.store');
        Route::match(['get', 'post'], '/profile/{id}', [ProviderController::class, 'profile'])->name('.profile');
        Route::post('/store', [ProviderController::class, 'store']);
        Route::get('/edit/{id}', [ProviderController::class, 'edit']);
        Route::post('/edit/{id}', [ProviderController::class, 'update']);
        Route::get('/delete/{id}', [ProviderController::class, 'delete']);
        Route::get('/service/details/{id}', [ProviderController::class, 'serviceDetails']);
        Route::post('/{user}/update-transactions', [ProviderController::class, 'updateTransactionUsd']);
        Route::post('/{id}/ajax', [ProviderController::class, 'ajax']);
        Route::post('/{id}/skills', [ProviderController::class, 'skills'])->name('.skills');
        Route::post('/bulk-action', [ProviderController::class, 'bulkAction'])->name('.bulk-action');
        Route::post('/import', [ProviderController::class, 'import'])->name('.import');
    });
    Route::prefix('admins')->as('admins')->middleware('can:access_to_users')->group(function () {
        Route::view('/chat', 'provider.profile');
    });


    /*  users  */
    Route::prefix('user')->as('user')->middleware('can:access_to_users')->group(function () {

        Route::get('/admins', [UserController::class, 'admins'])->name('.admins');
        Route::get('/admins/export', [UserController::class, 'exportAdmins'])->name('.admins.export');

        // providers
        Route::get('/providers', [UserController::class, 'providers'])->name('.providers');
        Route::get('/providers/export', [UserController::class, 'exportProviders'])->name('.providers.export');

        // users
        Route::get('/users', [UserController::class, 'users'])->name('.users');
        Route::get('/users/{user}/edit', [UserController::class, 'editUser'])->name('.users.edit');
        Route::put('/users/{user}/update', [UserController::class, 'updateUser'])->name('.users.update');
        Route::get('/users/export', [UserController::class, 'exportUsers'])->name('.users.export');

        // chat
        Route::get('/chat/review', [UserController::class, 'chat_reviews'])->name('.chat_reviews');
        Route::get('/chat/review/export', [UserController::class, 'exportChatReviews'])->name('.chat_reviews.export');

        Route::get('/profile', [UserController::class, 'profile'])->name('.profile');

        Route::view('/create', 'user.create')->name('create');
        Route::post('/create', [UserController::class, 'store']);

        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('.edit');
        Route::post('/edit/{id}', [UserController::class, 'update']);

        Route::get('/delete/{id}', [UserController::class, 'delete'])->name('.delete');
        Route::get('/admins/delete/{id}', [UserController::class, 'deleteAdmin'])->name('.admin.delete');

        Route::get('/block/{id}', [UserController::class, 'block'])->name('.block');
        Route::post('/bulk-action', [UserController::class, 'bulkAction'])->name('.bulk-action');
        Route::post('/chat-review/bulk-action', [UserController::class, 'chatReviewBulkAction'])->name('.chat-review.bulk-action');
    });

    Route::prefix('user/chat/review')->as('chat_review')->middleware('can:access_to_users')->group(function () {
        Route::get('/create', [UserChatReviewController::class, 'create'])->name('.create');
        Route::post('/create', [UserChatReviewController::class, 'store'])->name('.store');
        Route::get('/edit/{id}', [UserChatReviewController::class, 'edit'])->name('.edit');
        Route::post('/edit/{id}', [UserChatReviewController::class, 'update'])->name('.update');
        Route::get('/delete/{id}', [UserChatReviewController::class, 'delete'])->name('.delete');
    });
    /* faq */
    Route::group(['middleware' => 'can:access_to_faq', 'prefix' => 'faq', 'as' => 'faq.'], function () {

        Route::get('/', [FaqController::class, 'index'])->name('index');

        Route::get('/create', [FaqController::class, 'create'])->name('create');

        Route::post('/store', [FaqController::class, 'store'])->name('store');

        Route::get('/edit/{id}', [FaqController::class, 'edit'])->name('edit');

        Route::post('/update/{id}', [FaqController::class, 'update'])->name('update');

        Route::get('/delete/{id}', [FaqController::class, 'delete'])->name('delete');
    });
    /* quick offers. */
    Route::group(['prefix' => 'quick_offers', 'as' => 'quick_offers.'], function () {

        Route::get(
            '/',
            [QuickOffersController::class, 'index']
        )->name('index');

        Route::get('/create', [QuickOffersController::class, 'create'])->name('create');

        Route::post('/create', [QuickOffersController::class, 'store'])->name('store');

        Route::get('/edit/{id}', [QuickOffersController::class, 'edit'])->name('edit');

        Route::post('/edit/{id}', [QuickOffersController::class, 'update'])->name('update');

        Route::get('/delete/{id}', [QuickOffersController::class, 'delete'])->name('delete');
        Route::get('/provider/service/{id}', [QuickOffersController::class, 'providerService'])->name('create');
        Route::patch('/settings', OfferSettingController::class)->name('settings.update');

        Route::get('get-offers/{service}', [QuickOffersController::class, 'getServiceOffers']);
    });


    /** settings */
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings')->middleware('can:access_to_settings');
    Route::post('/settings', [SettingsController::class, 'update'])->middleware('can:access_to_settings');
    Route::post('/settings/payment', [SettingsController::class, 'payment'])->middleware('can:access_to_settings')->name('payment.setting');
    Route::post('/settings/email', [SettingsController::class, 'email'])->middleware('can:access_to_settings')->name('email.setting');

    /**service categories */
    Route::as('service_categories')->prefix('service/category')->middleware('can:access_to_services')->group(function () {

        Route::get('/', [ServiceCategoriesController::class, 'index'])->name('.index');

        Route::get('/create', [ServiceCategoriesController::class, 'create']);
        Route::post('/create', [ServiceCategoriesController::class, 'store']);

        Route::get('/edit/{id}', [ServiceCategoriesController::class, 'edit']);
        Route::post('/edit/{id}', [ServiceCategoriesController::class, 'update']);

        Route::get('/delete/{id}', [ServiceCategoriesController::class, 'delete']);
        Route::get('/block/{id}', [ServiceCategoriesController::class, 'block']);
        Route::post('/bulk-action', [ServiceCategoriesController::class, 'bulkAction'])->name('.bulk-action');
    });

    /**service subcategories */
    Route::as('service.subcategories')->prefix('service/subcategories')->middleware('can:access_to_services')->group(function () {

        Route::get('/', [ServiceSubcategoriesController::class, 'index'])->name('.index');

        Route::get('/create', [ServiceSubcategoriesController::class, 'create']);
        Route::post('/create', [ServiceSubcategoriesController::class, 'store']);

        Route::get('/edit/{id}', [ServiceSubcategoriesController::class, 'edit']);
        Route::post('/edit/{id}', [ServiceSubcategoriesController::class, 'update']);

        Route::get('/delete/{id}', [ServiceSubcategoriesController::class, 'delete']);
        Route::get('/block/{id}', [ServiceSubcategoriesController::class, 'block']);
        Route::post('/bulk-action', [ServiceSubcategoriesController::class, 'bulkAction'])->name('.bulk-action');
    });
    /**service sub2 */
    Route::as('service.sub2')->prefix('service/sub2')->middleware('can:access_to_services')->group(function () {

        Route::get(
            '/',
            [ServiceSub2Controller::class, 'index']
        )->name('.index');

        Route::get('/create', [ServiceSub2Controller::class, 'create']);
        Route::post('/create', [ServiceSub2Controller::class, 'store']);

        Route::get('/edit/{id}', [ServiceSub2Controller::class, 'edit']);
        Route::post('/edit/{id}', [ServiceSub2Controller::class, 'update']);

        Route::get('/delete/{id}', [ServiceSub2Controller::class, 'delete']);
        Route::get('/block/{id}', [ServiceSub2Controller::class, 'block']);
        Route::post('/bulk-action', [ServiceSub2Controller::class, 'bulkAction'])->name('.bulk-action');
    });
    /**service sub3 */
    Route::as('service.sub3')->prefix('service/sub3')->middleware('can:access_to_services')->group(function () {

        Route::get(
            '/',
            [ServiceSub3Controller::class, 'index']
        )->name('.index');

        Route::get('/create', [ServiceSub3Controller::class, 'create']);
        Route::post('/create', [ServiceSub3Controller::class, 'store']);

        Route::get('/edit/{id}', [ServiceSub3Controller::class, 'edit']);
        Route::post('/edit/{id}', [ServiceSub3Controller::class, 'update']);

        Route::get('/delete/{id}', [ServiceSub3Controller::class, 'delete']);
        Route::get('/block/{id}', [ServiceSub3Controller::class, 'block']);
        Route::post('/bulk-action', [ServiceSub3Controller::class, 'bulkAction'])->name('.bulk-action');
    });
    /**service sub4*/
    Route::as('service.sub4')->prefix('service/sub4')->middleware('can:access_to_services')->group(function () {

        Route::get(
            '/',
            [ServiceSub4Controller::class, 'index']
        )->name('.index');

        Route::get('/create', [ServiceSub4Controller::class, 'create']);
        Route::post('/create', [ServiceSub4Controller::class, 'store']);

        Route::get('/edit/{id}', [ServiceSub4Controller::class, 'edit']);
        Route::post('/edit/{id}', [ServiceSub4Controller::class, 'update']);

        Route::get('/delete/{id}', [ServiceSub4Controller::class, 'delete']);
        Route::get('/block/{id}', [ServiceSub4Controller::class, 'block']);
        Route::post('/bulk-action', [ServiceSub4Controller::class, 'bulkAction'])->name('.bulk-action');
    });



    /*Slider */
    Route::as('slider')->prefix('slider')->middleware('can:access_to_sliders')->group(function () {


        Route::get('/', [SliderController::class, 'index'])->name('.index');



        Route::view('/create', 'slider.create');
        Route::post('/create', [SliderController::class, 'create']);

        Route::get('/button/create/{slider_id}', [SliderController::class, 'addButtons'])->name('.newBtn');
        Route::get('/button/create/{slider_id}/edit/{button}', [SliderController::class, 'editButton'])->name('.editBtn');
        Route::post('/button/create/{slider_id}/edit/{button}', [SliderController::class, 'updateButton'])->name('.updateBtn');
        Route::get('/button/active/{button}', [SliderController::class, 'active'])->name('.btn.active');
        Route::post('/button/create/{slider_id}', [SliderController::class, 'createNewBotton']);


        Route::get('/edit/{id}', [SliderController::class, 'edit']);
        Route::post('/edit/{id}', [SliderController::class, 'update']);

        Route::get('/delete/{id}', [SliderController::class, 'delete']);
        Route::get('/button/delete/{slider_url_id}', [SliderController::class, 'deleteBtn'])->name('.btn.delete');

        Route::get('/block/{id}', [SliderController::class, 'block']);

        Route::post('/bulk-actions', [SliderController::class, 'bulkAction'])->name('.bulk-action');
    });

    /*pages */
    Route::as('pages')->prefix('pages')->group(function () {

        Route::get('/', [PagesController::class, 'index'])->name('.index');

        Route::view('/create', 'pages.create');
        Route::post('/create', [PagesController::class, 'create']);

        Route::get('/edit/{id}', [PagesController::class, 'edit']);
        Route::post('/edit/{id}', [PagesController::class, 'update']);

        Route::get('/delete/{id}', [PagesController::class, 'delete']);
        Route::get('/block/{id}', [PagesController::class, 'block']);
    });
    /*  products  */
    Route::middleware('can:access_to_products')->as('product')->prefix('product')->group(function () {

        Route::get('/', [ProductController::class, 'index'])->name('.index');

        Route::get('/create', [ProductController::class, 'create'])->name('.create');
        Route::post('/create', [ProductController::class, 'store'])->name('.store');

        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('.edit');
        Route::post('/edit/{id}', [ProductController::class, 'update'])->name('.update');

        Route::get('/delete/{id}', [ProductController::class, 'delete'])->name('.delete');
        Route::get('/block/{id}', [ProductController::class, 'block']);
        Route::get('export', [ProductController::class, 'export'])->name('.export');
        Route::post('/bulk-actions', [ProductController::class, 'bulkAction'])->name('.bulk-action');
        Route::post('/import', [ProductController::class, 'import'])->name('.import');

        Route::post('revision', [ProductController::class, 'updateRevisionStatus'])->name('.revision.status');

        Route::post('/category/{category}', [ProductController::class, 'productsCategory']);
    });

    /*services */
    Route::as('services')->prefix('services')->middleware('can:access_to_services')->group(function () {

        Route::get('/', [ServiceController::class, 'index'])->name('.index');

        Route::get('/create', [ServiceController::class, 'create']);
        Route::post('/create', [ServiceController::class, 'store']);

        Route::get('/edit/{id}', [ServiceController::class, 'edit']);
        Route::post('/edit/{id}', [ServiceController::class, 'update']);

        Route::get('/delete/{id}', [ServiceController::class, 'delete']);
        Route::get('/block/{id}', [ServiceController::class, 'block']);
        Route::post('/bulk-action', [ServiceController::class, 'bulkAction'])->name('.bulk-action');

        Route::post('/provider-services/{service}', [ServiceController::class, 'getProviderServices']);
    });
    /*countries */
    Route::as('countries')->prefix('countries')->middleware('can:access_to_countries')->group(function () {

        Route::get(
            '/',
            [CountryController::class, 'index']
        )->name('.index');

        Route::get('/create', [CountryController::class, 'create']);
        Route::post('/create', [CountryController::class, 'store'])->name('.store');

        Route::get('/edit/{id}', [CountryController::class, 'edit']);
        Route::post('/edit/{id}', [CountryController::class, 'update']);

        Route::get('/delete/{id}', [CountryController::class, 'delete']);
        Route::get('/block/{id}', [CountryController::class, 'block']);
        Route::get('/export', [CountryController::class, 'export'])->name('.export');
        Route::post('/bulk-action', [CountryController::class, 'bulkAction'])->name('.bulk-action');

        Route::get('/ajax-create', [CountryController::class, 'create_ajax'])->name('ajax.create');
    });

    /*cities */
    Route::as('cities')->prefix('cities')->middleware('can:access_to_cities')->group(function () {

        Route::get(
            '/',
            [CitiesController::class, 'index']
        )->name('.index');

        Route::get('/create', [CitiesController::class, 'create']);
        Route::post('/create', [CitiesController::class, 'store'])->name('.store');

        Route::get('/edit/{id}', [CitiesController::class, 'edit']);
        Route::post('/edit/{id}', [CitiesController::class, 'update']);

        Route::get('/delete/{id}', [CitiesController::class, 'delete']);
        Route::get('/block/{id}', [CitiesController::class, 'block']);
        Route::post('/bulk-action', [CitiesController::class, 'bulkAction'])->name('.bulk-action');
        Route::post('load', [CitiesController::class, 'loadCities'])->name('.load');
        Route::post('assing', [CitiesController::class, 'assignToCountry'])->name('.assign');

        Route::get('/ajax-create', [CitiesController::class, 'create_ajax'])->name('ajax.create');
    });

    // Models
    Route::resource('models', ModelController::class)->except('show');
    Route::get('/models/ajax-create', [ModelController::class, 'create_ajax'])->name('models.ajax.create');

    // Brands
    Route::resource('brands', BrandController::class)->except('show');
    Route::get('/brands/ajax-create', [BrandController::class, 'create_ajax'])->name('brands.ajax.create');

    // Colors
    Route::resource('colors', ColorController::class)->except('show');
    Route::get('/colors/ajax-create', [ColorController::class, 'create_ajax'])->name('colors.ajax.create');

    // Issues
    Route::resource('issues', IssueController::class)->except('show');
    Route::get('/issues/ajax-create', [IssueController::class, 'create_ajax'])->name('issues.ajax.create');

    // Types
    Route::resource('types', MaintenanceRequestTypeController::class)->except('show');
    Route::get('/types/ajax-create', [MaintenanceRequestTypeController::class, 'create_ajax'])->name('types.ajax.create');

    /*street */
    Route::as('street')->prefix('street')->middleware('can:access_to_street')->group(function () {

        Route::get(
            '/',
            [StreetController::class, 'index']
        )->name('.index');
        Route::get('/create', [StreetController::class, 'create']);
        Route::post('/create', [StreetController::class, 'store'])->name('.store');

        Route::get('/edit/{id}', [StreetController::class, 'edit']);
        Route::post('/edit/{id}', [StreetController::class, 'update']);

        Route::get('/delete/{id}', [StreetController::class, 'delete']);
        Route::get('/block/{id}', [StreetController::class, 'block']);
        Route::get('/export', [StreetController::class, 'export'])->name('.export');
        Route::post('/bulk-action', [StreetController::class, 'bulkAction'])->name('.bulk-action');

        Route::get('/ajax-create', [StreetController::class, 'create_ajax'])->name('ajax.create');
    });
    /*coupons */
    Route::as('coupons')->prefix('coupons')->group(function () {

        Route::get('/', [CouponsController::class, 'index'])->name('.index');

        Route::view('/create', 'coupons.create');
        Route::post('/create', [CouponsController::class, 'create']);

        Route::get('/edit/{id}', [CouponsController::class, 'edit']);
        Route::post('/edit/{id}', [CouponsController::class, 'update']);

        Route::get('/delete/{id}', [CouponsController::class, 'delete']);
        Route::get('/block/{id}', [CouponsController::class, 'block']);
    });
    /*welcome */
    Route::as('welcome')->prefix('welcome')->middleware('can:access_to_welcome')->group(function () {


        Route::get(
            '/users',
            [WelcomeController::class, 'welcomeUsers']
        )->name('.users');
        Route::get(
            '/providers',
            [WelcomeController::class, 'welcomeProviders']
        )->name('.providers');

        Route::view('/create', 'welcome.create');
        Route::post('/create', [WelcomeController::class, 'create']);

        Route::get('/edit/{id}', [WelcomeController::class, 'edit']);
        Route::post('/edit/{id}', [WelcomeController::class, 'update']);

        Route::get('/delete/{id}', [WelcomeController::class, 'delete']);
        Route::get('/block/{id}', [WelcomeController::class, 'block']);
    });
    /*orders */
    Route::as('orders')->prefix('orders')->middleware('can:access_to_orders')->group(function () {

        Route::get('/', [OrderController::class, 'index'])->name('.index');

        Route::get('/create', [OrderController::class, 'create']);
        Route::post('/create', [OrderController::class, 'store']);

        Route::get('/edit/{id}', [OrderController::class, 'edit']);
        Route::post('/edit/{id}', [OrderController::class, 'update']);

        Route::get('/delete/{id}', [OrderController::class, 'delete']);
        Route::get('/block/{id}', [OrderController::class, 'block']);

        Route::post('/services', [OrderController::class, 'providerServices']);
        Route::get('/export', [OrderController::class, 'export'])->name('.export');
    });
    /*reports */
    Route::as('reports')->prefix('reports')->middleware('can:access_to_reports')->group(function () {

        Route::get('/', [ReportsController::class, 'index'])->name('.index');

        Route::get('/solved/{id}', [ReportsController::class, 'changeStatus'])->name('.solved');
        Route::get('/delete/{id}', [ReportsController::class, 'delete']);
        Route::get('/export', [ReportsController::class, 'export'])->name('.export');
        Route::post('/bulk-actions', [ReportsController::class, 'bulkAction'])->name('.bulk-action');
    });
    /* provider */

    Route::as('providers.services')->prefix('providers/services')->middleware('can:access_to_providers_services')->group(function () {

        Route::get('/', [ProviderServicesController::class, 'index'])->name('.index');
        Route::get('/accept/{id}', [ProviderServicesController::class, 'accept']);
        Route::get('reject/{id}', [ProviderServicesController::class, 'reject']);
        Route::get('delete/{id}', [ProviderServicesController::class, 'delete']);
    });

    Route::get('/transactions', [TransactionController::class, 'transactions'])->middleware('can:access_to_transactions');
    Route::get('/transactions/export', [TransactionController::class, 'export'])->middleware('can:access_to_transactions')->name('transactions.export');

    Route::resource('service-types', ServiceTypeController::class);
    Route::post('service-types/bulk-action', [ServiceTypeController::class, 'bulkAction'])->name('service-types.bulk-action');
});


Route::get('error', function () {
    $order = 1;

    $order = $order + 'null';

    return $order;
});


Route::get('/not', function () {


    $notifications = AdminNotification::all();
    $html = <<<HTML
        <div>Hello world</div>
    HTML;

    $html = $notifications->map(function ($notification) use ($html) {
        return <<<HTML
            <a class="dropdown-item" href="{$notification->link}">
                <span>{$notification->description}</span>
            </a>
        HTML;
    })->toArray();

    return implode(" ", $html);
});

Route::get('/fatoorah/{user_id}/invoice/{product_id}', [ProductController::class, 'createInvoice'])->name('products.invoice.create');
Route::get('/fatoorah/success', [ProductController::class, 'successInvoice'])->name('fatoorah.success');

Route::get('/order/fatoorah/{user_id}/invoice', [ProductController::class, 'createOrderInvoice'])->name('products.invoice.order.create');
Route::get('/order/fatoorah/invoice/success', [ProductController::class, 'successInvoiceOrder'])->name('products.invoice.order.success');

Route::get('/order/payment-error', [MaintenanceOrderController::class, 'fatoorah_failed']);
Route::get('/order/success', [MaintenanceOrderController::class, 'fatoorah_success'])->name('maintenance-request.payement.status');

Route::get('/my-fatoorah/main-store/order', [MaintenanceOrderController::class, 'store_fatoorah'])->name('main-store.order.store');
Route::get('/paypal/main-store/order', [MaintenanceOrderController::class, 'store_paypal'])->name('paypal.main-store.order.store');

Route::get('log/{user}', function (User $user) {
    auth()->login($user);

    return redirect('pusher');
});


Route::get('updatemigration', function () {
    $dirs = scandir(database_path('migrations'));
    $dirs = array_values(array_filter($dirs, fn ($item) => $item != '.' && $item != '..'));

    $migrations = DB::table('migrations')->select('migration')->get()->pluck('migration')->toArray();

    foreach ($dirs as $dir) {
        if (!in_array($dir, $migrations)) {
            DB::table('migrations')->insert([
                'migration' => str_replace('.php', '', $dir),
                'batch' => 1,
            ]);
        }
    }
});


Route::get('/so', function () {
    return view('socket.index');
});

Route::get('/testmessage', function () {
    event(new MessageEvent);
});

Route::get('/test', function () {
    // $url = "https://play.google.com/store/apps/details?id=app.android.doctortecapp.com";

    // $html = file_get_contents($url);

    // $crawler = new Crawler($html);
});

Route::middleware('auth')->get('pusher', function () {
    if (auth()->user()->role == 'provider') {
        $canceled_buyer_requests = CanceledBuyerRequest::all()->where('user_id', auth()->id())->pluck('buyer_request_id')->filter()->unique()->values()->toArray();

        $buyer_requests = BuyerRequest::query()->whereNull('provider_id')->whereNotIn('id', $canceled_buyer_requests)->latest('id')->get();
    } else {
        $buyer_requests = BuyerRequest::query()->whereNull('provider_id')->latest('id')->get();
    }

    return view('pusher', compact('buyer_requests'));
});

Route::get('pusher/{order}/details', function (BuyerRequest $order) {
    return view('pusher-details', compact('order'));
});


Route::post('pusher/{order}/accept', function (BuyerRequest $order) {
    $order->update([
        'provider_id' => auth()->id()
    ]);

    event(new BuyerRequestAccepted($order));
    event(new BuyerRequestUpdates($order));

    return back();
});

Route::post('pusher/{order}/reject', function (BuyerRequest $order) {
    CanceledBuyerRequest::query()->create([
        'buyer_request_id' => $order->id,
        'user_id' => auth()->id(),
    ]);

    return redirect('pusher');
});

Route::get('/privacy-policy', [FrontController::class, 'privay_policy_page']);
