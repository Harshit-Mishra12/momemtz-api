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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    // Route::get('/loginPage','App\Http\Controllers\AuthController@getLoginPage')->name('login');
    Route::post('/login','App\Http\Controllers\AuthController@login');
    Route::post('/me', 'App\Http\Controllers\AuthController@me');
    Route::post('/resetPassword','App\Http\Controllers\CustomersController@actionResetPassword');
    Route::get('/signup','App\Http\Controllers\CustomersController@getRegisterPage' );
    Route::post('/customers/register', 'App\Http\Controllers\CustomersController@actionRegister');
    Route::post('/customers/verify-otp', 'App\Http\Controllers\CustomersController@actionVerifyOtp');
    Route::post('/customers/profiles','App\Http\Controllers\CustomersProfileController@actionProfile');
    Route::get('/interestCategories','App\Http\Controllers\CategoriesController@actionInterestCategory');
    Route::post('/customers/interests','App\Http\Controllers\CustomersController@actionCustomerInterest');
    Route::post('/mailNewPassword','App\Http\Controllers\CustomersController@actionForgetPassword');

    //events api
    Route::post('/createEvent','App\Http\Controllers\EventsController@createEvent');
    Route::get('/events/id={customerId}','App\Http\Controllers\EventsController@fetchEvents');
    Route::post('/events/id={customerId}','App\Http\Controllers\EventsController@updateEvents');
    Route::get('/fetchAllevents','App\Http\Controllers\EventsController@fetchAllEvents');
    Route::post('/events/{eventId}/tickets','App\Http\Controllers\EventsController@createEventTicket');
    Route::get('/events/{eventId}/tickets','App\Http\Controllers\EventsController@fetchEventTicket');
    Route::post('/events/{eventId}/dress-code','App\Http\Controllers\EventsController@createEventDresscode');
    Route::get('/events/{eventId}/dress-code','App\Http\Controllers\EventsController@fetchEventDresscode');
 
    //end events api 
});
Route::get('/errorMessagePage','App\Http\Controllers\Controller@ShowAuthenticationError')->name('login');
// Route::post('/login','App\Http\Controllers\AuthController@login');
// Route::post('/me', 'App\Http\Controllers\AuthController@me');
// Route::post('/resetPassword','App\Http\Controllers\CustomersController@actionResetPassword');
// Route::get('/signup','App\Http\Controllers\CustomersController@getRegisterPage' );
// Route::post('/customers/register', 'App\Http\Controllers\CustomersController@actionRegister');
// Route::post('/customers/verify-otp', 'App\Http\Controllers\CustomersController@actionVerifyOtp');
// Route::post('/customers/profiles','App\Http\Controllers\CustomersProfileController@actionProfile');
// Route::get('/interestCategories','App\Http\Controllers\CategoriesController@actionInterestCategory');
// Route::post('/customers/interests','App\Http\Controllers\CustomersController@actionCustomerInterest');
// Route::post('/mailNewPassword','App\Http\Controllers\CustomersController@actionForgetPassword');

// //events api
// Route::post('/createEvent','App\Http\Controllers\EventsController@createEvent');
// Route::get('/events/id={customerId}','App\Http\Controllers\EventsController@fetchEvents');
// Route::post('/events/id={customerId}','App\Http\Controllers\EventsController@updateEvents');
// Route::get('/fetchAllevents','App\Http\Controllers\EventsController@fetchAllEvents');
// Route::post('/events/{eventId}/tickets','App\Http\Controllers\EventsController@createEventTicket');
// Route::get('/events/{eventId}/tickets','App\Http\Controllers\EventsController@fetchEventTicket');
// Route::post('/events/{eventId}/dress-code','App\Http\Controllers\EventsController@createEventDresscode');
// Route::get('/events/{eventId}/dress-code','App\Http\Controllers\EventsController@fetchEventDresscode');

// Route::resource('/categories','App\Http\Controllers\CustomersController@getRegisterPage' )->middleware('jwt.auth');