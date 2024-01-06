<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('category/{id?}', [CategoryController::class,"fetch_api"])->name('category.api');
Route::get('product/{id?}', [ProductController::class,"fetch_api"])->name('product.api');
Route::get('banner/{id?}', [BannerController::class,"fetch_api"])->name('banner.api');

Route::controller(UserController::class)->group(function () {
    Route::post('login', [UserController::class, 'login_api'])->name('user.api.login');
    Route::post('signup', "signup_api")->name('user.api.signup');
});

Route::post('category-store', [CategoryController::class,"CategoryCreate"])->name('category.api');
Route::get('showcategory', [CategoryController::class, 'AllCategories'])->name('category.api');
Route::get('showcategory/{id}', [CategoryController::class, 'Categorybyid'])->name('category.api');
Route::delete('category-delete/{id}', [CategoryController::class, 'CategoryDeletebyid'])->name('category.api');
Route::put('category-update/{id}', [CategoryController::class, 'CategoryUpdatebyid'])->name('category.api');

Route::post('product-store', [ProductController::class,"ProductCreate"])->name('product.api');
Route::get('showproduct', [ProductController::class, 'AllProducts'])->name('product.api');
Route::get('showproduct/{id}', [ProductController::class, 'Productbyid'])->name('product.api');
Route::put('product-update', [ProductController::class, 'ProductUpdatebyid'])->name('product.api');
Route::delete('product-delete/{id}', [ProductController::class, 'ProductDeletebyid'])->name('product.api');

Route::post('banner-store', [BannerController::class,"BannerCreate"])->name('banner.api');
Route::get('showbanner', [BannerController::class, 'AllBanners'])->name('banner.api');
Route::get('showbanner/{id}', [BannerController::class, 'Bannerbyid'])->name('banner.api');
Route::put('banner-update', [BannerController::class, 'BannerUpdatebyid'])->name('banner.api');
Route::delete('banner-delete/{id}', [BannerController::class, 'BannerDeletebyid'])->name('banner.api');

Route::post('user-store', [UserController::class,"UserCreate"])->name('user.api');
Route::get('showuser', [UserController::class, 'AllUsers'])->name('user.api');
Route::get('showuser/{id}', [UserController::class, 'Userbyid'])->name('user.api');
Route::put('user-update/{id}', [UserController::class, 'UserUpdatebyid'])->name('user.api');
Route::delete('user-delete/{id}', [UserController::class, 'UserDeletebyid'])->name('user.api');
