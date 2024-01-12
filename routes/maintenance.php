<?php

use App\Http\Controllers\{
    BrandController,
    ColorController,
    IssueController,
    MaintenanceShopController,
    MaintenanceTypeController,
    ModelController,
};
use Illuminate\Support\Facades\Route;

Route::resource('', MaintenanceShopController::class)->parameter('', 'request')->except('show');
Route::get('{request}/types', [MaintenanceShopController::class, 'types'])->name('types');
Route::post('{request}/types', [MaintenanceShopController::class, 'store_new_type'])->name('types');
Route::get('{request}/types/{type}/edit', [MaintenanceShopController::class, 'types'])->name('types.edit');
Route::post('{request}/types/{type}/edit', [MaintenanceShopController::class, 'update_type'])->name('types.edit');
Route::get('{request}/types/{type}/delete', [MaintenanceShopController::class, 'delete_type'])->name('types.delete');

// Types
Route::resource('/types', MaintenanceTypeController::class);

// Brands
Route::resource('/brands', BrandController::class);

// Models
Route::resource('/models', ModelController::class);

// Colors
Route::resource('/colors', ColorController::class);

// Issues
Route::resource('/issues', IssueController::class);

// Orders
Route::get('/orders', [MaintenanceShopController::class, 'get_orders']);
