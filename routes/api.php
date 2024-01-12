<?php

use App\Helpers\FCM;
use App\Http\Controllers\api\AppRateApiController;
use App\Http\Controllers\api\AuthApiController;
use App\Http\Controllers\api\targertsController;
use App\Http\Controllers\api\ProviderOnlineController;
use App\Http\Controllers\api\CartApiController;
use App\Http\Controllers\api\ChatApiController;
use App\Http\Controllers\api\CityController;
use App\Http\Controllers\api\StatisticsController;
use App\Http\Controllers\api\ConfigApiController;
use App\Http\Controllers\api\IdentityVerificationController;
use App\Http\Controllers\api\OfferApiController;
use App\Http\Controllers\api\OrderApiController;
use App\Http\Controllers\api\ProviderApiController;
use App\Http\Controllers\api\RatingApiController;
use App\Http\Controllers\api\ReportApiController;
use App\Http\Controllers\api\ServiceCategoriesApiController;
use App\Http\Controllers\api\ServiceOffersApiController;
use App\Http\Controllers\api\ServicesApiController;
use App\Http\Controllers\api\ServiceSubcategoriesApiController;
use App\Http\Controllers\api\SubscribeApiController;
use App\Http\Controllers\api\UserApiController;
use App\Http\Controllers\api\ProductApiController;
use App\Http\Controllers\api\SkillApiController;
use App\Http\Controllers\api\SliderApiController;
use App\Http\Controllers\api\V2\MaintenanceRequestCouponContorller;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ServiceAllSubcategories;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Events\myEvent;
use Illuminate\Http\JsonResponse;


use Illuminate\Support\Facades\Mail;
use App\Mail\OfferCanceled;


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




// -------------------------------------------------------------

Route::middleware('auth:sanctum')->get('user', function () {
    return auth()->user();
});

Route::post('/test/test-file-upload', function () {
    $exec = '';
    // exec('whoami', $exec);
    // dd($exec);
    $url = upload_picture(request()->file('avatar'), '/images/avatars');
    return response()->data(compact('url'));
});

Route::post('send-otp', [AuthApiController::class, 'sendOTPCode']);
Route::post('check-otp', [AuthApiController::class, 'checkOtpCode']);

Route::match(['post', 'get'], '/config', [ConfigApiController::class, 'config']);
Route::match(['post', 'get'], '/config/current', [ConfigApiController::class, 'config']);
Route::match(['post', 'get'], 'storage/flags/{county_code}', [ConfigApiController::class, 'flag']);
Route::match(['post', 'get'], '/country/{county_code}', [ConfigApiController::class, 'countyDetails']);
Route::match(['post', 'get'], '/country/cities/{county_id}', [ConfigApiController::class, 'countyCities']);
Route::match(['post', 'get'], '/city/street/{city_id}', [ConfigApiController::class, 'citystreets']);

Route::get('streets/{city}', [CityController::class, 'get_city_streets']);

Route::post('/users/login', [AuthApiController::class, 'login']);
Route::post('/users/register', [AuthApiController::class, 'register']);
Route::post('/users/check/phone', [AuthApiController::class, 'checkPhone']);

Route::get('/product/brands/{product_type_id}', [ProductApiController::class, 'productBrands']);
/*  config  */
Route::match(['post', 'get'], '/faq', [ConfigApiController::class, 'faq']);
Route::match(['post', 'get'], '/privacy/policy', [ConfigApiController::class, 'privacy_policy']);
Route::match(['post', 'get'], '/terms', [ConfigApiController::class, 'terms_of_use']);
Route::match(['post', 'get'], '/about', [ConfigApiController::class, 'about']);

Route::match(['post', 'get'], 'services/categories/{id}', [ServicesApiController::class, 'categories']);
Route::match(['post', 'get'], 'services/subcategories/{id}', [ServiceAllSubcategories::class, 'subcategories']);
Route::match(['post', 'get'], 'services/sub2/{id}', [ServiceAllSubcategories::class, 'sub2']);
Route::match(['post', 'get'], 'services/sub3/{id}', [ServiceAllSubcategories::class, 'sub3']);


Route::match(['post', 'get'], 'services/details/{provider_id}', [ProviderApiController::class, 'services_']);
Route::match(['post', 'get'], 'service/{provider_service_id}', [ProviderApiController::class, 'serviceDetails']);
Route::match(['post', 'get'], 'service/offers/{provider_service_id}', [OfferApiController::class, 'allOffersActive']);
Route::match(['post', 'get'], 'service/ratings/{provider_service_id}', [ProviderApiController::class, 'providerServiceRatings']);
Route::match(['post', 'get'], 'service/{provider_id}', [ProviderApiController::class, 'services']);
Route::match(['post', 'get'], 'service/categories/{service_id}', [ServiceCategoriesApiController::class, 'category']);
Route::match(['post', 'get'], 'services/filters', [ServicesApiController::class, 'filters']);
Route::match(['post', 'get'], 'services/filters/online', [ServicesApiController::class, 'filtersOnlineService']);
Route::match(['post', 'get'], 'quick/offers/{service_id}', [OfferApiController::class, 'quickOffers']);

Route::match(['post', 'get'], '/provider/identity/{provider_id}', [ProviderApiController::class, 'identity']);
Route::match(['post', 'get'], '/provider/verified/{provider_id}', [ProviderApiController::class, 'verified']);
Route::match(['post', 'get'], '/provider/unverified/{provider_id}', [ProviderApiController::class, 'unverified']);
Route::match(['post', 'get'], '/provider/commission/{provider_id}', [ProviderApiController::class, 'commissionCreate']);
Route::match(['post', 'get'], '/provider/debt_ceiling/{provider_id}', [ProviderApiController::class, 'changeDebt_ceiling']);
Route::match(['post', 'get'], '/provider/generate/key/{provider_id}', [ProviderApiController::class, 'generateKey']);
Route::match(['post', 'get'], '/provider/generate/phone/key/{number_phone}', [ProviderApiController::class, 'generateKeyByphone']);
Route::match(['post'],        '/provider/transaction/create', [ProviderApiController::class, 'transactionCreate']);
Route::match(['post', 'get'], '/provider/profile/{provider_id}', [ProviderApiController::class, 'profile']);
Route::match(['post', 'get'], '/provider/increment/{provider_id}', [ProviderApiController::class, 'incrementProfileViewers']);
Route::match(['get', 'post'], '/payment', [PaymentController::class, 'payWithpaypal']);

/**
 * subscribe route
 */
Route::prefix('subscribe')->group(function () {
    Route::match(['get', 'post'], '/payment', [SubscribeApiController::class, 'payNewSubscribe']);
    Route::match(['get', 'post'], '/data', [SubscribeApiController::class, 'subscribeInfo']);
});


// Notify Users that provider is online

    Route::post('/providerOnline' , [ProviderOnlineController::class , 'notiftyUsers']);
    Route::get('/channalInfo' , [ProviderOnlineController::class , 'index']);

/* store */
Route::prefix('store')->group(function () {
    Route::match(['post', 'get'], '/load'           , [ProductApiController::class, 'products'  ]);
    Route::match(['post', 'get'], '/configuration'  , [ProductApiController::class, 'categories']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::delete('/account/delete', [UserApiController::class, 'deleteAccount']);

    Route::match(['get', 'post'], 'subscribe/statistics', [SubscribeApiController::class, 'statistics']);

    Route::group(['as' => 'carts', 'prefix' => 'carts/'], function () {
        Route::get('/', [CartApiController::class, 'index'])->name('carts.index');
        Route::post('/store', [CartApiController::class, 'store'])->name('carts.store');
        Route::post('/remove', [CartApiController::class, 'remove'])->name('carts.remove');
        Route::post('/order', [CartApiController::class, 'order'])->name('carts.order');
        Route::post('/order/paid', [CartApiController::class, 'payment_order']);
        Route::post('/{cart}/update', [CartApiController::class, 'update'])->name('carts.update');
        Route::delete('/{cart}/delete', [CartApiController::class, 'destroy'])->name('carts.destroy');
        Route::post('/{cart}/paid', [CartApiController::class, 'markAsPaid'])->name('carts.paid');
    });

    Route::resource('skills', SkillApiController::class)->except(['index', 'update', 'create', 'edit', 'show']);
    Route::post('skills/{skill}/update', [SkillApiController::class, 'update']);
    Route::get('skills/get', [SkillApiController::class, 'getAll']);

    Route::post('/identity/upload', [IdentityVerificationController::class, 'upload']);

    // --------------------------------------


    // copuns ----------------------------------

    Route::match(['post', 'get'], '/allcops', [MaintenanceRequestCouponContorller::class, 'allcoupons']);
    Route::post('/check_product_coupon', [MaintenanceRequestCouponContorller::class, 'check_product_coupon']);

    //  -------------------------------------

    //  ----------------------Statistics----------------------------
    Route::get( '/providers_statistics', [StatisticsController::class, 'providers_statistics']);
    Route::get( '/products_statistics', [StatisticsController::class, 'products_statistics']);
    Route::match(['get' , 'post'] , '/monthly_statistics', [StatisticsController::class, 'monthly_statistics']);
    // --------------------------------------------------------


    /* Slider */


    Route::prefix('slider')->group(function () {

        Route::match(['post', 'get'], '/', [SliderApiController::class, 'getAll']);
        Route::get('/change/active/{id}', [SliderApiController::class, 'activeStatus']);
        Route::get('/delete/{id}', [SliderApiController::class, 'delete']);
        Route::post('/first', [SliderApiController::class, 'firstTime']);//new a
        Route::get('/targets', [SliderApiController::class, 'filterTarget']);
        Route::get('/target/{target}', [SliderApiController::class, 'getByTarget']);
        Route::post('/edit/button/{button_id}', [SliderApiController::class, 'updateButton']);
        Route::post('/button/add', [SliderApiController::class, 'addButton']);
        // Route::post('/add' , [SliderApiController::class, 'AddSilder']);
        // Route::post('/update', [SliderApiController::class, 'update']);

        // Route::match(['post', 'get'], '/all', [AuthApiController::class, 'withdrawRequests']);
        // Route::match(['post', 'get'], '/update', [AuthApiController::class, 'changeStatus']);
    });


    /* withdraw */
    Route::prefix('withdraw')->group(function () {
        Route::match(['post', 'get'], '/', [AuthApiController::class, 'withdraw']);
        Route::match(['post', 'get'], '/all', [AuthApiController::class, 'withdrawRequests']);
        Route::match(['post', 'get'], '/update', [AuthApiController::class, 'changeStatus']);
    });


    /* users */
    Route::prefix('users')->group(function () {
        Route::match(['post', 'get'], '/check/username', [AuthApiController::class, 'hasUsername']);
        Route::match(['post', 'get'], '/logout', [AuthApiController::class, 'logout']);
        Route::match(['post', 'get'], '/profile', [AuthApiController::class, 'profile']);
        Route::post('/account/update', [AuthApiController::class, 'update']);
        Route::post('/permission/update', [AuthApiController::class, 'updatePermission']);
        Route::post('/permission/setall', [AuthApiController::class, 'setAllPermessions']);
        Route::match(['post', 'get'], '/notifications', [AuthApiController::class, 'notifications']);
        Route::match(['post', 'get'], '/all', [AuthApiController::class, 'allUsers']);
    });
    Route::prefix('user')->group(function () {
        Route::match(['post', 'get'], '/check/username', [AuthApiController::class, 'hasUsername']);
    });

    /* providers */
    Route::prefix('provider')->group(function () {

        Route::match(['post', 'get'], '/', [ProviderApiController::class, 'providers']);
        Route::match(['post', 'get'], '/offers/{provider_id}', [ProviderApiController::class, 'offers']);
        Route::match(['post', 'get'], '/orders/{provider_id}', [ProviderApiController::class, 'orders']);
        Route::match(['post', 'get'], '/statistics', [ProviderApiController::class, 'orderStatistics']);
        Route::match(['post', 'get'], '/ratings/{provider_id}', [ProviderApiController::class, 'ratings']);
        Route::match(['post', 'get'], '/services/{provider_id}', [ProviderApiController::class, 'services']);
        Route::match(['post', 'get'], '/services/list/{provider_id}', [ProviderApiController::class, 'services_list_offer']);
        Route::match(['post', 'get'], '/services/details/{provider_id}', [ProviderApiController::class, 'services_']);
        Route::post('/service/create', [ProviderApiController::class, 'serviceCreate']);
        Route::match(['post', 'get'], '/service/{provider_service_id}', [ProviderApiController::class, 'serviceDetails']);
        Route::match(['post', 'get'], '/service/offers/{provider_service_id}', [OfferApiController::class, 'allOffers']);
        Route::match(['post', 'get'], '/service/ratings/{provider_service_id}', [ProviderApiController::class, 'providerServiceRatings']);
        Route::match(['post', 'get'], '/service/{provider_id}', [ProviderApiController::class, 'services']);
        Route::match(['post', 'get'], '/transactions', [ProviderApiController::class, 'allTransactions']);
        Route::match(['post', 'get'], '/skill/create', [ProviderApiController::class, 'providerSkillCreate']);
        Route::match(['post', 'get'], '/skill/remove/{provider_skill_id}', [ProviderApiController::class, 'providerSkillRemove']);
        Route::match(['post', 'get'], '/all/services', [ProviderApiController::class, 'allProviderServices']);
        Route::match(['post', 'get'], '/change/service/status', [ProviderApiController::class, 'change_services_status']);
        Route::match(['post', 'get'], '/change/service/top', [ProviderApiController::class, 'change_services_pin_top']);
        Route::match(['post', 'get'], '/products', [ProductApiController::class, 'providerProducts']);


        Route::post('/status/update', [ProviderApiController::class, 'status']);
        Route::post('/create', [ProviderApiController::class, 'create']);
        Route::post('/payments/history ', [ProviderApiController::class, 'payments']);

        Route::match(['post', 'get'], '/convertations', [ProviderApiController::class, 'convertations']);

        Route::prefix('service')->group(function () {
            Route::post('/update/{provider_service_id}', [ProviderApiController::class, 'serviceUpdate']);
            Route::post('/delete/{provider_service_id}', [ProviderApiController::class, 'serviceDelete']);
            Route::post('/offers/update/{provider_service_id}', [OfferApiController::class, 'serviceOffersUpdate']);
            Route::post('/offer/edit/{offer_id}', [OfferApiController::class, 'editOffer']);
        });
    });
    /* user */
    Route::prefix('user')->group(function () {
        Route::match(['post', 'get'], '/convertations', [UserApiController::class, 'convertations']);
        Route::match(['post', 'get'], '/onlineStatistics', [UserApiController::class, 'onlineStatistics']);
        Route::match(['post', 'get'], '/transactions', [UserApiController::class, 'allTransactions']);
        Route::match(['post', 'get'], '/profile/{id}', [AuthApiController::class, 'userProfile']);
    });


    /* users */
    Route::prefix('users')->group(function () {
        Route::match(['post', 'get'], '/', [UserApiController::class, 'users']);
        Route::match(['post', 'get'], '/orders/{user_id}', [UserApiController::class, 'orders']);
        Route::match(['post', 'get'], '/chats/{user_id}', [UserApiController::class, 'chats']);
    });



    /* services  */
    Route::prefix('services')->group(function () {

        Route::match(['post', 'get'],    '/', [ServicesApiController::class, 'allServices']);
        Route::match(['post', 'get'], '/details/{service_id}', [ServicesApiController::class, 'details']);
        Route::match(['post', 'get'], '/providers/{id}', [ServicesApiController::class, 'providers']);

        Route::post('/create', [ServicesApiController::class, 'create']);
        Route::post('/update/{id}', [ServicesApiController::class, 'update']);
        Route::delete('/delete/{id}', [ServicesApiController::class, 'delete']);
        Route::post('/add/favourite', [ServicesApiController::class, 'createFavourite']);
        Route::post('/delete/favourite', [ServicesApiController::class, 'deleteFavourite']);
        Route::post('/my/favourite', [ServicesApiController::class, 'myFavourite']);

        Route::post('/offers/create', [ServiceOffersApiController::class, 'create']);
        Route::delete('/offers/delete/{service_offer_id}', [ServiceOffersApiController::class, 'delete']);

        /* services categories   */
        Route::prefix('/categories')->group(function () {
            Route::match(['post', 'get'], '/{service_id}', [ServiceCategoriesApiController::class, 'index']);
            Route::post('/create', [ServiceCategoriesApiController::class, 'create']);
            Route::post('/update/{id}', [ServiceCategoriesApiController::class, 'update']);
            Route::delete('/delete/{id}', [ServiceCategoriesApiController::class, 'delete']);
        });
        Route::prefix('/subcategories{  service_subcategories_id    }')->group(function () {
            Route::post('/create', [ServiceSubcategoriesApiController::class, 'create']);
            Route::post('/update/{id}', [ServiceSubcategoriesApiController::class, 'update']);
            Route::delete('/delete/{id}', [ServiceSubcategoriesApiController::class, 'delete']);
        });
    });

    /* Orders */
    Route::prefix('orders')->group(function () {

        Route::match(['post', 'get'], '/', [OrderApiController::class, 'orders']);
        Route::match(['post', 'get'], '/details/{order_id}', [OrderApiController::class, 'details']);
        Route::post('/create', [OrderApiController::class, 'create']);
        Route::post('/status/{id}', [OrderApiController::class, 'status']);
        Route::delete('/delete/{id}', [OrderApiController::class, 'delete']);
        Route::post('/create/from/offer', [OrderApiController::class, 'createFromOffer']);
        Route::post('/create/direct', [OrderApiController::class, 'createDirectOrder']);
        Route::post('/create/order/product', [OrderApiController::class, 'createOrderProduct']);
        Route::match(['post', 'get'], '/all', [OrderApiController::class, 'allOrders']);
        Route::post('/change/provider', [OrderApiController::class, 'changeProviderOrder']);
        Route::post('/update/quick', [OrderApiController::class, 'updateToQuickOffer']);
        Route::post('/update/direct', [OrderApiController::class, 'updateToDirectOrder']);
    });

    /* Products */
    Route::prefix('product')->group(function () {

        Route::match(['post', 'get'], '/', [ProductApiController::class, 'products']);
        Route::match(['post', 'get'], '/details/{product_id}', [ProductApiController::class, 'details']);
        Route::post('/create', [ProductApiController::class, 'create']);
        Route::post('/update/{id}', [ProductApiController::class, 'update']);
        Route::get('/delete/{id}', [ProductApiController::class, 'delete']);
        Route::get('/change/active/{id}', [ProductApiController::class, 'activeStatus']);
        Route::post('/add/favourite', [ProductApiController::class, 'createFavourite']);
        Route::post('/delete/favourite', [ProductApiController::class, 'deleteFavourite']);
        Route::get('/my/favourite/{user_id}', [ProductApiController::class, 'myFavourite']);

    });

    /* rating */
    Route::prefix('ratings')->group(function () {

        Route::match(['post', 'get'], '/details/{rate_id}', [RatingApiController::class, 'details']);
        Route::post('/create', [RatingApiController::class, 'create']);
        Route::post('/update/{id}', [RatingApiController::class, 'update']);
        Route::delete('/delete/{id}', [RatingApiController::class, 'delete']);

        Route::match(['post', 'get'], '/provider/{order_id}', [RatingApiController::class, 'provider']);
        Route::match(['post', 'get'], '/user/{order_id}', [RatingApiController::class, 'user']);
        Route::match(['post', 'get'], '/service/{order_id}', [RatingApiController::class, 'service']);
    });

    /*Chat */

    Route::prefix('convertation')->group(function () {
        Route::post('/',               [ChatApiController::class, 'convertations']);
        Route::post('/paginate',               [ChatApiController::class, 'paginate']);
        Route::match(['post', 'get'], '/seen',      [ChatApiController::class, 'seen']);
        Route::post('/create',               [ChatApiController::class, 'create']);
        Route::match(['post', 'get'], '/typing',    [ChatApiController::class, 'typing']);
        Route::match(['post', 'get'], '/review',    [ChatApiController::class, 'message_review']);
        Route::match(['post', 'get'], '/review/{provider_id}',  [ChatApiController::class, 'review_provider_messages']);
        Route::match(['post', 'get'], '/message/report',        [ChatApiController::class, 'messageRepote']);
        Route::match(['post', 'get'], '/message/report/delete', [ChatApiController::class, 'deleteMessageRepote']);
    });

    /* offer */
    Route::prefix('offer')->group(function () {
        Route::post('/create',       [OfferApiController::class, 'create']);
        Route::post('/update/{id}',  [OfferApiController::class, 'update']);
        Route::get('/status/{id}',  [OfferApiController::class, 'status']);
        Route::post('/status/{id}',  [OfferApiController::class, 'status']);
        Route::delete('/delete/{id}', [OfferApiController::class, 'delete']);
    });
    /* application rate */
    Route::prefix('application/rate')->group(function () {
        Route::post('/create',       [AppRateApiController::class, 'create']);
        Route::post('/update/{id}',  [AppRateApiController::class, 'update']);
        Route::post('/delete/{id}', [AppRateApiController::class, 'delete']);
    });
    /* Reports */
    Route::prefix('reports')->group(function () {
        Route::match(['post', 'get'], '/',          [ReportApiController::class, 'index']);
        Route::post('/create',                      [ReportApiController::class, 'create']);
        Route::match(['post', 'get'], '/all',       [ReportApiController::class, 'reports']);
        Route::match(['post', 'get'], '/update',    [ReportApiController::class, 'changeStatus']);
        Route::delete('/{report}', [ReportApiController::class, 'delete']);
        Route::get('/{status}', [ReportApiController::class, 'status']);
        Route::post('/{report}/update-status', [ReportApiController::class, 'updateStatus']);
    });
    Route::prefix('notifications')->group(function () {
        Route::match(['post', 'get'], '/', [AuthApiController::class, 'notifications']);
        Route::match(['post', 'get'], '/count', [AuthApiController::class, 'notificationsCount']);
        Route::match(['post', 'get'], '/seen', [AuthApiController::class, 'notificationsSeen']);
    });
    Route::match(['get', 'post'], 'subscribe/iap_ios', [SubscribeApiController::class, 'create']);
});

Route::get('/error', function () {

    $order = 1;

    $order = $order + 'null';

    return $order;
});

Route::post('/slider/addWithBtn/{target}', [SliderController::class, 'silderWithBtn']);
Route::post('/slider/addBtnSlider/{sliderId}', [SliderController::class, 'addBtnSlider']);
Route::put('/slider/editBtnSlider/{sliderId}/{btnId}', [SliderController::class, 'editBtnSlider']);
Route::get('/slider/deleteBtnSlider/{sliderId}/{btnId}', [SliderController::class, 'deleteButton']);

Route::get('/slider/showSlider/{sliderId}', [SliderController::class, 'getSlider']);
Route::post('/slider/editSlider/{sliderId}', [SliderController::class, 'editSlider']);

Route::get('/targets', [SliderController::class, 'getTargets']);

Route::get('/test',function(){
    return view('new');
});

Route::get('/test/mail',function(){
    try{
        return Mail::to(Offer::where('id', 5174)->first()->provider)->send(new OfferCanceled("test done"));
    }
    catch(Exception $e){

        return response()->data("an error occurd : " . $e);
    };
});

require 'v2/api.php';
