<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    // Route::get('/login','App\Http\Controllers\AuthController@getLoginPage' );
    Route::post('/login','App\Http\Controllers\AuthController@login');
    Route::post('/me', 'App\Http\Controllers\AuthController@me');
    Route::get('/signup','App\Http\Controllers\CustomersController@getRegisterPage' );
    Route::post('/customers/register', 'App\Http\Controllers\CustomersController@actionRegister');
    Route::post('/customers/verify-otp', 'App\Http\Controllers\CustomersController@actionVerifyOtp');
    Route::post('/customers/profiles','App\Http\Controllers\CustomersProfileController@actionProfile');
    Route::get('/categories','App\Http\Controllers\CategoriesController@actionInterestCategory');
    Route::post('/customers/interests','App\Http\Controllers\CustomersController@actionCustomerInterest');
   
});

// Route::resource('/categories','App\Http\Controllers\CustomersController@getRegisterPage' )->middleware('jwt.auth');