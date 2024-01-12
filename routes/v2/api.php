<?php

use App\Http\Controllers\api\V2\BrandController;
use App\Http\Controllers\api\V2\BuyerRequestController;
use App\Http\Controllers\api\V2\ColorController;
use App\Http\Controllers\api\v2\FeesController;
use App\Http\Controllers\api\V2\IssueController;
use App\Http\Controllers\api\V2\MainStoreController;
use App\Http\Controllers\api\V2\MaintenanceRequestCouponContorller;
use App\Http\Controllers\api\V2\MaintenanceRequestTypeController;
use App\Http\Controllers\api\v2\MaintenanceTypeController;
use App\Http\Controllers\api\V2\ModelController;
use App\Http\Controllers\api\v3\MainStoreController as V3MainStoreController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

$router->group(['prefix' => 'no-main-store'], function ($router) {
    $router->get('/', [MainStoreController::class, 'index']);
    $router->post('/', [MainStoreController::class, 'store']);
    $router->get('/create', [MainStoreController::class, 'create']);
    $router->post('/{request}/update', [MainStoreController::class, 'update']);
    $router->delete('/{request}/delete', [MainStoreController::class, 'delete']);

    // Coupons
    $router->apiResource('coupons', MaintenanceRequestCouponContorller::class);
    $router->post('coupons/check', [MaintenanceRequestCouponContorller::class, 'check_coupon']);
});

$router->get('no-main-store/get-params', [MainStoreController::class, 'get_params']);
$router->post('main-store/order-web', [MainStoreController::class, 'store_new_order_from_web']);

Route::group(['prefix' => 'v2', 'middleware' => 'auth:sanctum'], function (Router $router) {

    $router->get('buyer-requests/params', [BuyerRequestController::class, 'get_params']);
    $router->match(['post', 'get'], 'buyer-requests/all', [BuyerRequestController::class, 'all']);
    $router->post('buyer-requests', [BuyerRequestController::class, 'store']);
    $router->get('buyer-requests/{buyer_request}/details', [BuyerRequestController::class, 'details']);
    $router->post('buyer-requests/{buyer_request}/accept', [BuyerRequestController::class, 'accept']);
    $router->post('buyer-requests/{buyer_request}/observer/cancel', [BuyerRequestController::class, 'cancel']);
    $router->post('buyer-requests/{buyer_request}/cancel', [BuyerRequestController::class, 'cancel_buyer_requests']);
    $router->get('buyer-requests/test_notifi', [BuyerRequestController::class, 'test_notifi']);
    $router->get('buyer-requests/{buyer_request}/delete', [BuyerRequestController::class, 'delete_buyer_requests']);
    $router->get('buyer-requests/providers', [BuyerRequestController::class, 'providers']);

    // This is related to the maintenance store.
    $router->group(['prefix' => 'main-store'], function ($router) {
        $router->get('/', [MainStoreController::class, 'index']);
        $router->post('/', [MainStoreController::class, 'store']);
        $router->get('/create', [MainStoreController::class, 'create']);
        $router->post('/{request}/update', [MainStoreController::class, 'update']);
        $router->delete('/{request}/delete', [MainStoreController::class, 'delete']);

        $router->post('requests/new-update', [MainStoreController::class, 'new_update']);
        $router->get('all_info', [MainStoreController::class, 'info']);

        // Coupons
        $router->apiResource('coupons', MaintenanceRequestCouponContorller::class);
        $router->post('coupons/check', [MaintenanceRequestCouponContorller::class, 'check_coupon']);
    });

    // Orders
    $router->get('main-store/get-params', [MainStoreController::class, 'get_params']);
    $router->get('main-store/get-providers', [MainStoreController::class, 'get_providers']);
    $router->post('main-store/order', [MainStoreController::class, 'store_new_order']);
    $router->post('main-store/order-paid', [MainStoreController::class, 'store_new_order_paid']);
    $router->post('main-store/order-check-exists', [MainStoreController::class, 'check_exist_order']);


    $router->get('brands', [BrandController::class, 'index']);
    $router->post('brands', [BrandController::class, 'store']);
    $router->post('brands/{brand}/update', [BrandController::class, 'update']);
    $router->delete('brands/{brand}/delete', [BrandController::class, 'delete']);

    $router->get('models', [ModelController::class, 'index']);
    $router->post('models', [ModelController::class, 'store']);
    $router->post('models/{model}/update', [ModelController::class, 'update']);
    $router->delete('models/{model}/delete', [ModelController::class, 'delete']);

    $router->get('colors', [ColorController::class, 'index']);
    $router->post('colors', [ColorController::class, 'store']);
    $router->post('colors/{color}/update', [ColorController::class, 'update']);
    $router->delete('colors/{color}/delete', [ColorController::class, 'delete']);

    $router->get('issues', [IssueController::class, 'index']);
    $router->post('issues', [IssueController::class, 'store']);
    $router->post('issues/{issue}/update', [IssueController::class, 'update']);
    $router->delete('issues/{issue}/delete', [IssueController::class, 'delete']);

    $router->get('maintenance-types', [MaintenanceRequestTypeController::class, 'index']);
    $router->post('maintenance-types', [MaintenanceRequestTypeController::class, 'store']);
    $router->put('maintenance-types/{type}', [MaintenanceRequestTypeController::class, 'update']);
    $router->delete('maintenance-types/{type}', [MaintenanceRequestTypeController::class, 'delete']);

    $router->apiResource('types', MaintenanceTypeController::class);

    // Fees
    $router->apiResource('fees', FeesController::class);
});

Route::group(['prefix' => 'v3', 'middleware' => 'auth:sanctum'], function (Router $router) {
    $router->get('main-store/get-params', [V3MainStoreController::class, 'get_params']);
    $router->get('main-store/get-params-test', [V3MainStoreController::class, 'get_params_test']);
});
