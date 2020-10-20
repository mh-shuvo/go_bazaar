<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
//registration disable
Auth::routes([
	'register' => false
]);

Auth::routes();
Route::get('/', 'WebController@index')->name('index');
Route::get('/categories', 'WebController@categories')->name('web.categories');
Route::get('/product/list/{cat?}/{sub_cat?}/{price?}', 'WebController@product_list')->name('productList');
Route::get('/popular_category', 'WebController@popular_category')->name('popular_category');

Route::post('/product_search', 'WebController@product_search')->name('product_search');

Route::get('/product/cart/{id}', 'WebController@product_cart');
Route::post('order/check', 'WebController@check_order')->name('order.check');
Route::post('ocustomer/customer_save', 'WebController@customer_save')->name('customer.customer_save');

Route::post('customer/login', 'WebController@customer_login')->name('customer.login');

Route::post('customer/logout', 'WebController@customer_logout')->name('customer.logout');

Route::get('customer/forgot_password', 'WebController@customer_forgot_password')->name('customer.forgot_password');
Route::post('customer/forgot_password/otp_send', 'WebController@customer_fp_otp_send')->name('customer.forgot_password.otp_send');
Route::post('customer/forgot_password/otp_verify', 'WebController@customer_fp_otp_verify')->name('customer.forgot_password.otp_verify');
Route::post('customer/password_change', 'WebController@customer_password_change')->name('customer.forgot_password.password_change');

Route::get('/product/view/{id}', 'WebController@product_view');

Route::post('/productSearch', 'WebController@product_auto_search')->name('productSearch');

Route::get('/error', 'WebController@error')->name('error');

// cart
Route::post('add_cart_product', 'WebController@add_cart_product');
Route::post('remove_cart_product', 'WebController@remove_cart_product');
Route::get('product/cartlist', 'WebController@product_cart_list')->name('product.cart_list');
Route::get('/product/remove/{id}', 'WebController@product_remove_cart');
Route::get('/product/checkout', 'WebController@product_checkout')->name('product.checkout');

Route::post('/update_cart', 'WebController@product_cart_update')->name('product.update');

Route::post('/product/order', 'WebController@order_confirm')->name('product.order');

Route::post('/product/get_location', 'WebController@get_location')->name('get_location');

//Mehedi

Route::get('/contact', 'WebController@contact')->name('web.contact');
Route::post('contact/store', 'WebController@contactStore')->name('contact.store');

Route::post('/send_otp', 'WebController@sms_send')->name('web.sms_send');
Route::post('/check_otp', 'WebController@check_otp')->name('web.check_otp');

Route::get('/supplier/registration', 'WebController@supplier_registration')->name('web.supplier.registration');

Route::post('/supplier/registration', 'WebController@supplier_save')->name('web.supplier.registration');
Route::get('/supplier/list', 'WebController@supplier_list')->name('web.supplier.list');

// privacy policy page
Route::get('/privacy_policy', 'WebController@privacy_policy')->name('privacy_policy');

//for customer login verification
Route::group(['middleware' => ['verify.client']], function () {

	Route::get('customer/profile', 'WebController@customer_profile')->name('customer.profile');

	Route::get('customer/edit', 'WebController@customer_edit')->name('customer.edit');

	Route::post('customer/update', 'WebController@customer_update')->name('customer.update');

	Route::get('customer/orders', 'WebController@orders')->name('customer_orders');

	Route::get('customer/order_details/{id}', 'WebController@order_details')->name('orderDetails');

	Route::post('customer/order_reject', 'WebController@order_reject')->name('orderReject');
	Route::get('customer/order-tracking', 'WebController@order_tracking')->name('order_tracking');

	Route::post('customer/order-tracking/data', 'WebController@order_tracking_data')->name('order_tracking_data');


	Route::get('customer/wish_list/', 'WebController@wishListIndex')->name('customer.wish_list');
	Route::post('customer/wish_list/store', 'WebController@wishListStore')->name('customer.wish_list.store');
	Route::post('customer/wish_list/remove', 'WebController@removeWishListProduct')->name('customer.wish_list.remove');

	Route::get('customer/complain_box','WebController@complain_box')->name('web.complain_box');
	Route::post('customer/complain/submit','WebController@complain_submit')->name('web.complain_submit');
	Route::get('customer/complain_list','WebController@complain_list')->name('web.complain_list');
	Route::get('customer/complain/details/{id}','WebController@complain_details')->name('web.complain_details');
	Route::post('customer/complain/reply/submit','WebController@complain_reply_submit')->name('web.complain_reply_submit');
});

Route::post('supplier/forgot_password/otp_send', 'SuperAdminController@supplier_fp_otp_send')->name('supplier.forgot_password.otp_send');
Route::post('supplier/forgot_password/otp_verify', 'SuperAdminController@supplier_fp_otp_verify')->name('supplier.forgot_password.otp_verify');
Route::post('supplier/password_change', 'SuperAdminController@supplier_password_change')->name('supplier.forgot_password.password_change');


//All Admin route inside auth group
Route::middleware(['auth', 'client.check'])->group(function () {

	Route::get('/home', 'HomeController@index')->name('home');
	
	Route::get('/home/data', 'HomeController@homePageData')->name('home.data');
	
	//Shop login from super admin
	Route::get('/admin/impersonate/{id}','HomeController@impersonate')->name('impersonate');
	Route::get('/admin/imper/leave','HomeController@impersonateLeave')->name('impersonate.leave');
	
	//SuperAdminACL
	Route::get('super-admin/acl','SuperAdminController@aclIndex')->name('admin.acl');
	Route::get('super-admin/acl/list', 'SuperAdminController@acl_list')->name('admin.acl.list');
	Route::get('super-admin/acl/create', 'SuperAdminController@acl_create')->name('admin.acl.create');
	Route::post('super-admin/acl/create/action', 'SuperAdminController@acl_save')->name('admin.acl.create.action');
	Route::get('super-admin/acl/edit/{id}', 'SuperAdminController@acl_edit')->name('admin.acl.edit');
	Route::post('super-admin/acl/edit/action', 'SuperAdminController@acl_update')->name('admin.acl.edit.action');
	Route::post('super-admin/acl/delete', 'SuperAdminController@acl_delete')->name('admin.acl.delete');
	//location
	//get all location
	Route::post('/admin/get_location', 'SuperAdminController@get_location')->name('get_location');
	Route::get('/admin/location_list', 'SuperAdminController@location_list')->name('location_list');
	Route::post('/admin/location_store', 'SuperAdminController@location_store')->name('location_store');
	Route::post('/admin/location_update', 'SuperAdminController@location_update')->name('location_update');
	Route::post('/admin/location_delete', 'SuperAdminController@location_delete')->name('location_delete');

	//category and subcategory
	Route::get('/admin/category_list', 'SuperAdminController@category_list')->name('category_list');
	Route::post('/admin/category_store', 'SuperAdminController@category_store')->name('category_store');
	Route::post('/admin/category_update', 'SuperAdminController@category_update')->name('category_update');
	Route::post('/admin/category_delete', 'SuperAdminController@category_delete')->name('category_delete');
	Route::get('/admin/categores', 'SuperAdminController@categores')->name('categores');


	//Central User List
	Route::get('/admin/user-list','SuperAdminController@userList')->name('admin.user_list');
	Route::post('/admin/user/store','SuperAdminController@userStore')->name('admin.user.store');
	Route::post('/admin/user/edit','SuperAdminController@userEdit')->name('admin.user.edit');
	Route::post('/admin/user/delete','SuperAdminController@userDelete')->name('admin.user.delete');

	//Start Central Monitoring System Routes
	Route::get('/central/dashboard','CentralController@dashboard')->name('central.dashboard');
	Route::get('/central/customer_list','CentralController@customer_list')->name('central.customer_list');
	Route::get('/central/supplier_list','CentralController@supplier_list')->name('central.supplier_list');
	Route::get('/central/order_list','CentralController@order_list')->name('central.order_list');
	Route::get('/central/contact','CentralController@contact')->name('central.contact');
	Route::get('/central/complain','CentralController@complain')->name('central.complain');
	
	//End Central Monitoring System Routes


	//for CSV upload from super admin
	Route::get('/csv','ProductController@csvIndex')->name('csv');
	Route::post('/csv/upload', 'ProductController@ProductCSVUpload')->name('csv.upload');
	Route::post('/csv/check', 'ProductController@ProductCSVCheck')->name('csv.check');

	//for supplier type
	Route::get('/admin/supplier_type', 'SuperAdminController@supplier_type')->name('supplier_type');
	Route::post('/admin/supplier_type_store', 'SuperAdminController@supplier_type_store')->name('supplier_type_store');
	Route::post('/admin/supplier_type_update', 'SuperAdminController@supplier_type_update')->name('supplier_type_update');
	Route::post('/admin/supplier_type_delete', 'SuperAdminController@supplier_type_delete')->name('supplier_type_delete');
	
	//for ecommerce setup
	Route::get('/setup', 'HomeController@ecommerceSetup')->name('eccomerce_setup');
	Route::post('/setup/store', 'HomeController@ecommerceSetupStore')->name('eccomerce_setup.store');
	Route::post('/setup/edit', 'HomeController@ecommerceEdit')->name('eccomerce_setup.edit');
	Route::post('/setup/delete', 'HomeController@ecommerceDelete')->name('eccomerce_setup.delete');


	//for supplier
	Route::get('/admin/suppliers', 'SuperAdminController@suppliers')->name('suppliers');
	Route::get('/admin/supplier_add', 'SuperAdminController@supplier_add')->name('supplier_add');
	Route::post('/admin/supplier_store', 'SuperAdminController@supplier_store')->name('supplier_store');
	Route::get('/admin/supplier_edit/{id}', 'SuperAdminController@supplier_edit')->name('supplier_edit');
	Route::post('/admin/supplier_update', 'SuperAdminController@supplier_update')->name('supplier_update');
	Route::post('/admin/supplier_delete', 'SuperAdminController@supplier_delete')->name('supplier_delete');
	Route::post('/admin/supplier/status_change', 'SuperAdminController@statusChange')->name('supplier.status_change');
	Route::get('/supplier_profile', 'SuperAdminController@supplier_profile')->name('supplier_profile');
	Route::post('/supplier_profile_update', 'SuperAdminController@supplier_profile_update')->name('supplier_profile_update');


	//for delivery member type
	Route::get('/supplier/deliverymans', 'SupplierController@deliverymans')->name('deliverymans');

	Route::post('/supplier/deliveryman_store', 'SupplierController@deliveryman_store')->name('deliveryman_store');

	Route::post('/supplier/deliveryman_update', 'SupplierController@deliveryman_update')->name('deliveryman_update');

	Route::post('/supplier/deliveryman_delete', 'SupplierController@deliveryman_delete')->name('deliveryman_delete');

	Route::get('/delivery_man/list', 'SupplierController@delivery_man_list')->name('deliveryman.list');

	Route::post('/delivery_man/order/assign', 'SupplierController@delivery_man_order_assign')->name('deliveryman.order.assign');

	// ACL Module
	Route::get('/acl', 'SupplierController@acl')->name('acl');
	Route::get('/acl/list', 'SupplierController@acl_list')->name('acl.list');
	Route::get('/acl/create', 'SupplierController@acl_create')->name('acl.create');
	Route::post('/acl/create/action', 'SupplierController@acl_save')->name('acl.create.action');
	Route::get('/acl/edit/{id}', 'SupplierController@acl_edit')->name('acl.edit');
	Route::post('/acl/edit/action', 'SupplierController@acl_update')->name('acl.edit.action');
	Route::post('/acl/delete', 'SupplierController@acl_delete')->name('acl.delete');


	//manager & sales man
	Route::get('/supplier/employe', 'SupplierController@employe')->name('supplier.employe');
	Route::post('/supplier/employe/store', 'SupplierController@employeStore')->name('supplier.employe.store');
	Route::post('/supplier/employe/delete', 'SupplierController@employeDelete')->name('supplier.employe.delete');
	Route::get('/supplier/employe/sale_report','SupplierController@employeSaleReport')->name('employe.sale_report');
	Route::get('/supplier/employe/sale_report/download','SupplierController@employeSaleReportData')->name('employe.sale_report.download');
	Route::get('/supplier/employe/purchase_report','SupplierController@employePurchaseReport')->name('employe.purchase_report');
	Route::get('/supplier/employe/purchase_report/download','SupplierController@employePurchaseReportData')->name('employe.purchase_report.download');

	//for client
	Route::get('/admin/clients', 'SuperAdminController@clients')->name('clients');
	Route::post('/admin/client_delete', 'SuperAdminController@client_delete')->name('client_delete');

	//orders
	Route::get('/admin/orders', 'SuperAdminController@orders')->name('orders');
	Route::get('/admin/order_details/{order_id}', 'SuperAdminController@order_details')->name('order_details');
	Route::post('/get_shop_by_district','HomeController@get_shop_by_district')->name('get_shop_by_district');

	//password changes
	Route::post('/admin/password_changes', 'SuperAdminController@password_changes')->name('password_changes');
	Route::get('admin/contact', 'SuperAdminController@contact')->name('admin.contact');
	Route::get('admin/contact/data', 'SuperAdminController@contactData')->name('admin.contact.data');

	// MEHEDI

	Route::get('/product', 'ProductController@index')->name('supplier.product.index');
	Route::get('/product/data', 'ProductController@data')->name('supplier.product.data');
	Route::post('/product/store', 'ProductController@store')->name('supplier.product.store');
	Route::get('/product/edit', 'ProductController@edit')->name('supplier.product.edit');
	Route::get('/product/delete', 'ProductController@delete')->name('supplier.product.delete');
	Route::post('/product/image/delete', 'ProductController@image_delete')->name('supplier.product.image.delete');

	//product sale
	Route::get('/product_sale', 'ProductController@product_sale')->name('product_sale');
	Route::post('/product_fetch', 'ProductController@product_fetch')->name('product_fetch');

	Route::post('/sale_product_add', 'ProductController@sale_product_add')->name('sale_product_add');
	Route::post('/sale_product_list_update', 'ProductController@sale_product_list_update')->name('sale_product_list_update');
	Route::post('/internal_order_confirm', 'ProductController@internal_order_confirm')->name('internal_order_confirm');

	Route::get('/stock', 'InventoryController@index')->name('supplier.inventory.index');
	Route::get('/stock/data', 'InventoryController@data')->name('supplier.inventory.data');
	Route::post('/stock/store', 'InventoryController@store')->name('supplier.inventory.store');
	Route::post('/stock/edit', 'InventoryController@edit')->name('supplier.inventory.edit');
	Route::get('/stock/delete', 'InventoryController@delete')->name('supplier.inventory.delete');
	Route::get('/stock/report', 'InventoryController@report')->name('supplier.inventory.report');
	Route::get('/stock/report/data', 'InventoryController@stockData')->name('supplier.inventory.stockData');
	Route::get('/stock/report/download', 'InventoryController@DownloadStockReport')->name('download.stock_report');

	Route::get('/stock/barcode/bulk','InventoryController@BulkBarcode')->name('supplier.bulk_barcode');
	Route::post('/stock/barcode/bulk/download','InventoryController@DownloadBulkBarcode')->name('download.bulk_barcode');
	Route::post('GetProductByFiltering','InventoryController@GetProductByFiltering')->name('GetProductByFiltering');

	Route::get('/stock/opening', 'InventoryController@opening_stock')->name('stock.opening');
	Route::post('/stock/opening/action', 'InventoryController@opening_stock_action')->name('stock.opening.action');
	Route::post('/stock/opening/save', 'InventoryController@opening_stock_save')->name('stock.opening.save');

	Route::post('/profit_loss/report/download', 'ReportController@DownloadProfitLosskReport')->name('download.profit_loss_report');

	Route::POST('/stock/barcode', 'InventoryController@barcodeGenerate')->name('supplier.inventory.barcode');

	Route::get('/order', 'OrderController@index')->name('supplier.order.index');
	Route::get('/order/data', 'OrderController@data')->name('supplier.order.data');
	Route::get('/order/details/{order_id}', 'OrderController@orderDetails')->name('supplier.order.details');
	Route::get('/order/receipt/{order_id}', 'OrderController@orderReceipt')->name('supplier.order.receipt');
	Route::get('/order/status/', 'OrderController@orderStatusChange')->name('supplier.order.status_change');
	Route::post('/order/get_product_variation/', 'OrderController@getProductStockVariation')->name('supplier.order.getProductStockVariation');
	Route::post('/order/web/confirm', 'OrderController@WebOrderConfirm')->name('supplier.order.web.confirm');

	Route::get('/waste', 'InventoryController@productWaste')->name('supplier.product.waste');
	Route::get('/waste/data', 'InventoryController@productWasteData')->name('supplier.waste.data');
	Route::post('/waste/store', 'InventoryController@productWasteStore')->name('supplier.product.waste.store');
	Route::post('/waste/edit', 'InventoryController@WasteEdit')->name('supplier.waste.edit');
	Route::get('/waste/delete', 'InventoryController@WasteDelete')->name('supplier.waste.delete');

	Route::get('/report/product', 'ReportController@productReport')->name('supplier.report.product');
	Route::get('/report/product/data', 'ReportController@productReportData')->name('supplier.report.product.data');

	Route::get('/report/order', 'ReportController@orderReport')->name('report.order');
	Route::get('/report/profit-loss', 'ReportController@profitLoassReport')->name('report.profit_loss');
	Route::get('/report/profit-loss/data', 'ReportController@profitLoassReportData')->name('report.profit_loss.data');

	Route::get('/report/sale/daily', 'ReportController@dailySaleReport')->name('report.daily_sale');
	Route::get('/report/sale/monthly', 'ReportController@monthlySaleReport')->name('report.monthly_sale');
	Route::get('/report/sale/data', 'ReportController@saleReportData')->name('report.sale.data');
	Route::get('/report/order/download', 'ReportController@orderReportDownload')->name('report.order.download');

	Route::get('/report/balance_statement', 'ReportController@BalanceStatement')->name('report.balance_statement');

	Route::get('/report/balance_statement/download', 'ReportController@DownloadBalanceStatement')->name('report.balance_statement.download');

	Route::get('/report/salesman','ReportController@salesman')->name('report.salesman');
	Route::get('/report/salesman/download','ReportController@downloadSalesmanReport')->name('report.salesman.download');

	Route::post('/getSubCategoryByCategory', 'ProductController@getSubCategoryByCategory')->name('getSubCategoryByCategory');
	Route::post('/getProduct', 'ProductController@getProduct')->name('getProduct');

	Route::get('/supplier/client', 'ClientController@index')->name('supplier.client.index');
	Route::post('/supplier/client/data', 'ClientController@data')->name('supplier.client.data');
	Route::post('/supplier/client/store', 'ClientController@store')->name('supplier.client.store');
	Route::post('/supplier/client/edit/{id}', 'ClientController@edit')->name('supplier.client.edit');
	Route::post('/supplier/client/delete/{id}', 'ClientController@delete')->name('supplier.client.delete');

	Route::post('/getStockByProductId', 'ProductController@getStockByProductId');


	Route::get('delivery/orders', 'DeliveryManController@orders')->name('delivery.orders');
	Route::get('delivery/orders/data', 'DeliveryManController@ordersData')->name('delivery.orders.data');

	Route::post('delivery/order/status/', 'DeliveryManController@orderStatusChange')->name('delivery.order.status_change');
	Route::get('delivery/order_details/{id}', 'DeliveryManController@order_details')->name('delivery.order_details');

	Route::get('autocomplete', 'ProductController@search');


	// Expense Management

	Route::get('/expense/account-head', 'ExpenseController@AccountHeadList')->name('expense.account_head');
	Route::post('/expense/account-head/store', 'ExpenseController@AccountHeadStore')->name('expense.account_head.store');
	Route::post('/expense/account-head/delete', 'ExpenseController@AccountHeadDelete')->name('expense.account_head.delete');

	Route::get('/expense/entry', 'ExpenseController@EntryList')->name('expense.entry');
	Route::post('/expense/entry/store', 'ExpenseController@EntryStore')->name('expense.entry.store');
	Route::post('/expense/entry/delete', 'ExpenseController@EntryDelete')->name('expense.entry.delete');
	Route::get('/expense/report', 'ExpenseController@Report')->name('expense.report');
	Route::get('/expense/report/download', 'ExpenseController@DownloadReport')->name('expense.report.download');


	//offer management

	Route::get('/supplier/offer/', 'ProductController@offer')->name('supplier.offer');
	Route::get('/supplier/offer/data', 'ProductController@offerData')->name('supplier.offer.data');
	Route::post('/supplier/offer/store', 'ProductController@offerStore')->name('supplier.offer.store');
	Route::post('/supplier/offer/delete', 'ProductController@offerDelete')->name('supplier.offer.delete');

	Route::get('/supplier/support','HomeController@support')->name('supplier.support');

	Route::get('/supplier/complain','HomeController@complain')->name('supplier.complain');
	Route::get('/supplier/complain/reply/{id}','HomeController@complain_reply')->name('supplier.complain_reply');
	Route::post('/supplier/complain/reply/submit','HomeController@complain_reply_submit')->name('supplier.complain_reply_submit');
	Route::get('/admin/complain','SuperAdminController@complain')->name('admin.complain');
	Route::get('/admin/complain/{id}','SuperAdminController@singleComplain')->name('admin.complain.single');

});
