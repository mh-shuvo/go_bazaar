<?php
use Illuminate\Http\Request;
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

Route::get('/',function(Request $request){

	return response()->json(
		[
			"status" => "success", 
			"message" => "Successfully data found.", 
			"data" => [
				"attempt_time" => date("Y-m-d H:i:s"),
				"client_id" => $request->header('CLIENT-ID'),
				"token" => $request->header('ACCESS-TOKEN'),
				"app_key" => $request->header('APP-KEY'),
				"type" => $request->header('TYPE'),
			]
		]
		,200);
});
Route::post('/login','ApiAuth\UserController@login');
Route::post('/client_registration', 'ApiAuth\UserController@client_registration');

Route::post('/supplier_registration', 'ApiAuth\UserController@supplier_registration');

Route::post('/get_location', 'ApiController@get_location'); // if send parent_id with type so it can be pass all union of a specific upazila

Route::post('/get_supplier_type', 'ApiController@getSupplierType'); // if you want specific type so you can pass id parameter which is contain a supplier type id

Route::get('/home/categories','ApiController@get_menu_category');
Route::get('/home/products','ApiController@get_products');
Route::get('/home/featured_products','ApiController@get_feature_product');
Route::get('/menu','ApiController@get_menu_category');
Route::get('/home/slider','ApiController@get_slider_product');

Route::get('/product/{id}', 'ApiController@product_view');

Route::get('/product/list/{cat?}/{sub_cat?}/{price?}', 'ApiController@product_list');

Route::get('/latest/product', 'ApiController@latest_product');

Route::post('/send_otp', 'ApiController@sms_send')->middleware('api.auth');

Route::get('/client/profile', 'ApiController@customer_profile')->middleware('api.auth');
Route::POST('/client/profile/update', 'ApiAuth\UserController@client_update')->middleware('api.auth');
Route::POST('/client/password_change','ApiAuth\UserController@password_change')->middleware('api.auth');

Route::get('/client/orders', 'ApiController@orders')->middleware('api.auth');
Route::get('/client/order/{id}', 'ApiController@order_details')->middleware('api.auth');

Route::post('/client/order_reject', 'ApiController@order_reject')->middleware('api.auth'); //pass order id by post method

Route::post('/order/submit','ApiController@api_order_submit')->middleware('api.auth');