<?php

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

Route::get('/', function () {
    return view('welcome');
});

// Route::post('/login','App\Http\Controllers\AuthController@getLoginPage' )->name('showError');
// Route::get('/signup','App\Http\Controllers\CustomersController@getRegisterPage' );
// Route::post('/auth/login', 'App\Http\Controllers\AuthController@actionLogin');
// Route::post('/customers/register', 'App\Http\Controllers\CustomersController@actionRegister');
// Route::post('/customers/verify-otp', 'App\Http\Controllers\CustomersController@actionVerifyOtp');
// Route::post('/customers/profiles','App\Http\Controllers\CustomersProfileController@actionProfile');
// Route::get('/categories','App\Http\Controllers\CategoriesController@actionInterestCategory');
// Route::post('/customers/interests','App\Http\Controllers\CustomersController@actionCustomerInterest');




