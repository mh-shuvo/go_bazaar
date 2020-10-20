<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\Sms;
use App\Client;
use App\Contact;
use App\Location;
use App\Wishlist;
use App\User;
use App\Order;
use App\Product;
use App\Supplier;
use App\SupplierType;
use Session;
use Image;
use Route;
use Response;


class ApiController extends Controller
{
	use Sms;
	public function get_menu_category()
	{

		$menu_category = DB::table('categories AS MC')
			->leftJoin("categories AS SC", "MC.id", "=", "SC.parent_id")
			->select('MC.id AS m_id', 'MC.parent_id AS m_parent_id', 'MC.name AS m_name', 'MC.icon AS m_icon', 'SC.id AS s_id', 'SC.name AS s_name', 'SC.icon AS s_icon', 'SC.parent_id AS s_parent_id')
			->where([
				['MC.is_menu', '=', 1],
			])
			->whereNull("MC.deleted_at")
			->orderBy('MC.sorting')
			->get();


		$menu_list = [];

		foreach ($menu_category as $val) {
			if (isset($menu_list[$val->m_id])) {
				if ($val->s_parent_id) {
					$menu_list[$val->m_id]['sub_menu'][] = [
						"id" => $val->s_id,
						"name" => $val->s_name,
						"icon" => $val->s_icon
					];
				}
			} else {
				$menu_list[$val->m_id] = [
					"id" => $val->m_id,
					"name" => $val->m_name,
					"icon" => $val->m_icon,
					"sub_menu" => []
				];

				if ($val->s_parent_id) {
					$menu_list[$val->m_id]["sub_menu"][] = [
						[
							"id" => $val->s_id,
							"name" => $val->s_name,
							"icon" => $val->s_icon
						]
					];
				}
			}
		}

		// dd($menu_list);

		return response()->json(
			[
				'status' => 'success',
				'message' => 'Data Found',
				'data' => [
					'menu' => $menu_list
				]
			]
		);
	}

	public function get_products()
	{
		$menu_category = DB::table('categories')
			->select('id', 'name', 'is_show', 'icon')
			->where([
				['type', '=', 1],
				// ['is_show', '=', 1],
			])
			->whereNull("deleted_at")
			->orderBy('sorting')
			->get();

		//get all feature product
		$feature_product = $this->feature_product();

		$product_info = [];

		foreach ($menu_category as $item) {
			if ($item->is_show == 1) {

				$product_list = DB::table('inventories AS INV')
					->join('products AS PRD', function ($join) use ($item) {
						$join->on('INV.product_id', '=', 'PRD.id')
							->where('INV.type', '=', 1)
							->where('PRD.category_id', '=', $item->id)
							->whereNull("PRD.deleted_at");
					})
					->join('product_rate AS PRDR', function ($join) use ($item) {
						$join->on('PRDR.product_id', '=', 'PRD.id')
							->where('PRD.category_id', '=', $item->id)
							->whereNull("PRDR.deleted_at");
					})
					->join('units AS UNT', function ($join) {
						$join->on('UNT.id', '=', 'PRD.unit_id');
					})
					->join('suppliers AS SPLR', function ($join) {
						$join->on('SPLR.id', '=', 'PRD.supplier_id')
							->whereNull("SPLR.deleted_at");
					})
					->join('locations AS UPZ', function ($join) {
						$join->on('UPZ.id', '=', 'SPLR.upazila_id')
							->whereNull("SPLR.deleted_at");
					})
					->join('locations AS UNI', function ($join) {
						$join->on('UNI.id', '=', 'SPLR.union_id')
							->whereNull("SPLR.deleted_at");
					})
					->select('INV.id', 'INV.product_id', 'PRDR.rate', 'PRD.name as product_name', 'PRD.picture', 'PRD.category_id', 'UNT.name as unit_name', 'UPZ.name as upazila_name', 'UNI.name as union_name', DB::raw('SUM(INV.credit) AS total_credit'), DB::raw('SUM(INV.debit) AS total_debit'))
					->where('PRD.category_id', '=', $item->id)
					->whereNull('PRD.deleted_at')
					->groupBy("INV.product_id")
					->orderByDesc('PRD.created_at')
					->limit(10)
					->get();

				$product_info[] = [
					"category_id" => $item->id,
					"category_name" => $item->name,
					"category_icon" => $item->icon,
					"product_list" => $product_list
				];
			}
		}

		$message = "Data Found";
		if (count($product_info) <= 0) {
			$message = "Data Not Found";
		}
		return response()->json(
			[
				'status' => 'success',
				'message' => 'Data Found',
				'data' => [
					'products' => $product_info,
				]
			]
		);
	}
	public function get_slider_product()
	{

		$slider = [
			[
				"name" => "slider7",
				"picture" => "slider7.jpg"
			],
			[
				"name" => "slider6",
				"picture" => "slider6.jpg"
			],
			// [
			// 	"name" => "slider5",
			// 	"picture" => "slider5.jpg"
			// ],
			[
				"name" => "slider1",
				"picture" => "slider1.jpg"
			],
			[
				"name" => "slider3",
				"picture" => "slider3.jpg"
			],
			// [
			// 	"name" => "slider2",
			// 	"picture" => "slider2.jpg"
			// ],
			[
				"name" => "slider4",
				"picture" => "slider4.jpg"
			]
		];

		return response()->json(
			[
				'status' => 'success',
				'message' => 'Data Found',
				'data' => [
					'slider' => $slider,
				]
			]
		);
	}
	public function get_feature_product()
	{
		$feature_product = $this->feature_product();

		$message = "Data Found";
		if (count($feature_product) <= 0) {
			$message = "Data Not Found";
		}
		return response()->json(
			[
				'status' => 'success',
				'message' => 'Data Found',
				'data' => [
					'categories' => $feature_product,
				]
			]
		);
	}

	public function feature_product()
	{

		$feature_product = DB::table('categories AS CTG')

			->select('CTG.id', 'CTG.name', 'CTG.icon', 'PRD.picture')

			->join('products AS PRD', function ($join) {

				$join->on('PRD.category_id', '=', 'CTG.id');
				// ->on('PRD.sub_category_id', '=', 'CTG.id');
			})

			->where([
				['type', '=', 1],
				// ['is_show', '=', 1],
				['is_feature', '=', 1],
			])
			->whereNull("CTG.deleted_at")
			->groupBy('PRD.category_id')
			// ->groupBy('PRD.sub_category_id')
			->take(20)
			->orderBy('CTG.sorting')
			->get();

		return $feature_product;
	}
	public function get_location(Request $request)
	{

		//get location
		$response = Location::get_all_location($request);

		if (!empty($response)) {
			return response()->json(
				[
					"status" => "success",
					"message" => "Location Found",
					"data" => $response
				],
				200
			);
		} else {
			return response()->json(
				[
					"status" => "error",
					"message" => "Location Not Found",
					"data" => []
				],
				200
			);
		}
	}

	public function getSupplierType(Request $request)
	{
		if (isset($request->id) && $request->id != 0) {
			$data = SupplierType::find($request->id);
		} else {
			$data = SupplierType::all();
		}


		return response()->json(
			[
				"status" => "success",
				"message" => "Supplier Type Found",
				"data" => [
					'supplier_types' => $data
				]
			],
			200
		);
	}

	public function product_view($product_id)
	{


		$menu_category = DB::table('categories')
			->select('id', 'name', 'is_show', 'icon')
			->where([
				['type', '=', 1],
				// ['is_show', '=', 1],
			])
			->whereNull("deleted_at")
			->orderBy('sorting')
			->get();

		// DB::enableQueryLog();

		$get_product_info = DB::table('inventories AS INV')
			->join('products AS PRD', function ($join) use ($product_id) {
				$join->on('INV.product_id', '=', 'PRD.id')
					->where('INV.type', '=', 1)
					->where('PRD.id', '=', $product_id)
					->whereNull("PRD.deleted_at");
			})
			->join('product_rate AS PRDR', function ($join) use ($product_id) {
				$join->on('PRDR.product_id', '=', 'PRD.id')
					->where('PRD.id', '=', $product_id)
					->whereNull("PRDR.deleted_at");
			})
			->join('units AS UNT', function ($join) {
				$join->on('UNT.id', '=', 'PRD.unit_id');
			})
			->join('suppliers AS SPLR', function ($join) {
				$join->on('SPLR.id', '=', 'PRD.supplier_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('locations AS UPZ', function ($join) {
				$join->on('UPZ.id', '=', 'SPLR.upazila_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('locations AS UNI', function ($join) {
				$join->on('UNI.id', '=', 'SPLR.union_id')
					->whereNull("SPLR.deleted_at");
			})

			->select('INV.id', 'INV.product_id', 'PRDR.rate', 'PRD.name as product_name', 'PRD.picture', 'PRD.description', 'PRD.category_id', 'UNT.name as unit_name', 'UPZ.name as upazila_name', 'UNI.name as union_name', 'SPLR.name as supplier_name', 'INV.supplier_id', 'SPLR.mobile as supplier_mobile', 'SPLR.id as supplier_id', 'SPLR.shop_name', 'SPLR.address as supplier_address')
			->where([
				['INV.product_id', '=', $product_id],
			])
			->first();

		// dd($get_product_info);
		// exit;

		$category_id = $get_product_info->category_id;

		// get product
		$get_product = DB::table('inventories AS INV')
			->join('products AS PRD', function ($join) use ($category_id) {
				$join->on('INV.product_id', '=', 'PRD.id')
					->where('INV.type', '=', 1)
					->where('PRD.category_id', '=', $category_id)
					->whereNull("PRD.deleted_at");
			})
			->join('product_rate AS PRDR', function ($join) use ($category_id) {
				$join->on('PRDR.product_id', '=', 'PRD.id')
					->where('PRD.category_id', '=', $category_id)
					->whereNull("PRDR.deleted_at");
			})
			->join('units AS UNT', function ($join) {
				$join->on('UNT.id', '=', 'PRD.unit_id');
			})
			->join('suppliers AS SPLR', function ($join) {
				$join->on('SPLR.id', '=', 'PRD.supplier_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('locations AS UPZ', function ($join) {
				$join->on('UPZ.id', '=', 'SPLR.upazila_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('locations AS UNI', function ($join) {
				$join->on('UNI.id', '=', 'SPLR.union_id')
					->whereNull("SPLR.deleted_at");
			})
			->select('INV.id', 'INV.product_id', 'PRDR.rate', 'PRD.name as product_name', 'PRD.picture', 'PRD.category_id', 'UNT.name as unit_name', 'UPZ.name as upazila_name', 'UNI.name as union_name', DB::raw('SUM(INV.credit) AS total_credit'), DB::raw('SUM(INV.debit) AS total_debit'))
			->where('PRD.category_id', '=', $category_id)
			->whereNull('PRD.deleted_at')
			->groupBy("INV.product_id")
			->limit(10)
			->get();

		$supplier_id = $get_product_info->supplier_id;

		$credit_stock = DB::table('inventories')
			->select(DB::raw('SUM(credit) AS total_credit'))
			->where('supplier_id', '=', $supplier_id)
			->where('product_id', '=', $product_id)
			->where('type', '=', 1)
			->whereNull('order_id')
			->groupBy('product_id')
			->first();

		$debit_stock = DB::table('inventories')
			->select(DB::raw('SUM(debit) AS total_debit'))
			->where('supplier_id', '=', $supplier_id)
			->where('product_id', '=', $product_id)
			->where('type', '=', 2)
			->whereNotNull('order_id')
			->groupBy('product_id')
			->first();

		$total_credit = isset($credit_stock->total_credit) ? $credit_stock->total_credit : 0;
		$total_debit = isset($debit_stock->total_debit) ? $debit_stock->total_debit : 0;

		$total_stock = ($total_credit - $total_debit);

		$cart_list = session('cart_product');

		$add_to_cart = false;
		$cart_quantity = 1;

		if (!empty($cart_list)) {
			foreach ($cart_list as $item) {
				if ($item['id'] == $product_id) {
					$add_to_cart = true;
					$cart_quantity = $item['quantity'];
				}
			}
		}

		// dd($add_to_cart);
		return response()->json(
			[
				'status' => 'success',
				'message' => 'Data Found',
				'data' => [
					'product' => $get_product_info,
				]
			]
		);
	}

	//gel all menu
	public function menu_category()
	{

		return DB::table('categories')
			->select('id', 'name', 'is_show', 'icon')
			->where([
				['type', '=', 1],
				['is_menu', '=', 1],
				['deleted_at', '=', NULL],
			])
			->orderBy('sorting')
			->get();
	}

	public function product_list($cat_id = 0, $sub_cat_id = 0, $price = null)
	{

		//get all menu
		$menu_category = $this->menu_category();

		$price_limit = 0;

		$get_product = DB::table('inventories AS INV')
			->join('products AS PRD', function ($join) use ($cat_id) {
				$join->on('INV.product_id', '=', 'PRD.id')
					->where('PRD.category_id', '=', $cat_id)
					->whereNull("PRD.deleted_at");
			})
			->join('product_rate AS PRDR', function ($join) use ($cat_id) {
				$join->on('PRDR.product_id', '=', 'PRD.id')
					->where('PRD.category_id', '=', $cat_id)
					->whereNull("PRDR.deleted_at");
			})
			->join('suppliers AS SPLR', function ($join) {
				$join->on('SPLR.id', '=', 'PRD.supplier_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('locations AS UPZ', function ($join) {
				$join->on('UPZ.id', '=', 'SPLR.upazila_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('locations AS UNI', function ($join) {
				$join->on('UNI.id', '=', 'SPLR.union_id')
					->whereNull("SPLR.deleted_at");
			})
			->select('INV.id', 'INV.product_id', 'PRDR.rate', 'PRD.name as product_name', 'PRD.picture', 'PRD.category_id','UPZ.name as upazila_name', 'UNI.name as union_name','SPLR.shop_name as shop_name', DB::raw('SUM(INV.credit) AS total_credit'), DB::raw('SUM(INV.debit) AS total_debit'))
			->where('INV.status','=',0)
			->orWhere('INV.status','=',2)
			->whereNull('PRD.deleted_at')
			->groupBy("INV.product_id");

		if ($sub_cat_id > 0) {

			$get_product->where('PRD.sub_category_id', $sub_cat_id);
		}

		if ($price != null) {
			$price = explode('_', $price);

			$price_limit = (float) $price[1];

			$get_product->whereBetween('PRDR.rate', [(float) $price[0], (float) $price[1]]);
		}



		if (!empty(request()->header('APP-KEY'))) {
			$get_product = $get_product->orderByDesc('PRD.created_at')->get();
		} else {
			$get_product = $get_product->orderByDesc('PRD.created_at')->paginate(8);
		}


		// dd($get_product);

		$get_sub_category = DB::table('categories')
			->select('id', 'parent_id', 'name')
			->where([
				['parent_id', '=', $cat_id],
				['type', '=', 2],
			])
			->orderBy('sorting')
			->whereNull('deleted_at')
			->orderByDesc('created_at')
			->get();

		return response()->json([
			'status' => 'success',
			'message' => 'Data Found',
			'data' => [
				'products' => $get_product,
				'categories' => $menu_category,
				'sub_categories' => $get_sub_category,
				'category_id' => $cat_id,
				'sub_category_id' => $sub_cat_id,


			],
		], 200);
	}

	public function latest_product()
	{
		$product_list = DB::table('inventories AS INV')
			->join('products AS PRD', function ($join) {
				$join->on('INV.product_id', '=', 'PRD.id')
					->where('INV.type', '=', 1)
					->whereNull("PRD.deleted_at");
			})
			->join('product_rate AS PRDR', function ($join) {
				$join->on('PRDR.product_id', '=', 'PRD.id')
					->whereNull("PRDR.deleted_at");
			})
			->join('units AS UNT', function ($join) {
				$join->on('UNT.id', '=', 'PRD.unit_id');
			})
			->join('suppliers AS SPLR', function ($join) {
				$join->on('SPLR.id', '=', 'PRD.supplier_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('locations AS UPZ', function ($join) {
				$join->on('UPZ.id', '=', 'SPLR.upazila_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('locations AS UNI', function ($join) {
				$join->on('UNI.id', '=', 'SPLR.union_id')
					->whereNull("SPLR.deleted_at");
			})
			->select('INV.id', 'PRD.category_id', 'UPZ.name as upazila_name', 'UNI.name as union_name', 'PRD.name as product_name', 'PRD.picture', 'PRDR.rate', DB::raw('SUM(INV.credit)-SUM(INV.debit) AS total_stock'))
			->whereNull('PRD.deleted_at')
			->groupBy("INV.supplier_id")
			->orderBy("INV.id")
			->limit(6)
			->get();

			if(empty($product_list)){
				$product_list = [];
			}

			return response()->json(["status" => "success", "message" => "Data Found.", "data" => $product_list]);

			// dd($product_list);

	}

	public function sms_send(Request $request)
	{
		$otp_code = mt_rand(100000, 999999);
		$mobile = $request->phone;
		$msg = "Your verification code is " . $otp_code . ' valid for 1 minute. Gobazaar.com.bd. Thank you.';

		$response = Sms::sendSms($mobile,$msg);

		if ($response == 1) {
			$status = 'success';
			$api_status = 'success';
			$message = "OTP Successfully Send";
			$status_code = 200;
			session(['otp_code' => $otp_code]);
		} else {
			$status = 'fail';
			$api_status = 'success';
			$status_code = 200;
			$message = "OTP Not Send";
		}

		if (!empty($request->header('APP-KEY'))) {
			return response()->json([
				'status' => $api_status,
				'message' => $message,
				'data' => [],
				'otp_code' => $otp_code
			], $status_code);
		} else {
			return ['status' => $status];
		}
	}


	//customer profile and edit
	public function customer_profile()
	{
		$client_id = (int) request()->header('CLIENT-ID');

		//get all menus
		$menu_category = $this->menu_category();

		//get client data
		$client_data = DB::table('users AS UGS')

			->select('UGS.id as user_id', 'UGS.username', 'CLNT.id as client_id', 'CLNT.name', 'CLNT.mobile', 'CLNT.email', 'CLNT.address', 'CLNT.photo', 'CLNT.porosova', 'CLNT.ward', 'CLNT.corona_zone', 'LOC1.id as upazila_id', 'LOC1.name as upazila_name', 'LOC2.id as union_id', 'LOC2.name as union_name')

			->join('clients AS CLNT', function ($join) {

				$join->on('CLNT.id', '=', 'UGS.record_id')
					->where('UGS.user_type', 3);
			})

			->leftjoin('locations AS LOC1', 'LOC1.id', '=', 'UGS.upazila_id')
			->leftjoin('locations AS LOC2', 'LOC2.id', '=', 'UGS.union_id')

			->where('CLNT.id', $client_id)

			->first();

		// dd($client_data);
		// exit;

		if (!empty($client_data)) {
			$api_status = 'success';
			$api_message = 'Client Data Found';
			$api_status_code = 200;
		} else {
			$api_status = 'error';
			$api_message = 'Client Data Not Found';
			$api_status_code = 404;
		}

		//get all upzila			
		$upazila = Location::get_all_location();

		$union = [];

		if (!empty($client_data) && $client_data->upazila_id > 0) {

			$request = ['parent_id' => $client_data->upazila_id, 'type' => 2];

			//get all union
			$union = Location::get_all_location($request);
		}

		$cmenu = 'profile';

		return response()->json([
			'status' => $api_status,
			'message' => $api_message,
			'data' => [
				'client_data' => $client_data,
				'upazila' => $upazila,
				'union' => $union
			],
		], $api_status_code);
	}
	public function orders(Request $request)
	{

		if (!empty($request->header('APP-KEY'))) {
			$client_id = $request->header('CLIENT-ID');
		} else {
			$client_id = session('client_id');
		}

		//get orderlist  data	
		$orders = DB::table('orders AS ORD')

			->select('ORD.id', 'ORD.order_id', 'ORD.client_id', 'ORD.total_amount', 'ORD.discount', 'ORD.net_amount', 'ORD.status', 'ORD.shipping_address', 'ORD.created_at', 'CLNT.name', 'CLNT.email', 'CLNT.mobile', 'CLNT.address')

			->join('clients AS CLNT', 'CLNT.id', '=', 'ORD.client_id')
			->where('ORD.client_id', $client_id)
			->where('ORD.deleted_at', NULL)
			->where('CLNT.deleted_at', NULL)
			->get();

		//get all menus
		$menu_category = $this->menu_category();

		//get client info
		$client_data = Client::find($client_id);

		$cmenu = 'orderlist';
		return response()->json([
			'status' => 'success',
			'message' => 'Order List',
			'data' => [
				'client_data' => $client_data,
				'orders' => $orders
			]
		], 200);
	}

	public function order_details($order_id)
	{

		//get order details
		$data = Order::order_details($order_id);

		return response()->json([
			'status' => 'success',
			'message' => 'Order Details',
			'data' => [
				'order' => $data
			]
		], 200);
	}
	public function order_reject(Request $request)
	{

		$client_id = $request->header('CLIENT-ID');



		DB::beginTransaction();

		try {

			$data = [
				'updated_by' => $client_id,
				'updated_by_ip' => $request->ip(),
				'status' => 3
			];

			$order_update = DB::table('orders')->where('order_id', $request->id)->update($data);

			$inventory_update = DB::table('inventories')->where('order_id', $request->id)->update($data);

			DB::commit();
			return response()->json([
				'status' => 'success',
				'message' => 'Order successfully rejected',
				'data' => []
			], 200);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'status' => 'success',
				'message' => 'Order unsuccessfully not rejected',
				'data' => []
			], 401);
		}
	}


	public function api_order_submit(Request $request)
	{
		$client_id = $request->header('CLIENT-ID');

		$shipping_address = $request->shipping_address;

		$current_year = date("y");
		$current_date = date("d");

		$total_count = DB::table('orders')
			->whereYear('created_at', date("Y"))
			->count();

		$serial = $total_count + 1;

		$serial_code = $this->order_key($serial);

		$order_id = $current_year . $current_date . $serial_code;

		$inventories_data = [];

		$product_data = $request->product;

		// dd($product_data);
		// $total_amount = 0;

		foreach ($product_data as $value) {

			// $total_amount += ($value['rate'] * $value['quantity']);

			$inventories_data[] = [
				'order_id' => $order_id,
				'product_id' => $value["id"],
				'client_id' => $client_id,
				'supplier_id' => $value["supplier_id"],
				'type' => 2,
				'debit' => $value["quantity"],
				'selling_price' => $value["rate"],
				'status' => 1,	// 1 = pending
				'created_by' => $client_id,
				'created_by_ip' => $request->ip(),
			];
		}

		// dd($inventories_data);

		$order_data = [
			'order_id' => $order_id,
			'client_id' => $client_id,
			'total_amount' => $request->order_amount,
			'discount' => $request->discount,
			'net_amount' => $request->net_amount,
			'status' => 1,
			'shipping_address' => $shipping_address,
			'created_by' => $client_id,
			'created_by_ip' => $request->ip(),
		];

		DB::table('inventories')
			->insert($inventories_data);

		DB::table('orders')
			->insert($order_data);


		return response()->json([
			'status' => 'success',
			'message' => 'Order Successfully Submited',
			'data' => [
				'order_id' => $order_id
			]
		], 200);
	}
}
