<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Order;
use App\Supplier;
use App\Client;
use App\Employe;
use App\Product;
use DataTables;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\Sms;

class OrderController extends Controller {
	
	use Sms;

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return view('admin.supplier.order.index');
	}

	public function data(Request $request) {
		if ($request->ajax()) {

			if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);
				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}

			$query = DB::table('inventories')
					->select('inventories.order_id','inventories.status','clients.name as client_name','clients.mobile as client_mobile',DB::raw("SUM(inventories.debit * inventories.selling_price) AS order_amount"),'orders.origin')
					->join('clients',function($join) use($supplier_id) {
						$join->on('clients.id','=','inventories.client_id');
						$join->where('inventories.supplier_id','=',$supplier_id);
					})
					->join('orders', function($join){
						$join->on('orders.order_id','=','inventories.order_id');
					})
					->where('inventories.type','=',2)
					->where('inventories.supplier_id','=',$supplier_id)
					->whereNull('inventories.deleted_at');


			if ($request->status != 0) {
				$query->where('inventories.status','=',$request->status);
			}

			if ($request->origin != 0) {
				$query->where('inventories.status','=',$request->origin);
			}
			if($request->from_date != 0){
				$query->whereDate('orders.created_at','>=',$request->from_date);
			}
			if($request->to_date != 0){
				$query->whereDate('orders.created_at','<=',$request->to_date);
			}


			$data = $query->groupBy('inventories.order_id')->orderBy('orders.id','DESC')->get();

			return Datatables::of($data)
				->addIndexColumn()
				->addColumn('action', function ($data) {
					return '<a href="' . route('supplier.order.details', $data->order_id) . '" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> দেখুন</a>';
				})
				->rawColumns(['action', 'status'])
				->make(true);
		}

	}
	public function orderDetails($order_id) {
		if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}
		$order = DB::table('orders')
				->join('clients',function($join){
					$join->on('clients.id','=','orders.client_id');
				})
				->select('orders.*','clients.name as client_name','clients.address as client_address','clients.mobile as client_mobile')
				->where('order_id','=',$order_id)->whereNull('orders.deleted_at')->first();

		$orderedProducts = Inventory::where('supplier_id', $supplier_id)->where('order_id', $order_id)->get();
		$supplier_info = Supplier::find($supplier_id);
		return view('admin.supplier.order.order_details', compact('orderedProducts', 'order','supplier_info'));
	}

	public function orderReceipt($order_id)
	{
		if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}
		$order = DB::table('orders')
				->join('clients',function($join){
					$join->on('clients.id','=','orders.client_id');
				})
				->select('orders.*','clients.name as client_name','clients.address as client_address','clients.mobile as client_mobile')
				->where('order_id','=',$order_id)->whereNull('orders.deleted_at')->first();

		$orderedProducts = Inventory::where('supplier_id', $supplier_id)->where('order_id', $order_id)->get();
		$supplier_info = Supplier::find($supplier_id);

		return view('admin.supplier.order.order_receipt', compact('orderedProducts', 'order','supplier_info'));
	}

	public function orderStatusChange(Request $request) {
		if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}

		Inventory::where('supplier_id', $supplier_id)->where('order_id', $request->order_id)->update(['status' => $request->status]);

		$order_info = Inventory::where('order_id', $request->order_id)
		->select("order_id", DB::raw("COUNT(*) AS total_product"), DB::raw("SUM(IF(status=1,1,0)) AS pending"), DB::raw("SUM(IF(status=2,1,0)) AS confirm"), DB::raw("SUM(IF(status=3,1,0)) AS rejected"))->get()->first();
		
		// dd($order_info->total_product);

		// update order status
		$status = ($order_info->total_product == (int)$order_info->confirm) ? 2 : ($order_info->total_product == (int)$order_info->rejected ? 3 : 4);

		Order::where('order_id', $request->order_id)->update(['status' => $status]);

		// dd($request);

		// when reject order send sms
		if($request->status == 3){
			Sms::sendSms($request->client_mobile, "দুঃখিত। আপনার $request->order_id অর্ডারটি বাতিল করা হয়েছে। gobazaar.com.bd");
		}

		return ['status' => 'success'];
	}

	public function getProductStockVariation(Request $request)
	{
		if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}
		$status='error';
		$orderedProducts = Inventory::where('supplier_id', $supplier_id)->where('order_id', $request->order_id)->get();
		$data = [];
		foreach ($orderedProducts as $item) {
			$temp = [];
			$temp['product_id'] = $item->product_id;
			$temp['product_name'] = $item->Product->name;
			$stocks = DB::table('inventories')->select('inventories.id','products.name', 'inventories.buying_price')
				->join('products',function($join){
					$join->on('products.id','=','inventories.product_id')
						->on('products.supplier_id', '=', 'inventories.supplier_id');
				})
				->whereNull('inventories.deleted_at')
				->where('inventories.is_sold','=','0')
				->where('inventories.product_id', '=', $item->product_id)
				->where('inventories.supplier_id','=', $supplier_id)
				->where('inventories.type','=',1)
				->groupBy('inventories.id')->get();
			$temp['stocks'] = $stocks;
			array_push($data, $temp);
		}
		
		if(count($data) > 0){
			$status = 'success';
		}
		return ['status' => $status,'data' => $data];
	}


	public function WebOrderConfirm(Request $request)
	{
		$supplier_id = Auth::user()->record_id;
		$total_product = count($request->product_id);
		$temp = [];
		for($i = 0; $i<$total_product;$i++){

			$inventory = Inventory::where('order_id', $request->order_id)->where('product_id',$request->product_id[$i])->where('supplier_id',$supplier_id)->first();

			$inventory->inventory_id = $request->inventory_id[$i];
			$inventory->status = 4;
			$inventory->save();

			$current_stock = Product::GetCurrentStock($request->product_id[$i],$request->inventory_id[$i]);

			if($current_stock == 0){
				$reference_row = Inventory::find($request->inventory_id[$i]);
				$reference_row->is_sold = 1;
				$reference_row->save();
			}
		}

		$order_info = Inventory::where('order_id', $request->order_id)
		->select("order_id", DB::raw("COUNT(*) AS total_product"), DB::raw("SUM(IF(status=1,1,0)) AS pending"), DB::raw("SUM(IF(status=2,1,0)) AS confirm"), DB::raw("SUM(IF(status=3,1,0)) AS rejected"))->get()->first();
		

		// update order status
		$status = ($order_info->total_product == (int)$order_info->confirm) ? 2 : ($order_info->total_product == (int)$order_info->rejected ? 3 : 4);

		Order::where('order_id', $request->order_id)->update(['status' => $status]);

		return [
			'status' => 'success',
			'message' => 'অর্ডারটি কনফার্ম হয়েছে। ডেলিভারীর জন্য অপেক্ষমান আছে।'
		];

	}

	
}

