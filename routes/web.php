<?php

use Illuminate\Support\Facades\Route;

//import ProdukController
use App\Http\Controllers\ProdukController;

//memberikan akses web ke ProdukController
Route::resource('/produk',ProdukController::class);

Route::get('/', function () {
    return view('welcome');
});
