<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware(['auth', 'superadmin'])->name('dashboard.')->prefix('dashboard')->group(function(){    
    Route::get('/', [DashboardController::class, 'index'])->name('main.index');
    
    Route::controller(ProductController::class)->group(function () {
        Route::get('product','index')->name('product.index');
        Route::get('product/delete/{id}','delete')->name('product.delete');
        Route::get('product/edit/{id}','edit')->name('product.edit');
        Route::post('product/update/{id}','update')->name('product.update');
        Route::get('product/create','create')->name('product.create');
        Route::post('product/store','store')->name('product.store');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('category','index')->name('category.index');
        Route::get('category/create','create')->name('category.create');
        Route::post('category/store','store')->name('category.store');
        Route::get('category/delete/{id}','delete')->name('category.delete');
        Route::get('category/edit/{id}','edit')->name('category.edit');
        Route::post('category/update/{id}','update')->name('category.update');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('user','index')->name('user.index');
        Route::get('user/create','create')->name('user.create');
        Route::post('user/store','store')->name('user.store');
        Route::get('user/delete/{id}','delete')->name('user.delete');
        Route::get('user/edit/{id}','edit')->name('user.edit');
        Route::post('user/update','update')->name('user.update');
    });

    Route::controller(BannerController::class)->group(function () {
        Route::get('banner','index')->name('banner.index');
        Route::get('banner/create','create')->name('banner.create');
        Route::post('banner/store','store')->name('banner.store');
        Route::get('banner/delete/{id}','delete')->name('banner.delete');
        Route::get('banner/edit/{id}','edit')->name('banner.edit');
        Route::post('banner/update/{id}','update')->name('banner.update');
    });
});
Auth::routes();

Route::redirect('/', 'dashboard');


