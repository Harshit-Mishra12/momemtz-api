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

    //vendor profile
    Route::post('/vendors/profiles','App\Http\Controllers\CustomersProfileController@actionVendorProfile');
    //events api
    Route::post('/createEvent','App\Http\Controllers\Event\EventsController@createEvent');
    Route::get('/events/id={customerId}','App\Http\Controllers\Event\EventsController@fetchEvents');
    Route::put('/events/id={eventId}','App\Http\Controllers\Event\EventsController@updateEvents');
    Route::get('/fetchAllevents','App\Http\Controllers\Event\EventsController@fetchAllEvents');
    Route::post('/publishEvent/id={eventId}','App\Http\Controllers\Event\EventsController@actionPublishEvent');
    
    // delete event api
    Route::delete('/deleteAnEvent/id={eventId}','App\Http\Controllers\Event\EventsController@deleteAnEvent');

    //fetch events
    Route::get('/fetchAllActiveEvents','App\Http\Controllers\Event\EventsController@fetchAllActiveEvent');
    Route::get('/fetchActiveEvents/id={customerId}','App\Http\Controllers\Event\EventsController@fetchActiveEvent');
    Route::get('/fetchUpcomingEvents','App\Http\Controllers\Event\EventsController@fetchUpcomingEvent');
    Route::get('/fetchCompletedEvent/id={customerId}','App\Http\Controllers\Event\EventsController@fetchCompletedEvent');
    Route::get('/fetchUnpublishEvent/id={customerId}','App\Http\Controllers\Event\EventsController@fetchUnpublishEvent');
    
    
    //event ticket api
    Route::post('/events/{eventId}/tickets','App\Http\Controllers\Event\EventDashboard\TicketController@createEventTicket');
    Route::get('/events/{eventId}/tickets','App\Http\Controllers\Event\EventDashboard\TicketController@fetchEventTicket');
    Route::put('/events/{ticketId}/tickets','App\Http\Controllers\Event\EventDashboard\TicketController@updateEventTicket');
    
    //event dreescode api
    Route::post('/events/{eventId}/dress-code','App\Http\Controllers\Event\EventDashboard\DressCodeController@createEventDresscode');
    Route::get('/events/{eventId}/dress-code','App\Http\Controllers\Event\EventDashboard\DressCodeController@fetchEventDresscode');
    
    //event wishlist api
    Route::get('/events/{eventId}/wishlist','App\Http\Controllers\Event\EventDashboard\WishlistController@fetchEventWishlist');
    Route::post('/events/{eventId}/wishlist','App\Http\Controllers\Event\EventDashboard\WishlistController@createEventWishList');
    Route::put('/events/{wishlistId}/wishlist','App\Http\Controllers\Event\EventDashboard\WishlistController@updateEventWishlist');
    //end events api 


    //explore events
    Route::get('/exploreCustomerEvents/id={customerId}','App\Http\Controllers\Explore\ExploreEventController@fetchExploreCustomerEvents');
    Route::get('/exploreNearbyEvents','App\Http\Controllers\Explore\ExploreEventController@fetchExploreNearbyEvents');
    Route::get('/fetchExploreEventDetails/id={eventId}','App\Http\Controllers\Explore\ExploreEventController@fetchExploreEventDetails');
   
    Route::post('/exploreFetchAllEventFilter','App\Http\Controllers\Explore\ExploreEventFilterController@ExploreEventFilter');
       
    //vendor product
    
    Route::post('/createProduct/id={vendorId}','App\Http\Controllers\Product\ProductController@createProduct');
    Route::get('/fetchProduct/id={vendorId}','App\Http\Controllers\Product\ProductController@fetchProduct');
    Route::get('/fetchOrder/id={vendorId}','App\Http\Controllers\Product\ProductController@fetchOrder');
    
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