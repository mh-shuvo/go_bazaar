<?php

namespace App\Http\Controllers;

use App\Category;
use App\Inventory;
use App\Product;
use App\Client;
use App\Offer;
use App\SubCategory;
use App\Unit;
use App\Employe;
use App\Imports\ProductsImport;
use App\Imports\CheckProductImport;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Session;
use Response;

class ProductController extends Controller
{
	public function index()
	{
		return view('admin.supplier.product.index');
	}

	public function data(Request $request)
	{
		if ($request->ajax()) {
			if (Auth::user()->user_type == 5) {	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}

			$data = DB::table('products AS PRODUCT')
				->join('categories AS CAT', function ($join) {
					$join->on('CAT.id', '=', 'PRODUCT.category_id');
				})
				->join('categories AS SUBCAT', function ($join) {
					$join->on('SUBCAT.id', '=', 'PRODUCT.sub_category_id');
				})
				->select('PRODUCT.*', 'CAT.name as category', 'SUBCAT.name as sub_category')
				->where('PRODUCT.supplier_id', '=', $supplier_id);

			if ($request->category_id > 0) {
				$data->where('PRODUCT.category_id', '=', $request->category_id);
			}

			if ($request->sub_category_id > 0) {
				$data->where('PRODUCT.sub_category_id', '=', $request->sub_category_id);
			}

			$data = $data->whereNull('PRODUCT.deleted_at')->orderBy('PRODUCT.id', 'DESC')->get();

			return Datatables::of($data)

				->addIndexColumn()


				->addColumn('created_at', function ($row) {
					return date('d-m-Y', strtotime($row->created_at));
				})

				->addColumn('picture', function ($row) {
					$images = explode('##', $row->picture);
					return $images[0];
				})

				->addColumn('action', function ($row) {

					$btn = permission_check('product', 'edit') ? '<a href="javascript:void(0)" data-id="' . $row->id . '" class="productEdit btn btn-primary btn-sm">সম্পাদন</a>' : '';

					$btn .= permission_check('product', 'delete') ? '<a href="javascript:void(0)" data-id="' . $row->id . '" class="productDelete btn btn-danger btn-sm">বাতিল</a>' : '';

					return $btn;
				})
				->rawColumns(['action', 'category', 'sub_category', 'picture'])

				->make(true);
		}
	}

	public function store(Request $request)
	{


		$fileName = 'default.jpg';

		$operation_type = "জমা";

		if (Auth::user()->user_type == 5) {	// shop employee
			$employee = Employe::find(Auth::user()->record_id);

			$supplier_id = $employee->shop_id;
		} else {
			$supplier_id = Auth::user()->record_id;
		}
		

		$isExists = Product::where('category_id', $request->category)->where('sub_category_id', $request->sub_category)->where('name', $request->name)->where('supplier_id', $supplier_id)->count();


		if ($isExists > 0 && !isset($request->id)) {
			return [
				'status' => 'error',
				'msg'    => 'এই পন্যটি ইতিমধ্যে আপনি যুক্ত করেছেন'
			];
		}


		if (isset($request->id)) {
			$product = Product::find($request->id);

			if ($request->hasFile('picture')) {
				$fileName = $product->picture;
				foreach ($request->picture as $key => $file) {
					$file_name = time() . '' . $key . '.' . $file->extension();
					$file->move(public_path('upload/product'), $file_name);
					$fileName .= $file_name . '##';
				}
			} else {
				$fileName = $product->picture;
			}
			$operation_type = "হালনাগাদ";
		} else {
			$product = new Product();

			if ($request->hasFile('picture')) {
				$fileName = '';
				foreach ($request->picture as $key => $file) {
					$file_name = time() . '' . $key . '.' . $file->extension();
					$file->move(public_path('upload/product'), $file_name);
					$fileName .= $file_name . '##';
				}
			}
		}

		$product->supplier_id = $supplier_id;
		$product->category_id = $request->category;
		$product->sub_category_id = $request->sub_category;
		// $product->unit_id = $request->unit;
		$product->unit_id = null;
		$product->name = $request->name;
		$product->picture = $fileName;
		$product->description = $request->description;
		$product->created_by = Auth::user()->id;
		$product->created_by_ip = $request->ip();

		$product_insert = $product->save();

		if ($product_insert) {
			$msg = "আপনার পন্যটি সফল্ভাবে $operation_type হয়েছে।";
			$status = 'success';
		} else {
			$msg = "অনিবার্য কারণবশত আপনার পন্য $operation_type হয়নি";
			$status = 'error';
		}

		return [
			'status' => $status,
			'msg' => $msg,
		];
	}

	public function csvIndex()
	{
		return view('admin.product_upload');
	}

	public function ProductCSVUpload(Request $request)
	{
		try {
			Excel::import(new ProductsImport, $request->file('product_csv_file'));
		} catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
			$failures = $e->failures();
			$errors = [];

			foreach ($failures as $failure) {
				array_push($errors, $failure->errors()[0] . 'on row number ' . $failure->row());
			}
			return [
				'status' => 'error',
				'errors' => $errors,
			];
		}
		return [
			'status' => 'success',
			'msg' => 'CSV ফাইল সফলভাবে আপলোড হয়েছে',
		];
	}

	public function ProductCSVCheck(Request $request)
	{
		try {
			Excel::import(new CheckProductImport, $request->file('product_csv_file'));
		} catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
			$failures = $e->failures();
			$errors = [];

			foreach ($failures as $failure) {
				array_push($errors, $failure->errors()[0] . 'on row number ' . $failure->row());
			}
			return [
				'status' => 'error',
				'errors' => $errors,
			];
		}
		return [
			'status' => 'success',
			'msg' => 'অভিনন্দন! আপনার দেয়া CSV ফাইলে কোন সমস্যা নেই।',
		];
	}


	public function edit(Request $request)
	{
		return Product::find($request->id);
	}
	public function delete(Request $request)
	{
		$data = Product::find($request->id);
		$inventory = Inventory::where('product_id', $request->id)->get();

		if (!empty($inventory)) {
			$data->delete();
			$msg = "আপনার পন্যটি সফল্ভাবে ডিলিট হয়েছে।";
			$status = 'success';
		} else {
			$msg = "অনিবার্য কারণবশত আপনার পন্য ডিলিট হয়নি";
			$status = 'error';
		}
		return [
			'status' => $status,
			'msg' => $msg,
		];
	}
	public function image_delete(Request $request)
	{

		$product = Product::find($request->id);

		$images = explode("##", $product->picture);
		array_pop($images);
		$fileName = "";
		foreach ($images as $item) {
			if ($item == $request->image) {
				$old_img = public_path('upload/product/' . $item);
				if (file_exists($old_img)) {
					@unlink($old_img);
				}
				continue;
			} else {
				$fileName .= $item . '##';
			}
		}
		$product->picture = $fileName;
		$product->save();

		return [
			'status' => 'success',
		];
	}
	public function getSubCategoryByCategory(Request $request)
	{
		$sub_categories = Category::where('parent_id', $request->category_id)->where('type', '2')->get();
		return $sub_categories;
	}

	public function getProduct(Request $request)
	{
		if (Auth::user()->user_type == 5) {	// shop employee
			$employee = Employe::find(Auth::user()->record_id);

			$supplier_id = $employee->shop_id;
		} else {
			$supplier_id = Auth::user()->record_id;
		}
        $products = Product::where('category_id', $request->category_id)
            ->where('sub_category_id', $request->sub_category_id)
            ->where("supplier_id", $supplier_id)
            ->orderBy('name','ASC')
            ->get();
		return $products;
	}


	//product sale
	public function product_sale()
	{

		return view('admin.supplier.product.product_sale');
	}

	//get all product from session
	public function product_fetch(Request $request)
	{
		$data = [];
		$cart_products = Session('cart_product');
		if (!empty($cart_products)) {
			$data = $cart_products;
		}
		return response()->json([
			'status' => 'success',
			'data' => $data
		]);
	}


	public function sale_product_add(Request $request)
	{


		$cart_products = Session('cart_product');

		$product_id = $request->product_id;

		if (!empty($cart_products) && in_array($product_id, array_column($cart_products, 'id'))) {

			$data = $cart_products;
			$status = 'error';
			$msg = "<strong>দুঃখিত! পন্যটি তালিকাভুক্ত আছে।";
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
				->select('PRODUCT.id', 'PRORAT.rate', 'PRODUCT.name as product_name', 'PRODUCT.picture', 'PRODUCT.description', 'SUPLR.id as supplier_id', 'SUPLR.shop_name', 'SUPLR.address as supplier_address')
				->where([
					['PRODUCT.id', '=', $product_id],
				])
				->first();

			$products = [
				'id' => $product_info->id,
				'inventory_id' => $request->inventory_id,
				'buying_price' => $request->buying_price,
				'product_name' => $product_info->product_name,
				'quantity' => 1, // first time qty=1
				'rate' => $product_info->rate,
				'shop_name' => $product_info->shop_name,
				'picture' => isset($product_info->picture) ? $product_info->picture : 'default.jpg',
				'supplier_address' => isset($product_info->supplier_address) ? $product_info->supplier_address : NULL,
				'supplier_id' => $product_info->supplier_id,
				'current_stock' => Product::GetCurrentStock($product_info->id, $request->inventory_id),
			];

			Session::push('cart_product', $products);

			$status = 'success';
			$msg = 'পন্যটি তালিকাভুক্ত হল।';

			$data = Session('cart_product');
		}

		// dd($data);
		// exit;


		return response()->json(["status" => $status, 'message' => $msg, "data" => $data]);
	}


	public function sale_product_list_update(Request $request)
	{

		$cart_list = session("cart_product");

		foreach ($cart_list as $k => $item) {

			if ($item['id'] == (int)$request->product_id) {

				$cart_list[$k]['quantity'] = (int)$request->quantity;
			}
		}

		session(['cart_product' => $cart_list]);

		$data = Session('cart_product');

		return response()->json(['status' => 'success', 'message' => 'Quantity update successfully.', 'data' => $data]);
	}

	//new client credential create
	public function new_client_create($receive = NULL)
	{


		if ($receive->customer_mobile != NULL) {

			$client_data = [

				'name' => isset($receive->customer_name) ? $receive->customer_name : $receive->customer_mobile,
				'mobile' => $receive->customer_mobile,
				'email' => 'customer@gmail.com',
				'address' => 'customer',
				'upazila_id' => 0,
				'union_id' => 0,
				'created_by' => Auth::user()->record_id,
				'created_by_ip' => \Request::ip(),

			];

			$client_id = DB::table('clients')->insertGetId($client_data);

			$user_data = [

				'record_id' => $client_id,
				'username' => $receive->customer_mobile,
				'password' => bcrypt($receive->customer_mobile),
				'upazila_id' => 0,
				'union_id' => 0,
				'user_type' => 3,
				'created_by' => Auth::user()->record_id,
				'created_by_ip' => \Request::ip(),

			];

			$user_insert = DB::table('users')->insert($user_data);

			return $client_id;
		} else {

			$default_client_exist_check = Client::where('name', 'Customer')->first();

			if (!empty($default_client_exist_check)) {

				return	$client_id = $default_client_exist_check->id;
			} else {

				$client_data = [

					'name' => 'Customer',
					'mobile' => '01XXXXXXXXX',
					'email' => 'customer@gmail.com',
					'address' => 'customer',
					'upazila_id' => 0,
					'union_id' => 0,
					'created_by' => Auth::user()->record_id,
					'created_by_ip' => \Request::ip(),

				];

				$client_id = DB::table('clients')->insertGetId($client_data);

				$user_data = [

					'record_id' => $client_id,
					'username' => 'customer',
					'password' => bcrypt('customer'),
					'upazila_id' => 0,
					'union_id' => 0,
					'user_type' => 3,
					'created_by' => Auth::user()->record_id,
					'created_by_ip' => \Request::ip(),

				];

				$user_insert = DB::table('users')->insert($user_data);

				return $client_id;
			}
		}
	}

	//internal order confirm
	public function internal_order_confirm(Request $request)
	{

		//if customer mobile number set
		if ($request->customer_mobile != NULL) {

			$exist_check = Client::where('mobile', $request->customer_mobile)->first();

			if (!empty($exist_check)) {

				$client_id = $exist_check->id;
			} else {

				$client_id = $this->new_client_create($request);
			}
		} else {

			$client_id = $this->new_client_create($request);
		}


		// $shipping_address = $request->shipping_address;

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

		// Begin Transaction
		DB::beginTransaction();

		try {
			if(!empty($product_data)){
				foreach ($product_data as $value) {

					$total_amount += ($value['rate'] * $value['quantity']);

					$inventories_data[] = [
						'order_id' => $order_id,
						'product_id' => $value["id"],
						'inventory_id' => $value["inventory_id"],
						'client_id' => $client_id,
						'supplier_id' => $value["supplier_id"],
						'type' => 2,
						'debit' => $value["quantity"],
						'selling_price' => $value["rate"],
						'buying_price' => $value["buying_price"],
						'status' => 2,	// 2 = confirm
						'created_by' => Auth::user()->record_id,
						'created_by_ip' => \Request::ip(),
					];

					if ($value["quantity"] == $value["current_stock"]) {
						DB::table('inventories')->where('id', $value["inventory_id"])->update(['is_sold' => 1]);
					}
				}

				// dd($inventories_data);

				$order_data = [
					'order_id' => $order_id,
					'client_id' => $client_id,
					'total_amount' => $total_amount,
					'discount' => $request->discount_amount,
					'net_amount' => $request->net_amount,
					'origin' => 2,
					'status' => 2,	// 2 = confirm
					'shipping_address' => 'address',
					'created_by' => Auth::user()->record_id,
					'created_by_ip' => \Request::ip(),
				];

				DB::table('orders')->insert($order_data);
				DB::table('inventories')->insert($inventories_data);
			}
			// Commit Transaction
			DB::commit();

			session(["cart_product" => []]);

			return response()->json(['status' => "success", 'message' => "অর্ডারটি সফলভাবে সম্পন্ন হয়েছে।", 'order_id' => $order_id]);
		} catch (\Exception $e) {
			// Rollback Transaction
			DB::rollback();

			return response()->json(['status' => "error", 'message' => "অর্ডারটি সম্পন্ন হয়নি।", 'order_id' => NULL]);
		}
	}

	public function getStockByProductId(Request $request)
	{
		if (Auth::user()->user_type == 5) {	// shop employee
			$employee = Employe::find(Auth::user()->record_id);

			$supplier_id = $employee->shop_id;
		} else {
			$supplier_id = Auth::user()->record_id;
		}

		$total_credit = Inventory::where('type', 1)->where('product_id', $request->product_id)->where('supplier_id', $supplier_id)->sum('credit');
		$total_debit = Inventory::where('type', 2)->where('product_id', $request->product_id)->where('supplier_id', $supplier_id)->where('status', 2)->sum('debit');
		$current_stock = $total_credit - $total_debit;

		return [
			'stock' => $current_stock
		];
	}

	// order id generate
	public function order_key($serial_no, $length = 4)
	{
		return str_repeat("0", ($length - strlen($serial_no))) . $serial_no;
	}

	public function productWaste()
	{
		if (Auth::user()->user_type == 5) {	// shop employee
			$employee = Employe::find(Auth::user()->record_id);

			$supplier_id = $employee->shop_id;
		} else {
			$supplier_id = Auth::user()->record_id;
		}

		$products = Product::where('supplier_id', $supplier_id)->get();
		return view('admin.supplier.waste', compact('products'));
	}

	public function search(Request $request)
	{
		$search = $request->get('term');
		if (Auth::user()->user_type == 5) {	// shop employee
			$employee = Employe::find(Auth::user()->record_id);

			$supplier_id = $employee->shop_id;
		} else {
			$supplier_id = Auth::user()->record_id;
		}

		$query = "SELECT inventories.id,products.id AS product_id,products.name, inventories.buying_price,SUM(inventories.credit) AS total_credit FROM products INNER JOIN inventories ON inventories.product_id = products.id AND inventories.supplier_id = products.supplier_id AND inventories.is_sold !=1 AND inventories.inventory_id IS NULL WHERE (products.name LIKE '%{$search}%' OR inventories.id = '$search') AND products.supplier_id = '{$supplier_id}' AND inventories.`type`=1 AND products.deleted_at IS NULL AND inventories.deleted_at IS NULL GROUP BY inventories.id";

		$stockData = DB::select(DB::raw($query));

		// dd($query);

		$data = [];

		foreach ($stockData as $product) {
			$temp['name'] = $product->name;
			$temp['id']   = $product->id;
			$temp['product_id']   = $product->product_id;
			$temp['buying_price']   = $product->buying_price;
			$temp['total_credit'] = $product->total_credit;
			$debitQuery = "SELECT SUM(inventories.debit) AS total_debit FROM inventories WHERE inventories.`status`!=3 AND inventories.inventory_id = '{$product->id}' AND inventories.inventory_id IS NOT null AND inventories.deleted_at IS NULL GROUP BY inventories.inventory_id";
			$debit = DB::select(DB::raw($debitQuery));
			$debit = !empty($debit) ? $debit[0]->total_debit : 0;
			$temp['total_debit'] = $debit;
			$temp['current_stock'] = ($product->total_credit - $debit) >= 0 ? ($product->total_credit - $debit) : 0;
			if ($temp['current_stock'] != 0) {
				array_push($data, $temp);
			}
		}

		return response()->json($data);
	}

	public function offer()
	{
		if (Auth::user()->user_type == 5) {	// shop employee
			$employee = Employe::find(Auth::user()->record_id);

			$supplier_id = $employee->shop_id;
		} else {
			$supplier_id = Auth::user()->record_id;
		}
		$products = Product::where('supplier_id', $supplier_id)->get();
		return view('admin.supplier.offer', compact('products'));
	}

	public function offerData(Request $request)
	{
		if ($request->ajax()) {

			if (Auth::user()->user_type == 5) {	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}

			$query = DB::table('offers')->select('offers.*', 'CAT.name as category', 'SUBCAT.name as sub_category', 'products.name')->leftJoin('categories as CAT', function ($join) {
				$join->on('CAT.id', '=', 'offers.category_id');
			})->leftJoin('categories as SUBCAT', function ($join) {
				$join->on('SUBCAT.id', '=', 'offers.sub_category_id');
			})
				->join('products', function ($join) {
					$join->on('products.id', '=', 'offers.product_id');
				})
				->where('offers.shop_id', '=', $supplier_id)
				->whereNull('offers.deleted_at');

			if ($request->category_id > 0) {

				$query->where('offers.category_id', '=', $request->category_id);
			}

			if ($request->sub_category_id > 0) {

				$query->where('offers.sub_category_id', '=', $request->sub_category_id);
			}

			if ($request->product_id != 0) {
				$query->where('offers.product_id', '=', $request->product_id);
			}

			if ($request->offer_status != '') {
				$query->where('offers.offer_status', '=', $request->offer_status);
			}

			$data = $query->get();

			return Datatables::of($data)
				->addIndexColumn()

				->addColumn('created_at', function ($row) {
					return date('d-m-Y', strtotime($row->created_at));
				})->make(true);
		}
	}

	public function offerStore(Request $request)
	{
		//validation start
		$validator = Validator::make(
			$request->all(),
			[

				'category' => 'required',
				'sub_category' => 'required',
				'product' => 'required',
				'offer_type' => 'required',
				'offer_amount' => 'required',
				'offer_status' => 'required',
			],

			//validation error custom message
			[
				"category.required" => "পন্যের ক্যাটাগরি নির্বাচন করুণ",
				"sub_category.required" => "পন্যের সাব ক্যাটাগরি নির্বাচন করুণ",
				"product.required" => "পন্য নির্বাচন করুণ",
				"offer_type.required" => "অফার এর ধরন নির্বাচন করুণ",
				"offer_amount.required" => "অফার এর পরিমাণ দিন",
				"offer_status.required" => "অফার স্ট্যাটাস নির্বাচন করুণ",
			]
		);

		if ($validator->passes()) {

			if (Auth::user()->user_type == 5) {	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}

			$isExists = Offer::where('shop_id', $supplier_id)->where('product_id', $request->product)->where('offer_status', 1)->get()->count();

			if ($isExists > 0) {
				return Response::json([
					'status' => 'error',
					'message' => 'আপনি যে পন্যের জন্য অফার তৈরি করছেন সেটির জন্য ইতিমধ্যে একটি অফার চলমান রয়েছে',
				]);
			}




			if (isset($request->offer_id) && !empty($request->offer_id)) {
				$insert_data = Offer::find($request->offer_id);
				$insert_data->updated_by = Auth::user()->id;
				$insert_data->updated_by_ip = request()->ip();
			} else {
				$insert_data = new Offer();
				$insert_data->created_by = Auth::user()->id;
				$insert_data->created_by_ip = request()->ip();
			}

			$insert_data->shop_id = $supplier_id;
			$insert_data->category_id = $request->category;
			$insert_data->sub_category_id = $request->sub_category;
			$insert_data->product_id = $request->product;
			$insert_data->offer_type = $request->offer_type;
			$insert_data->offer_amount = $request->offer_amount;
			$insert_data->offer_status = $request->offer_status;

			$isSave = $insert_data->save();

			if ($isSave) {
				$response = [
					'status' => 'success',
					'message' => 'অফারটি সফল ভাবে জমা হয়েছে',
				];
			} else {
				$response = [
					'status' => 'error',
					'message' => 'দুঃখিত! আপনার অফারটি জমা হয়নি।',
				];
			}

			return Response::json($response);
		}

		return Response::json(['errors' => $validator->errors()]);
	}

	public function offerDelete(Request $request)
	{
		$data = Offer::find($request->id);
		$isDelete = $data->delete();

		if ($isDelete) {
			$response = [
				'status' => 'success',
				'message' => 'অফারটি সফল ভাবে বাতিল হয়েছে',
			];
		} else {
			$response = [
				'status' => 'error',
				'message' => 'দুঃখিত! আপনার অফারটি বাতিল হয়নি।',
			];
		}

		return Response::json($response);
	}
}
