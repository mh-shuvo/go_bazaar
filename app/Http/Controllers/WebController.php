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
use App\Inventory;
use App\Offer;
use App\Complain;
use Session;
use Image;
use Route;
use Response;
use CheckSubDomain;

class WebController extends Controller
{
	use Sms;

	public function __construct()
	{
	}

	public function index(Request $request)
	{
		$dd_id = !empty(CheckSubDomain::getSubDomain()) ? CheckSubDomain::getSubDomain()->district_id : 0;
		$menu_category = $this->menu_category();

		// dd($dd_id);

		$is_show_category = DB::table('categories')
			->select('id', 'name', 'is_show', 'icon')
			->where([
				['type', '=', 1],
				['is_show', '=', 1],
			])
			->whereNull("deleted_at")
			->orderBy('sorting')
			->get();

		//get all feature product
		$feature_product = $this->feature_product();

		$product_info = [];

		foreach ($is_show_category as $item) {
			if ($item->is_show == 1) {
				$product_info[$item->id] = [
					"category_id" => $item->id,
					"category_name" => $item->name
				];

				$product_info[$item->id]['product_list'] = DB::table('inventories AS INV')
					->join('products AS PRD', function ($join) use ($item) {
						$join->on('INV.product_id', '=', 'PRD.id')
							// ->where('INV.type', '=', 1)
							->where('PRD.category_id', '=', $item->id)
							->whereNull("PRD.deleted_at");
					})
					->join('product_rate AS PRDR', function ($join) use ($item) {
						$join->on('PRDR.product_id', '=', 'PRD.id')
							->where('PRD.category_id', '=', $item->id)
							->whereNull("PRDR.deleted_at");
					})
					->join('suppliers AS SPLR', function ($join) {
						$join->on('SPLR.id', '=', 'PRD.supplier_id')
							->whereNull("SPLR.deleted_at");
					})
					->join('bd_locations AS DIS', function ($join) use ($dd_id) {
						$join->on('DIS.id', '=', 'SPLR.district_id')
							->where('DIS.id', '=', $dd_id)
							->whereNull("SPLR.deleted_at");
					})
					->join('bd_locations AS UPZ', function ($join) {
						$join->on('UPZ.id', '=', 'SPLR.upazila_id')
							->whereNull("SPLR.deleted_at");
					})
					->leftJoin('offers', function ($join) {
						$join->on('offers.product_id', '=', 'PRD.id')->on('offers.shop_id', '=', 'PRD.supplier_id')->where('offers.offer_status', '=', 1);
					})
					->select('INV.id', 'INV.product_id', 'PRDR.rate', 'PRD.name as product_name', 'PRD.picture', 'PRD.category_id', 'DIS.en_name as district_name', 'UPZ.en_name as upazila_name', 'SPLR.shop_name as shop_name', DB::raw('SUM(INV.credit) AS total_credit'), DB::raw('SUM(INV.debit) AS total_debit'), 'offers.id as offer_id', 'offers.offer_type', 'offers.offer_amount')
					->where('PRD.category_id', '=', $item->id)
					->where('SPLR.district_id', '=', $dd_id)
					// ->where('INV.status', '=', 0)
					// ->orWhere('INV.status', '=', 2)
					->whereNull('PRD.deleted_at')
					->whereNull('offers.deleted_at')
					->groupBy("INV.product_id")
					->orderByDesc('PRD.created_at')
					->limit(8)
					->get();
			}
		}

		// echo "<pre>";
		// dd($product_info->toSql());
		// exit;

		// get product for mini slider
		$mini_slider_product = DB::table('products AS PRODUCT')
			->join('inventories AS INVT', function ($join) {
				$join->on("INVT.product_id", '=', 'PRODUCT.id')
					->where('INVT.type', '=', 1);
			})
			->select('PRODUCT.id', 'PRODUCT.name', 'PRODUCT.picture')
			->take(20)
			->orderByDesc('PRODUCT.created_at')
			->get();

		$popular_item = $this->popular_items(8);


		if (!empty($request->header('APP-KEY'))) {
			return response()->json(
				[
					'status' => 'success',
					'message' => 'Data Found',
					'data' => [
						'categories' => $menu_category,
						'products'   => $product_info,
						'slider_product' => $mini_slider_product,
						'feature_product' => $feature_product,
						'popular_item' => $popular_item
					]
				]
			);
		} else {
			return view("web.home", compact(
				'menu_category',
				'product_info',
				'mini_slider_product',
				'feature_product',
				'popular_item'
			));
		}
	}

	public function popular_category()
	{
		$popular_item = $this->popular_items();
		$menu_category = $this->menu_category();
		return view('web.popular_item', compact('popular_item', 'menu_category'));
	}

	public function popular_items($limit = null)
	{
		$dd_id = !empty(CheckSubDomain::getSubDomain()) ? CheckSubDomain::getSubDomain()->district_id : 0;
		$data = DB::table('inventories AS INV')
			->join('products AS PRD', function ($join) {
				$join->on('INV.product_id', '=', 'PRD.id')
					->whereNull("PRD.deleted_at");
			})
			->join('product_rate AS PRDR', function ($join) {
				$join->on('PRDR.product_id', '=', 'PRD.id')
					->whereNull("PRDR.deleted_at");
			})
			->join('suppliers AS SPLR', function ($join) {
				$join->on('SPLR.id', '=', 'PRD.supplier_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('bd_locations AS DIS', function ($join) use ($dd_id) {
				$join->on('DIS.id', '=', 'SPLR.district_id')
					->where('DIS.id', '=', $dd_id)
					->whereNull("SPLR.deleted_at");
			})
			->join('bd_locations AS UPZ', function ($join) {
				$join->on('UPZ.id', '=', 'SPLR.upazila_id')
					->whereNull("SPLR.deleted_at");
			})
			->leftJoin('offers', function ($join) {
				$join->on('offers.product_id', '=', 'PRD.id')->on('offers.shop_id', '=', 'PRD.supplier_id')->where('offers.offer_status', '=', 1);
			})
			->select('INV.id', 'INV.product_id', 'PRDR.rate', 'PRD.name as product_name', 'PRD.picture', 'PRD.category_id', DB::raw('count(*) as total_sale_time'), 'DIS.en_name as district_name', 'UPZ.en_name as upazila_name', 'SPLR.shop_name as shop_name', DB::raw('SUM(INV.credit) AS total_credit'), DB::raw('SUM(INV.debit) AS total_debit'), 'offers.id as offer_id', 'offers.offer_type', 'offers.offer_amount')
			->where('INV.type', '=', 2)
			->where('SPLR.district_id', '=', $dd_id)
			// ->where('INV.status', '=', 2)
			->whereNull('PRD.deleted_at')
			->whereNull('offers.deleted_at')
			->groupBy("INV.product_id")
			->orderByDesc(DB::raw('count(*)'));
		if ($limit != null) {
			$data = $data->limit($limit)->get();
		} else {
			$data = $data->paginate(8);
		}
		return $data;
	}

	public function categories()
	{
		$menu_category = $this->menu_category();

		$categories = DB::table('categories')
			->select('id', 'name', 'is_show', 'icon')
			->where([
				['type', '=', 1],
				// ['is_show', '=', 1],
			])
			->whereNull("deleted_at")
			->orderBy('sorting')
			->get();

		return view('web.categories', compact('menu_category', 'categories'));
	}

	public function product_list($cat_id = 0, $sub_cat_id = 0, $price = null)
	{

		$dd_id = !empty(CheckSubDomain::getSubDomain()) ? CheckSubDomain::getSubDomain()->district_id : 0;

		//get all menu
		$menu_category = $this->menu_category();

		$price_limit = 0;

		$get_product = DB::table('inventories AS INV')
			->join('products AS PRD', function ($join) use ($cat_id, $sub_cat_id) {
				$join->on('INV.product_id', '=', 'PRD.id')
					// ->where('INV.type', '=', 1)
					->where('PRD.category_id', '=', $cat_id)
					->when($sub_cat_id > 0, function ($q) use ($sub_cat_id) {
						$q->where('PRD.sub_category_id', $sub_cat_id);
					})
					->whereNull("PRD.deleted_at");
			})
			->join('product_rate AS PRDR', function ($join) use ($cat_id, $sub_cat_id, $price) {
				$join->on('PRDR.product_id', '=', 'PRD.id')
					->where('PRD.category_id', '=', $cat_id)
					->when($sub_cat_id > 0, function ($q) use ($sub_cat_id) {
						$q->where('PRD.sub_category_id', $sub_cat_id);
					})
					->when(!empty($price), function ($q) use ($price) {
						$price_range = explode("_", $price);
						if ($price_range[0] > 2000) {
							$q->where("PRDR.rate", ">", 2000);
						} else {
							$q->whereBetween('PRDR.rate', [$price_range[0], $price_range[1]]);
						}
					})
					->whereNull("PRDR.deleted_at");
			})
			->join('suppliers AS SPLR', function ($join) {
				$join->on('SPLR.id', '=', 'PRD.supplier_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('bd_locations AS UPZ', function ($join) {
				$join->on('UPZ.id', '=', 'SPLR.upazila_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('bd_locations AS UNI', function ($join) {
				$join->on('UNI.id', '=', 'SPLR.district_id')
					->whereNull("SPLR.deleted_at");
			})
			->leftJoin('offers', function ($join) {
				$join->on('offers.product_id', '=', 'PRD.id')->on('offers.shop_id', '=', 'PRD.supplier_id')->where('offers.offer_status', '=', 1);
			})
			->select('INV.id', 'INV.product_id', 'PRDR.rate', 'PRD.name as product_name', 'PRD.picture', 'PRD.category_id', 'UPZ.en_name as upazila_name', 'UNI.en_name as district_name', 'SPLR.shop_name as shop_name', DB::raw('SUM(INV.credit) AS total_credit'), DB::raw('SUM(INV.debit) AS total_debit'), 'offers.id as offer_id', 'offers.offer_type', 'offers.offer_amount')
			// ->where('PRD.category_id', '=', $category_id)
			// ->where(function ($query) {
			// 	$query->where("INV.status", 0)->orWhere("INV.status", 2);
			// })
			->where('SPLR.district_id', '=', $dd_id)
			->whereNull('PRD.deleted_at')
			->whereNull('offers.deleted_at')
			->when($sub_cat_id > 0, function ($q) use ($sub_cat_id) {
				$q->where('PRD.sub_category_id', $sub_cat_id);
			})
			->when(!empty($price), function ($q) use ($price) {
				$price_range = explode("_", $price);

				if ($price_range[0] > 2000) {
					$q->where("PRDR.rate", ">", 2000);
				} else {
					$q->whereBetween('PRDR.rate', [$price_range[0], $price_range[1]]);
				}
			})
			->groupBy("INV.product_id");

		// dd($get_product->toSql());

		if (!empty($price)) {
			$price = explode('_', $price);
			$price_limit = $price[1];

			$price_limit = $price_limit == 0 ? 9999 : $price_limit;
		}

		$get_product = $get_product->orderByDesc('PRD.created_at')->paginate(9);


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

		return view('web.product_list', compact(
			'get_product',
			'menu_category',
			// 'category_id',
			'get_sub_category',
			'cat_id',
			'sub_cat_id',
			'price_limit'
		));
	}

	public function product_search(Request $request)
	{

		$product = $request->product;
		//get all menu
		$menu_category = $this->menu_category();

		$dd_id = !empty(CheckSubDomain::getSubDomain()) ? CheckSubDomain::getSubDomain()->district_id : 0;


		$get_product = DB::table('inventories AS INV')
			->join('products AS PRD', function ($join) use ($product) {
				$join->on('INV.product_id', '=', 'PRD.id')
					->where('INV.type', '=', 1)
					// ->where('PRD.name', '=', $product)
					->Where('PRD.name', 'like', '%' . $product . '%')
					->whereNull("PRD.deleted_at");
			})
			->join('product_rate AS PRDR', function ($join) use ($product) {
				$join->on('PRDR.product_id', '=', 'PRD.id')
					// ->where('PRD.name', 'like', $product)
					->Where('PRD.name', 'like', '%' . $product . '%')
					->whereNull("PRDR.deleted_at");
			})
			->join('suppliers AS SPLR', function ($join) {
				$join->on('SPLR.id', '=', 'PRD.supplier_id')
					->whereNull("SPLR.deleted_at");
			})
			->leftJoin('offers', function ($join) {
				$join->on('offers.product_id', '=', 'PRD.id')->on('offers.shop_id', '=', 'PRD.supplier_id')->where('offers.offer_status', '=', 1);
			})

			->join('bd_locations AS DIS', function ($join) {
				$join->on('DIS.id', '=', 'SPLR.district_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('bd_locations AS UPZ', function ($join) {
				$join->on('UPZ.id', '=', 'SPLR.upazila_id')
					->whereNull("SPLR.deleted_at");
			})
			->select('SPLR.shop_name', 'INV.id',  'INV.product_id', 'PRDR.rate', 'PRD.name as product_name', 'PRD.picture', 'PRD.category_id', 'UPZ.en_name as upazila_name', 'DIS.en_name as district_name', DB::raw('SUM(INV.credit) AS total_credit'), DB::raw('SUM(INV.debit) AS total_debit'), 'offers.id as offer_id', 'offers.offer_type', 'offers.offer_amount')
			->whereNull('PRD.deleted_at')
			->where('SPLR.district_id', '=', $dd_id)
			->groupBy("INV.product_id");


		$get_product = $get_product->orderByDesc('PRD.created_at')->paginate(8);

		$categoryId = Product::Where('name', 'like', '%' . $product . '%')->first();

		$cat_id = 0;

		if ($categoryId != null) {

			$cat_id = ($categoryId->category_id > 0) ? $categoryId->category_id : 0;
		}

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


		$sub_cat_id = 0;
		$price_limit = 0;

		$get_sub_category = count($get_sub_category) > 0 ? $get_sub_category : [];

		return view('web.product_list', compact(
			'get_product',
			'menu_category',
			// 'category_id',
			'get_sub_category',
			'cat_id',
			'sub_cat_id',

			'price_limit'
		));
	}

	// product cart
	public function product_cart($id)
	{

		//DB::enableQueryLog();

		$get_product_info = DB::table('inventories AS INVT')

			->join('product_rate AS PRORAT', function ($join) {
				$join->on('PRORAT.id', '=', 'INVT.product_id');
			})
			->join('products AS PRODUCT', function ($join) {
				$join->on('PRODUCT.id', '=', 'PRORAT.product_id');
			})
			->join('bd_locations AS UPAZ', function ($join) {
				$join->on('UPAZ.id', '=', 'INVT.district_id');
			})
			->join('bd_locations AS UNION', function ($join) {
				$join->on('UNION.id', '=', 'INVT.upazila_id');
			})
			->join('farmers AS FARMER', function ($join) {
				$join->on('FARMER.id', '=', 'INVT.farmer_id');
			})
			->select('PRODUCT.name as product_name', 'PRORAT.rate', 'PRODUCT.picture', 'PRODUCT.category_id', 'UPAZ.en_name as district_name', 'UNION.en_name as upazila_name', 'INVT.product_id', 'INVT.upazila_id', 'INVT.district_id', 'INVT.farmer_id', 'INVT.loading_poing', 'FARMER.name as farmer_name', 'FARMER.phone')
			->where([
				['INVT.product_id', '=', $id],
				['PRODUCT.supplier_id', '=', $id],

			])
			->first();

		$farmer_id = $get_product_info->farmer_id;

		$debit_stock = DB::table('inventories')
			->select(DB::raw('SUM(debit) AS total_debit'))
			->where('farmer_id', '=', $farmer_id)
			->where('product_id', '=', $id)
			->whereNull('order_id')
			->groupBy('product_id')
			->first();

		$credit_stock = DB::table('inventories')
			->select(DB::raw('SUM(credit) AS total_credit'))
			->where('farmer_id', '=', $farmer_id)
			->where('product_id', '=', $id)
			->whereNotNull('order_id')
			->groupBy('product_id')
			->first();

		$total_debit = $debit_stock->total_debit;

		if ($credit_stock > 0) {
			echo $total_credit = $credit_stock->total_credit;
		} else {
			$total_credit = 0;
		}

		$total_stock = ($total_debit - $total_credit);

		return view('web.product_cart', compact(
			'get_product_info',
			'total_stock'
		));
	}

	// single product view

	public function product_view($product_id)
	{


		$menu_category = $this->menu_category();

		$dd_id = !empty(CheckSubDomain::getSubDomain()) ? CheckSubDomain::getSubDomain()->district_id : 0;

		// DB::enableQueryLog();

		$get_product_info = DB::table('inventories AS INV')
			->join('products AS PRD', function ($join) use ($product_id) {
				$join->on('INV.product_id', '=', 'PRD.id')
					// ->where('INV.type', '=', 1)
					->where('PRD.id', '=', $product_id)
					->whereNull("PRD.deleted_at");
			})
			->join('product_rate AS PRDR', function ($join) use ($product_id) {
				$join->on('PRDR.product_id', '=', 'PRD.id')
					->where('PRD.id', '=', $product_id)
					->whereNull("PRDR.deleted_at");
			})
			->join('suppliers AS SPLR', function ($join) {
				$join->on('SPLR.id', '=', 'PRD.supplier_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('bd_locations AS UPZ', function ($join) {
				$join->on('UPZ.id', '=', 'SPLR.upazila_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('bd_locations AS UNI', function ($join) {
				$join->on('UNI.id', '=', 'SPLR.district_id')
					->whereNull("SPLR.deleted_at");
			})
			->leftJoin('offers', function ($join) {
				$join->on('offers.product_id', '=', 'PRD.id')->on('offers.shop_id', '=', 'PRD.supplier_id')->where('offers.offer_status', '=', 1);
			})

			->select('INV.id', 'INV.product_id', 'PRDR.rate', 'PRD.name as product_name', 'PRD.picture', 'PRD.description', 'PRD.category_id', 'UPZ.en_name as upazila_name', 'UNI.en_name as district_name', 'SPLR.name as supplier_name', 'INV.supplier_id', 'SPLR.mobile as supplier_mobile', 'SPLR.id as supplier_id', 'SPLR.shop_name', 'SPLR.address as supplier_address', 'offers.id as offer_id', 'offers.offer_type', 'offers.offer_amount')
			->where([
				['INV.product_id', '=', $product_id],
				['SPLR.district_id', '=', $dd_id]
			])
			->whereNull('offers.deleted_at')
			->first();

		if (empty($get_product_info)) {
			return redirect(route('error'));
		}

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

			->join('suppliers AS SPLR', function ($join) {
				$join->on('SPLR.id', '=', 'PRD.supplier_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('bd_locations AS UPZ', function ($join) {
				$join->on('UPZ.id', '=', 'SPLR.upazila_id')
					->whereNull("SPLR.deleted_at");
			})
			->join('bd_locations AS UNI', function ($join) {
				$join->on('UNI.id', '=', 'SPLR.district_id')
					->whereNull("SPLR.deleted_at");
			})
			->select('INV.id', 'INV.product_id', 'PRDR.rate', 'PRD.name as product_name', 'PRD.picture', 'PRD.category_id', 'UPZ.en_name as upazila_name', 'UNI.en_name as district_name', DB::raw('SUM(INV.credit) AS total_credit'), DB::raw('SUM(INV.debit) AS total_debit'))
			->where('PRD.category_id', '=', $category_id)
			->where('SPLR.district_id', '=', $dd_id)
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
		return view('web.single_product_view', compact(
			'menu_category',
			'get_product_info',
			'total_stock',
			'get_product',
			'add_to_cart',
			'cart_quantity'
		));
	}

	public function error()
	{
		$menu_category = $this->menu_category();

		return view('web.error', compact('menu_category'));
	}

	// save customer_save
	public function customer_save(Request $request)
	{

		$name = $request->name;
		$mobile = $request->mobile;
		$user_pass = $request->password;
		$password = Hash::make($request->password);
		//cut mobile prefix
		$mobile_prefix = substr($mobile, 0, 3);

		$exists = User::where('username', $mobile)->first();


		if (!in_array($mobile_prefix, ['018', '019', '017', '015', '016', '013', '012'])) {

			toastr()->warning('ফোন নম্বর সঠিক নয়।');
			return redirect()->back();
		}

		if (!is_numeric($request->mobile)) {
			toastr()->warning('ফোন নম্বর অবশ্যই সংখ্যার হতে হবে');
			return redirect()->back();
		}
		if (strlen($request->mobile) < 11) {
			toastr()->warning('ফোন নম্বর অবশ্যই ১১ সংখ্যার হতে হবে');
			return redirect()->back();
		}

		if (strlen($request->mobile) > 11) {
			toastr()->warning('ফোন নম্বর অবশ্যই ১১ সংখ্যার হতে হবে');
			return redirect()->back();
		}
		if ($exists) {
			toastr()->warning('আপনার দেয়া মোবাইল নম্বর দিয়ে একটি একাউন্ট রয়েছে। আপনি চাইলে লগইন করতে পারেন।');
			return redirect()->back();
		}

		if ($request->password != $request->confirm_password) {
			toastr()->error('পাসওয়ার্ড মিল নেই');
			return redirect()->back();
		}

		$user_data = [
			'username' => $mobile,
			'password' => $password,
			'user_type' => 3,
			'created_by' => 1,
			'created_by_ip' => $request->ip(),
		];

		$customer_data = [
			'name' => $name,
			'mobile' => $mobile,
			'created_by' => 1,
			'created_by_ip' => $request->ip(),
		];

		DB::beginTransaction();

		try {

			$client_id = DB::table('clients')->insertGetId($customer_data);

			$user_data['record_id'] = $client_id;

			DB::table('users')
				->insert($user_data);

			DB::commit();
			// set session flashdata
			$msg = "GoBazaar এ আপনাকে স্বাগতম। আপনার ইউজারনেমঃ " . $mobile . ", পাসওয়ার্ডঃ " . $user_pass . ".Thank you. Gobazaar.com.bd";
			$response = Sms::sendSms($mobile, $msg);
			toastr()->success('রেজিস্ট্রেশন সম্পূর্ণ হয়েছে');

			return redirect()->back();
		} catch (\Exception $e) {
			DB::rollback();
			//echo "<pre>";
			//echo $e;exit();
			toastr()->error('কোন কিছু ভুল হচ্ছে');

			return redirect()->back();
		}
	}

	// customer login
	public function customer_login(Request $request)
	{

		$previous_url =  str_replace(url('/'), '', url()->previous());

		$login = Auth::attempt([
			'username' => $request->username,
			'password' => $request->password,
		]);

		if ($login) {
			$username = $request->username;

			$data = DB::table("users")
				->where([
					['username', '=', $username],
					['user_type', '=', 3],
				])
				->whereNull("deleted_at")
				->get()
				->first();

			// dd($data);

			// if this user is not customer
			if (empty($data)) {
				toastr()->error('ইউজারনেম অথবা পাসওয়ার্ড সঠিক নয়');
				return back();
			}

			$client = Client::find($data->record_id);

			session([
				'client_id' => $data->record_id,
				'name' => $client->name,
				'mobile' => $client->mobile,
				'address' => $client->address,
				'photo' => $client->photo,
			]);

			// dd(session())


			toastr()->success('Successfully login.');

			if ($previous_url == '/product_search') {

				return redirect('/');
			} else {

				return back();
			}

			// return back();




		} else {

			toastr()->error('ইউজারনেম অথবা পাসওয়ার্ড সঠিক নয়।');

			if ($previous_url == '/product_search') {

				return redirect('/');
			} else {

				return back();
			}
		}
	}

	//customer profile and edit
	public function customer_profile()
	{

		$client_id = session('client_id');

		//get all menus
		$menu_category = $this->menu_category();

		//get client data
		$client_data = DB::table('users AS UGS')

			->select('UGS.id as user_id', 'UGS.username', 'CLNT.id as client_id', 'CLNT.name', 'CLNT.mobile', 'CLNT.email', 'CLNT.address', 'CLNT.photo', 'CLNT.porosova', 'CLNT.ward', 'CLNT.corona_zone', 'LOC1.id as upazila_id', 'LOC1.en_name as upazila_name', 'LOC2.id as district_id', 'LOC2.en_name as district_name')

			->join('clients AS CLNT', function ($join) {

				$join->on('CLNT.id', '=', 'UGS.record_id')
					->where('UGS.user_type', 3);
			})

			->leftjoin('bd_locations AS LOC1', 'LOC1.id', '=', 'UGS.upazila_id')
			->leftjoin('bd_locations AS LOC2', 'LOC2.id', '=', 'UGS.district_id')

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

		if (!empty($client_data) && $client_data->district_id > 0) {

			$request = ['parent_id' => $client_data->district_id, 'type' => 3];

			//get all union
			$union = Location::get_all_location($request);
		}

		$cmenu = 'profile';
		return view('web.customer_profile', compact('client_data', 'menu_category', 'upazila', 'union', 'cmenu'));
	}

	//customer profile update
	public function customer_update(Request $request)
	{


		$validatedData = $request->validate(
			[
				'name' => ['required'],
				'mobile' => ['required', 'regex:/(01)[0-9]{9}/', 'digits:11', Rule::unique('clients', 'mobile')->ignore($request->client_id)->whereNull('deleted_at')],
			],
			[ //validation custom message

				"name.required" => "নাম প্রদান করুন",
				"mobile.required" => "মোবাইল নাম্বার দিন",
				"mobile.unique" => "মোবাইল নাম্বার পূর্বে ব্যবহার হয়েছে",
				"mobile.regex" => "সঠিক মোবাইল নাম্বার দিন",
				"mobile.digits" => "মোবাইল ১১ সংখ্যার দিন",

			]
		);

		$user_id = $request->user_id;
		$client_id = $request->client_id;
		$name = $request->name;
		$mobile = $request->mobile;
		$email = $request->email;
		$address = $request->address;
		$photo = $request->photo;
		$upazila = $request->upazila;
		$district = $request->district;

		// password have
		if ($request->password != NULL) {

			if ($request->password != $request->confirm_password) {
				toastr()->error('পাসওয়ার্ড মিল নেই');
				return redirect()->back();
			}
		}

		// existing check		
		$exists = User::where('username', $mobile)
			->where('id', '!=', $user_id)
			->where('user_type', 3)
			->first();

		if ($exists) {
			toastr()->warning('আপনার দেয়া মোবাইল নম্বর দিয়ে একটি একাউন্ট রয়েছে। আপনি চাইলে লগইন করতে পারেন।');
			return redirect()->back();
		}


		$user_data = [
			'username' => $mobile,
			'user_type' => 3,
			'upazila_id' => $upazila,
			'district_id' => $district,
			'updated_by' => session('client_id'),
			'updated_by_ip' => $request->ip(),
		];

		//password hame
		if ($request->password != NULL) {

			$user_data['password'] = Hash::make($request->password);
		}


		$customer_data = [
			'name' => $name,
			'mobile' => $mobile,
			'email' => $email,
			'address' => $address,
			'porosova' => $request->porosova,
			'ward' => $request->ward,
			'corona_zone' => $request->corona_zone,
			'upazila_id' => $upazila,
			'district_id' => $district,
			'updated_by' => session('client_id'),
			'updated_by_ip' => $request->ip(),
		];

		if ($request->hasFile("photo")) {
			//insert image
			$image = $request->file("photo");

			$img = $mobile . "." . $image->getClientOriginalExtension();

			$location = public_path("/upload/clients/" . $img);

			//upload image in folder
			$move = Image::make($image)->resize(300, 300)->save($location);


			if ($move) {
				$customer_data['photo'] = $img;

				//store photo in session
				session(['photo' => $img]);
			}
		}

		DB::beginTransaction();

		try {

			$client_update = DB::table('clients')->where('id', $client_id)->update($customer_data);
			$user_update = DB::table('users')->where('id', $user_id)->update($user_data);



			DB::commit();
			// set session flashdata
			toastr()->success('আপডেট সম্পূর্ণ হয়েছে');

			return redirect('customer/profile');
		} catch (\Exception $e) {
			DB::rollback();

			toastr()->error('কোন কিছু ভুল হচ্ছে');

			return redirect()->back();
		}
	}

	//order list
	public function orders(Request $request)
	{
		$client_id = session('client_id');

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

		return view('web.order_list', compact('client_data', 'orders', 'menu_category', 'cmenu'));
	}

	//order Details
	public function order_details($order_id)
	{

		//get order details
		$data = Order::order_details($order_id);

		//get all menu
		$menu_category = $this->menu_category();

		return view('web.order_details', compact('data', 'menu_category'));
	}

	//order reject
	public function order_reject(Request $request)
	{

		$client_id = session('client_id');



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

			echo json_encode(['status' => 'success', 'message' => 'অর্ডারটি বাতিল হয়েছে']);
		} catch (\Exception $e) {
			DB::rollback();

			// toastr()->error('কোন কিছু ভুল হচ্ছে');
			echo json_encode(['status' => 'error', 'message' => 'অর্ডারটি বাতিল হয়নি']);
		}
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

	//get feature product
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

	// logout customer
	public function customer_logout()
	{
		session()->forget(['client_id', 'name', 'mobile', 'address']);
		// set session flashdata
		toastr()->success('লগ আউট সম্পূর্ণ হয়েছে');
		return redirect()->back();
		session()->flush();
	}

	// order id generate
	public function order_key($serial_no, $length = 4)
	{
		return str_repeat("0", ($length - strlen($serial_no))) . $serial_no;
	}

	public function add_cart_product(Request $request)
	{
		$cart_products = Session('cart_product');

		// dd($cart_products);

		$product_id = $request->product_id;
		$msg = "Successfully data add to cart";
		$dd_id = !empty(CheckSubDomain::getSubDomain()) ? CheckSubDomain::getSubDomain()->district_id : 0;
		if (!empty($cart_products) && in_array($product_id, array_column($cart_products, 'id'))) {
			$data = $cart_products;
			$msg = "Product already Added";
		} else {
			DB::enableQueryLog();
			$product_info = DB::table('products AS PRODUCT')
				->join('inventories AS INVT', function ($join) {
					$join->on('INVT.product_id', '=', 'PRODUCT.id');
				})
				->join('product_rate AS PRORAT', function ($join) {
					$join->on('PRORAT.product_id', '=', 'PRODUCT.id');
				})
				->join('suppliers AS SUPLR', function ($join) {
					$join->on('SUPLR.id', '=', 'PRODUCT.supplier_id');
				})
				->leftJoin('offers', function ($join) {
					$join->on('offers.product_id', '=', 'PRODUCT.id')->on('offers.shop_id', '=', 'PRODUCT.supplier_id')->where('offers.offer_status', '=', 1);
				})
				->select('PRODUCT.id', 'PRORAT.rate', 'PRODUCT.name as product_name', 'PRODUCT.picture', 'PRODUCT.description', 'SUPLR.id as supplier_id', 'SUPLR.shop_name', 'SUPLR.address as supplier_address', 'offers.id as offer_id', 'offers.offer_type', 'offers.offer_amount')
				->where([
					['PRODUCT.id', '=', $product_id],
					['SUPLR.district_id', '=', $dd_id]
				])
				->first();

			// echo "<pre>";
			// print_r(DB::getQueryLog($product_info));
			// exit();
			// print_r($product_info);
			// exit();

			$products = [
				'id' => $product_info->id,
				'product_name' => $product_info->product_name,
				'quantity' => 1, // first time qty=1
				'rate' => $product_info->rate,
				'shop_name' => $product_info->shop_name,
				'picture' => isset($product_info->picture) ? $product_info->picture : 'default.jpg',
				'supplier_address' => isset($product_info->supplier_address) ? $product_info->supplier_address : NULL,
				'supplier_id' => $product_info->supplier_id,
				'offer_id' => $product_info->offer_id,
				'offer_type' => $product_info->offer_type,
				'offer_amount' => $product_info->offer_amount,
			];

			// echo "<pre>";
			// print_r($products);
			// exit();

			Session::push('cart_product', $products);
			//print_r(Session::get('cart_product'));
			//exit();

			$data = Session('cart_product');
		}

		$total = count($data);

		return response()->json(["status" => "success", "message" => $msg, "data" => $data, "total" => $total]);
	}

	public function remove_cart_product(Request $request)
	{
		$product_id = $request->product_id;

		$session_data = Session('cart_product');
		// print_r($session_data[0]);
		// exit;
		foreach ($session_data as $k => $value) {
			if ($value['id'] == $product_id) {
				unset($session_data[$k]);
			}
		}

		Session(['cart_product' => $session_data]);
		// echo "<pre>";
		// print_r(Session('cart_product'));exit();
		$total = count($session_data);

		return response()->json(["status" => "success", "message" => "Successfully data remove from cart", "data" => $session_data, "total" => $total]);
	}

	public function product_cart_list()
	{

		$cart_products = Session('cart_product');

		// dd($cart_products);

		$menu_category = $this->menu_category();

		if (empty($cart_products)) {
			$data = [];
			$cart_products = [];

			return view('web.product_cart_list', compact(
				'menu_category',
				'cart_products',
				'data'
			));
		}

		$products_id = array_column(($cart_products), "id");

		$credit_stock = DB::table('inventories')
			->select(DB::raw('SUM(credit) AS total_credit'), 'product_id')
			->whereIn("product_id", $products_id)
			->where('type', '=', 1)
			->whereNull('order_id')
			->groupBy('product_id')
			->get();

		$debit_stock = DB::table('inventories')
			->select(DB::raw('SUM(debit) AS total_debit'), 'product_id')
			->whereIn("product_id", $products_id)
			->where('type', '=', 2)
			->whereNotNull('order_id')
			->groupBy('product_id')
			->get();

		$debit_list = array_column((json_decode(json_encode($debit_stock))), "total_debit", "product_id");

		//    echo "<pre>";
		// print_r($credit_stock[$products_id]->total_credit);exit();
		$data = [];

		foreach ($credit_stock as $k => $value) {

			$data[$value->product_id] = [
				'available' => ($value->total_credit - (isset($debit_list[$value->product_id]) ? $debit_list[$value->product_id] : 0)),
			];
		}

		// dd($data);

		return view('web.product_cart_list', compact(
			'menu_category',
			'cart_products',
			'data'
		));
	}

	public function product_remove_cart($product_id)
	{

		$session_data = Session('cart_product');

		foreach ($session_data as $k => $value) {
			if ($value['id'] == $product_id) {

				unset($session_data[$k]);
			}
		}

		Session(['cart_product' => $session_data]);

		session()->flash('success', 'পণ্যটি বাতিল হয়েছে');

		return redirect()->back()->with('success', 'পণ্যটি বাতিল হয়েছে');
	}

	public function product_cart_update(Request $request)
	{
		$cart_list = session("cart_product");

		foreach ($cart_list as $k => $item) {
			if ($item['id'] == (int) $request->product_id) {
				$cart_list[$k]['quantity'] = (int) $request->quantity;
			}
		}

		session(['cart_product' => $cart_list]);

		return response()->json(['status' => 'success', 'message' => 'Quantity update successfully.', 'data' =>  $cart_list]);

		// $cart_list = session("cart_product");

		// dd($cart_list);

	}


	//get all location
	public function get_location(Request $request)
	{

		//get location
		$response = Location::get_all_location($request);

		if (!empty($response)) {
			echo json_encode(['status' => 'success', "message" => "data found", 'data' => $response]);
		} else {
			echo json_encode(['status' => 'error', "message" => "data not found", 'data' => []]);
		}
	}
	// product_checkout
	public function product_checkout()
	{

		$menu_category = $this->menu_category();

		$products_data = session("cart_product");

		$products_data = !empty($products_data) ? $products_data : [];

		$upazila_list = DB::table('bd_locations')
			->select('id', 'en_name')
			->where('type', '=', 2)
			->get();

		// dd($products_data);

		return view('web.product_checkout_list', compact(
			'menu_category',
			'products_data',
			'upazila_list'
		));
	}

	// order onfirm

	public function order_confirm(Request $request)
	{

		$client_id = (int) session('client_id');

		if ($client_id <= 0) {
			return response()->json(['status' => "error", 'message' => "Client not logged in."]);
		}

		$shipping_address = $request->shipping_address;

		$glue = date("ym");

		$total_count = DB::table('orders')
			->whereYear('created_at', date("Y"))
			->whereMonth('created_at', date("m"))
			->count();

		$total_order = $total_count + 1;

		$serial_code = $this->order_key($total_order);

		$order_id = $glue . $serial_code;

		$inventories_data = [];

		$product_data = session("cart_product");

		// dd($product_data);
		$total_amount = 0;

		foreach ($product_data as $value) {

			//offer calculation
			if ($value['offer_id'] != null) {
				if ($value['offer_type'] == 1) {
					$price = $value['rate'] - $value['offer_amount'];
				} else {
					$price = $value['rate'] - (($value['rate'] * $value['offer_amount']) / 100);
				}
			} else {

				$price = $value['rate'];
			}

			$inventories_data[] = [
				'order_id' => $order_id,
				'product_id' => $value["id"],
				'client_id' => $client_id,
				'supplier_id' => $value["supplier_id"],
				'type' => 2,
				'debit' => $value["quantity"],
				'selling_price' => $price,
				'status' => 1,	// 1 = pending
				'created_by' => $client_id,
				'created_by_ip' => \Request::ip(),
			];
			$total_amount += ($price * $value["quantity"]);
		}

		// dd($inventories_data);

		$order_data = [
			'order_id' => $order_id,
			'client_id' => $client_id,
			'total_amount' => $total_amount,
			'discount' => 0,
			'net_amount' => $total_amount,
			'status' => 1,
			'shipping_address' => $shipping_address,
			'created_by' => $client_id,
			'created_by_ip' => \Request::ip(),
		];

		// Begin Transaction
		DB::beginTransaction();

		try {
			DB::table('inventories')->insert($inventories_data);

			DB::table('orders')->insert($order_data);

			// Commit Transaction
			DB::commit();

			session(["cart_product" => []]);
			// session::flush();

			$mobile = $request->mobile;
			$msg = "আপনার অর্ডারটি গৃহীত হয়েছে। আপনার অর্ডার আইডি " . $order_id . "। পরবর্তী ৭২ ঘন্টার মধ্যে অর্ডারটি আপনার ঠিকানায় পোঁছে দেওয়া হবে। ধন্যবাদ gobazaar.com.bd";
			$response = Sms::sendSms($mobile, $msg);

			return response()->json(['status' => "success", 'message' => "Successfully order completed."]);

		} catch (\Exception $e) {
			// Rollback Transaction
			DB::rollback();

			return response()->json(['status' => "error", 'message' => "Fail to complete order."]);
		}
	}

	public function sms_send(Request $request)
	{
		$otp_code = mt_rand(100000, 999999);
		$mobile = $request->phone;
		$msg = "Your verification code is " . $otp_code . ' valid for 1 minute. Gobazaar.com.bd. Thank you.';
		$response = Sms::sendSms($mobile, $msg);
		// $response = 1;

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
			return ['status' => $status, 'code' => $otp_code];
		}
	}

	public function check_otp(Request $request)
	{
		$otp = session('otp_code');
		if ($request->otp == $otp) {
			$status = "success";
			$msg = "Verfied";
			session()->forget(['otp_code']);
		} else {
			$status = 'error';
			$msg = "OTP Code Not Match";
		}
		return [
			'status' => $status,
			'message' => $msg,
		];
	}

	public function contact()
	{
		$menu_category = $this->menu_category();

		return view("web.contact", compact("menu_category"));
	}

	public function contactStore(Request $request)
	{
		$request->validate([
			'name' => 'required|max:50',
			'phone' => 'required',
			'email' => 'required|email',
			'message' => 'required'
		]);

		$contact = new Contact();
		$contact->name = $request->name;
		$contact->phone = $request->phone;
		$contact->email = $request->email;
		$contact->message = $request->message;
		$contact->save();

		// toastr()->success('Successfully Submited');
		return response()->json([
			'status' => 'success',
			'message' => 'Successfully Submited'
		]);
	}

	public function supplier_registration()
	{
		$menu_category = $this->menu_category();

		$upazila_list = DB::table('locations')
			->select('id', 'name')
			->where('type', '=', 1)
			->get();

		$supplier_types = DB::table('supplier_types')
			->select('id', 'name')
			->get();

		return view('web.supplier_registration', compact("menu_category", "upazila_list", "supplier_types"));
	}

	public function supplier_list()
	{
		$menu_category = $this->menu_category();

		$suppliers = Supplier::where('status', '1')->orderBy('id', 'desc')->paginate(10);

		return view('web.supplier_list', compact("menu_category", "suppliers"));
	}

	public function supplier_save(Request $request)
	{
		//validation start
		$validator = Validator::make(
			$request->all(),
			[

				'upazila_id' => ['required'],
				'district_id' => ['required'],
				'supplier_type' => ['required'],
				'shop_name' => ['required'],
				'supplier_name' => ['required'],
				'mobile' => ['required', 'regex:/(01)[0-9]{9}/', 'digits:11', Rule::unique('suppliers', 'mobile')->whereNull('deleted_at')],
				'address' => ['required'],
				'nid' => ['required'],
				'trade_id' => ['required'],

			],
			[ //validation custom message
				"upazila_id.required" => "উপজেলা সিলেক্ট করুন",
				"district_id.required" => "জেলা সিলেক্ট করুন",
				"supplier_type.required" => "বিক্রেতার ধরন দিন",
				"supplier_name.required" => "বিক্রেতার নাম দিন",
				"shop_name.required" => "দোকানের নাম দিন",
				"address.required" => "ঠিকানা দিন",
				"mobile.required" => "মোবাইল নাম্বার দিন",
				"mobile.unique" => "মোবাইল নাম্বার পূর্বে ব্যবহার হয়েছে",
				"mobile.regex" => "সঠিক মোবাইল নাম্বার দিন",
				"mobile.digits" => "মোবাইল ১১ সংখ্যার দিন",
				"nid.required" => "জাতীয় প্রিচয়পত্র নম্বর দিন",
				"trade_id.required" => "ট্রেড লাইসেন্স এর আইডি দিন",

			]
		);
		//if validation success
		if ($validator->passes()) {

			$mobile = $request->mobile;

			//cut mobile prefix
			$mobile_prefix = substr($mobile, 0, 3);

			if (!in_array($mobile_prefix, ['018', '019', '017', '015', '016', '013', '012'])) {

				return response()->json([
					"status" => "error",
					"msg" => "ফোন নম্বর সঠিক নয়"
				]);
			}

			if (!is_numeric($request->mobile)) {
				return response()->json([
					"status" => "error",
					"msg" => "ফোন নম্বর অবশ্যই সংখ্যার হতে হবে"
				]);
			}

			//supplier  object
			$supplier = new Supplier();
			//supplier data create
			$supplier->upazila_id = $request->upazila_id;
			$supplier->district_id = $request->district_id;
			$supplier->supplier_types = $request->supplier_type;
			$supplier->shop_name = $request->shop_name;
			$supplier->name = $request->supplier_name;
			$supplier->mobile = $request->mobile;
			$supplier->email = $request->email;
			$supplier->address = $request->address;
			$supplier->nid = $request->nid;
			$supplier->trade_id = $request->trade_id;

			$supplier->created_by = 1;
			$supplier->created_by_ip = request()->ip();


			if ($request->hasFile('shop_image')) {
				$fileName = time() . '.' . $request->shop_image->extension();
				$supplier->shop_image = $fileName;
				$request->shop_image->move(public_path('upload/supplier'), $fileName);
			}

			if ($request->hasFile('trade_photo')) {
				$tradePhoto = time() . '.' . $request->trade_photo->extension();
				$supplier->trade_photo = $tradePhoto;
				$request->trade_photo->move(public_path('upload/supplier'), $tradePhoto);
			}

			$supplier->save();

			$mobile = $request->mobile;

			$msg = "GoBazaar এর পার্টনার হিসেবে যুক্ত হওয়ার জন্য আপনাকে স্বাগতম। আপনার একাউন্ট টি প্রক্রিয়াধীন রয়েছে। অনুমোদিত হলে এসএমএস এর মাধ্যমে আপনাকে জানানো হবে।";

			$response = Sms::sendSms($mobile, $msg);

			return response()->json([
				"status" => "success",
				"msg" => "বিক্রেতা হিসেবে তথ্য সমূহ সফলভাবে জমা হয়েছে। আপনার একাউন্ট টি অনুমোদিত হলে এসএমএস এর মাধ্যমে আপনাকে জানানো হবে।"
			]);
		}

		return Response::json(['errors' => $validator->errors()]);
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




	//get auto complete product
	public function product_auto_search(Request $request)
	{

		if ($request->get('query')) {
			$dd_id = !empty(CheckSubDomain::getSubDomain()) ? CheckSubDomain::getSubDomain()->district_id : 0;

			$query = $request->get('query');

			$data = DB::table('products AS PRD')

				->join('inventories AS INVT', function ($join) {

					$join->on('INVT.product_id', '=', 'PRD.id')
						->on('INVT.supplier_id', '=', 'PRD.supplier_id');
				})
				->join('suppliers AS SPLR', function ($join) {

					$join->on('SPLR.id', '=', 'PRD.supplier_id');
				})

				->select('PRD.id as product_id', 'PRD.name', DB::raw('SUM(INVT.debit) AS total_debit'), DB::raw('SUM(INVT.credit) AS total_credit'))

				->where('PRD.name', 'LIKE', "%{$query}%")
				->where('SPLR.district_id', '=', $dd_id)

				->groupBy('INVT.product_id')

				->get();

			$output = '';

			foreach ($data as $row) {

				$output .= '<li id="stext" class="dropdown-item" style="color:#ff9800;">' . $row->name . '</li>';
			}

			echo $output;
		}
	}

	public function wishListIndex()
	{
		$client_id = session('client_id');
		$client_data = Client::find($client_id);
		$cmenu = 'wishlist';
		$menu_category = $this->menu_category();
		$wishlist_data = Wishlist::where('client_id', $client_id)->get();
		return view('web.wishlist', compact('client_data', 'cmenu', 'menu_category', 'wishlist_data'));
	}

	public function wishListStore(Request $request)
	{
		$data = new Wishlist();

		$data->product_id = $request->product_id;
		$data->client_id  = $request->client_id;
		$data->save();

		if ($data) {
			return [
				'status' => 'success',
				'status_code' => 200
			];
		} else {
			return [
				'status' => 'error',
				'status_code' => 401
			];
		}
	}

	public function removeWishListProduct(Request $request)
	{
		if (isset($request->id)) {
			$data = Wishlist::find($request->id);
			$result = $data->delete();
		} else {
			$result = DB::table('wishlists')->where('product_id', '=', $request->product_id)->where('client_id', '=', $request->client_id)->delete();
		}

		if ($result) {
			return [
				'status' => 'success',
				'status_code' => 200
			];
		} else {
			return [
				'status' => 'error',
				'status_code' => 401
			];
		}
	}

	public function customer_forgot_password()
	{
		return view('web.customer_forgot_password');
	}
	public function customer_fp_otp_send(Request $request)
	{

		$isExists = Client::where('mobile', $request->mobile)->first();

		if (empty($isExists)) {
			return [
				'status' => "error",
				'message' => "এই নাম্বার দিয়ে কোন একাউন্ট খুজে পাওয়া যায়নি।"
			];
		}

		$otp_code = mt_rand(100000, 999999);
		$mobile = $request->mobile;
		$msg = "Your verification code is " . $otp_code . ' valid for 1 minute. Gobazaar.com.bd. Thank you.';
		$response = Sms::sendSms($mobile, $msg);
		// $response = 1;
		if ($response == 1) {
			$status = 'success';
			session(['otp_code' => $otp_code]);
		} else {
			$status = 'fail';
		}
		return [
			'status' => $status,
			'message' => "এই নাম্বার দিয়ে একাউন্ট খুজে পাওয়া গেছে।",
			"record_id" => $isExists->id,
			// "otp_code" => $otp_code
		];
	}
	public function customer_fp_otp_verify(Request $request)
	{
		$otp = session('otp_code');
		if ($request->otp == $otp) {
			$status = "success";
			$msg = "ভেরিফাইড";
			session()->forget(['otp_code']);
		} else {
			$status = 'error';
			$msg = "OTP কোড মিলে নাই";
		}
		return [
			'status' => $status,
			'message' => $msg,
		];
	}
	public function customer_password_change(Request $request)
	{
		$user = User::where('record_id', $request->record_id)->first();
		$password = Hash::make($request->password);
		$user->password = $password;
		if ($user->save()) {

			return [
				'status' => 'success',
				'message'    => 'পাসওয়ার্ড পরিবর্তন সফল হয়েছে'
			];
		} else {
			return [
				'status' => 'error',
				'message'    => 'পাসওয়ার্ড পরিবর্তন সফল হয়নি'
			];
		}
	}

	public function privacy_policy()
	{
		return view('web.privacy_policy');
	}

	public function order_tracking()
	{
		$client_id = session('client_id');

		//get all menus
		$menu_category = $this->menu_category();

		//get client info
		$client_data = Client::find($client_id);

		$cmenu = 'ordertrack';

		return view('web.order_tracking', compact('client_data', 'menu_category', 'cmenu'));
	}


	public function order_tracking_data(Request $request)
	{
		$id = $request->order_id;
		$client_id = session('client_id');
		$status = "error";
		$message = "আপনার কাঙ্ক্ষিত অর্ডার টি খুজে পাওয়া যায়নি";

		$suppliers = Inventory::select('supplier_id', 'status')->where('order_id', $id)->where('client_id', '=', $client_id)->groupBy('supplier_id')->get();
		$data = Inventory::where('order_id', $id)->where('client_id', '=', $client_id)->get();
		$output = [];

		if (count($suppliers) > 0) {
			$status = "success";
			$message = "Data Found";
			for ($i = 0; $i < count($suppliers); $i++) {
				$temp_arr = [];
				$supplier_id = $suppliers[$i]->supplier_id;
				$suppplier = DB::table('suppliers')->select('name')->where('id', '=', $supplier_id)->first();
				$temp_arr['supplier'] = $suppplier->name;
				$temp_arr['supplier_id'] = $suppliers[$i]->supplier_id;
				$temp_arr['status'] = $suppliers[$i]->status;
				$temp_arr['products'] = [];


				for ($j = 0; $j < count($data); $j++) {
					if ($data[$j]->supplier_id == $supplier_id) {
						$product = DB::table('products')->select('name')->where('id', '=', $data[$j]->product_id)->first();
						array_push($temp_arr['products'], $product->name);
					} else {
						continue;
					}
				}
				array_push($output, $temp_arr);
			}
		}

		return response()->json([
			'status' => $status,
			'message' => $message,
			'data'  => $output,

		]);
	}

	public function complain_box()
	{
		$client_id = session('client_id');

		//get all menus
		$menu_category = $this->menu_category();

		//get client info
		$client_data = Client::find($client_id);

		$cmenu = 'complain_box';
		return view('web.complain_box', compact('cmenu', 'menu_category', 'client_data'));
	}

	public function complain_submit(Request $request)
	{
		$instance = new Complain();

		$instance->client_id = session('client_id');
		$instance->supplier_id = $request->supplier_id;
		$instance->order_id = $request->order_id;
		$instance->reply_type  = 0;
		$instance->reply_from  = 0;
		$instance->message = $request->message;
		$instance->created_by = session('client_id');
		$instance->created_by_ip = $request->ip();
		$instance->save();
		return response()->JSON([
			'status' => 'success',
			'msg'	 => 'আপনার অভিযোগটি গৃহীত হয়েছে।'
		]);
	}

	public function complain_list()
	{
		$client_id = session('client_id');

		//get all menus
		$menu_category = $this->menu_category();

		//get client info
		$client_data = Client::find($client_id);

		//get complain data

		$complain_data = Complain::where('client_id', session('client_id'))->where('reply_type', 0)->where('reply_from', 0)->paginate(8);

		$cmenu = 'complain_list';
		return view('web.complain_list', compact('cmenu', 'menu_category', 'client_data', 'complain_data'));
	}

	public function complain_details($id)
	{
		$cmenu = 'complain_list';
		$client_id = session('client_id');

		//get all menus
		$menu_category = $this->menu_category();

		//get client info
		$client_data = Client::find($client_id);

		//get complain
		$complain = Complain::find($id);
		$complain_reply = Complain::where('parent_id', $id)->get();
		return view('web.single_complain', compact('cmenu', 'menu_category', 'client_data', 'complain', 'complain_reply'));
	}

	public function complain_reply_submit(Request $request)
	{

		$complain = Complain::find($request->complain_id);

		$instance = new Complain();

		$instance->client_id = session('client_id');
		$instance->supplier_id = $complain->supplier_id;
		$instance->order_id = $complain->order_id;
		$instance->parent_id = $complain->id;
		$instance->reply_type  = 1;
		$instance->reply_from  = 0;
		$instance->message = $request->message;
		$instance->created_by = session('client_id');
		$instance->created_by_ip = $request->ip();
		$instance->save();

		return response()->JSON([
			'status' => 'success',
			'message' => 'Message Successfully Submitted'
		]);
	}
}
