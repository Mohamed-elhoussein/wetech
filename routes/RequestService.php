<?php

use App\Http\Controllers\RequestServiceComtroller;

Route::get('requst/services',[RequestServiceComtroller::class,'index'])->name('requst/services');
Route::post('data/services',[RequestServiceComtroller::class,'data'])->name('data/services');

