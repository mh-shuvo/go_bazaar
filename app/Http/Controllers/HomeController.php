<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\User;
use App\Supplier;
use App\Client;
use App\Order;
use App\Location;
use App\DeliveryMember;
use App\DeliveryMan;
use App\Employe;
use App\Inventory;
use DataTables;
use App\SuperAdmin;
use App\Complain;
use App\EcommerceSetup;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Converter;
class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// $this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{

		// dd(session("widget"));

		//if user as a client then redirect website
		if (Auth::user()->user_type == 3) {
			return redirect()->route('index');
		}
		if (Auth::user()->user_type == 6) {
			return redirect()->route('central.dashboard');
		}

		return view('admin.home');
	}
	
	public function homePageData(){
		//get total supplier
		$total_supplier = $this->total_supplier();

		//get total client
		$total_clients = $this->total_clients();

		//total orders
		$total_orders = $this->total_orders();

		//today orders
		$today_orders = $this->today_orders();

		//total pending order for delivery man
		$total_pending_orders = $this->total_pending_orders();

		//total confirm order for delivery man
		$total_confirm_orders = $this->total_confirm_orders();

		//total reject order for delivery man
		$total_reject_orders = $this->total_reject_orders();

		//current stock
		$current_stock = $this->TotalStock();

		//total sale
		$total_sale = $this->TotalSale();
		
		return response()->json([
			'total_supplier'       => Converter::en2bn($total_supplier),
			'total_clients'        => Converter::en2bn($total_clients),
			'total_orders'         => Converter::en2bn($total_orders),
			'today_orders' 		   => Converter::en2bn($today_orders),
			'total_pending_orders' => Converter::en2bn($total_pending_orders),
			'total_confirm_orders' => Converter::en2bn($total_confirm_orders),
			'total_reject_orders'  => Converter::en2bn($total_reject_orders),
			'current_stock'        => Converter::en2bn(number_format($current_stock)),
			'total_sale'           => Converter::en2bn(number_format($total_sale)),
			'today_sale'		   => Converter::en2bn(number_format($this->TodaySale()))
		]);
	}

	public function impersonate($id)
    {
		$user = User::find($id);
		$admin_user = Auth::id();
        if ($user) {
			Auth::login($user);

			if ($user->role_id) {
				$acl = DB::table("acl")->where("id", $user->role_id)->first();
	
				$widget = json_decode($acl->widget, true);
			} else {
				$widget = [];
			}
	
			session(
				[
					'widget' => $widget,
					'auth_user_id' => $admin_user
				]
			);
            return redirect()->intended('home');
        }
        else{
            return redirect()->back();
        }
    }

    public function impersonateLeave()
    {
		$user_id = session()->get('auth_user_id');
        $user = User::where('user_type','=',1)->where('id',$user_id)->first();
        if ($user) {
			Auth::login($user);
			session()->forget('auth_user_id','widget');
            return redirect()->intended('home');
        }
        else{
            return redirect()->back();
        }
	}


	//total supplier
	public function total_supplier()
	{

		$total_suuplier = Supplier::count();

		return $total_suuplier;
	}

	//total client
	public function total_clients()
	{

		$upazila_id = (Auth::user()->upazila_id > 0) ? Auth::user()->upazila_id : NULL;
		// $union_id = (Auth::user()->union_id > 0) ? Auth::user()->union_id : NULL;

		$query = Client::query();

		if ($upazila_id > 0) {

			$query->where('upazila_id', '=', $upazila_id);
		}

		$data = $query->count();

		return $data;
	}

	//total orders
	public function total_orders()
	{
		// dd(Auth::user());
		if (Auth::user()->user_type == 4) {	// delivery man
			$query = DeliveryMan::where('delivery_id', '=', Auth::user()->record_id);
		} else if (Auth::user()->user_type == 5) {	// shop employee
			$employee_info = Employe::find(Auth::user()->record_id);

			$query = Inventory::whereNotNull('order_id');

			if (Auth::user()->record_id != NULL) {
				$query->where('supplier_id', '=', $employee_info->shop_id);
			}

			// dd($employee_info);
		} else {
			$query = Inventory::whereNotNull('order_id');

			if (Auth::user()->record_id != NULL) {
				$query->where('supplier_id', '=', Auth::user()->record_id);
			}
		}

		$data = $query->groupBy('order_id')->get()->count();

		return $data;
	}

	//today orders
	public function today_orders()
	{

		DB::enableQueryLog();

		if (Auth::user()->user_type == 4) {	// delivery man
			$query = DeliveryMan::where('delivery_id', '=', Auth::user()->record_id);
		} else if (Auth::user()->user_type == 5) {	// shop employee
			$employee_info = Employe::find(Auth::user()->record_id);

			$query = Inventory::whereNotNull('order_id');

			if (Auth::user()->record_id != NULL) {
				$query->where('supplier_id', '=', $employee_info->shop_id);
			}

			// dd($employee_info);
		} else {

			$query = Inventory::whereNotNull('order_id');

			if (Auth::user()->record_id != NULL) {

				$query->where('supplier_id', '=', Auth::user()->record_id);
			}
		}

		$data = $query->whereDate('created_at', Carbon::today())->groupBy('order_id')->get()->count();

		return $data;
	}

	public function total_pending_orders()
	{
		DB::enableQueryLog();
		$data = DeliveryMan::where('delivery_id', '=', Auth::user()->record_id)->where('status', '=', 0)->groupBy('order_id')->get()->count();
		return $data;
	}
	public function total_confirm_orders()
	{
		DB::enableQueryLog();
		$data = DeliveryMan::where('delivery_id', '=', Auth::user()->record_id)->where('status', '=', 1)->groupBy('order_id')->get()->count();
		return $data;
	}
	public function total_reject_orders()
	{
		DB::enableQueryLog();
		$data = DeliveryMan::where('delivery_id', '=', Auth::user()->record_id)->where('status', '=', 2)->groupBy('order_id')->get()->count();
		return $data;
	}


	public function TotalStock()
	{

		$supplier_id = Auth::user()->record_id;

		$total_stock = 0;

		$query = "SELECT inventories.id,products.id AS product_id,products.name,cat.name as category,cat2.name as sub_category, inventories.buying_price,SUM(inventories.credit) AS total_credit FROM products INNER JOIN inventories ON inventories.product_id = products.id AND inventories.is_sold !=1 AND inventories.supplier_id = products.supplier_id AND inventories.inventory_id IS NULL INNER JOIN categories as cat ON cat.id = products.category_id INNER JOIN categories as cat2 ON cat2.id =products.sub_category_id WHERE inventories.`type`=1 AND products.deleted_at IS NULL AND inventories.deleted_at IS NULL";

		if (Auth::user()->user_type == 2) {


			$query .= " AND products.supplier_id = '{$supplier_id}'";
		}

		$query .= " GROUP BY inventories.id";

		$stockData = DB::select(DB::raw($query));
		foreach ($stockData as $product) {
			$debitQuery = "SELECT SUM(inventories.debit) AS total_debit FROM inventories WHERE inventories.`status`!=3 AND inventories.inventory_id = '{$product->id}' AND inventories.inventory_id IS NOT null AND inventories.deleted_at IS NULL";
			$query .= " GROUP BY inventories.inventory_id";

			$debit = DB::select(DB::raw($debitQuery));

			$debit = !empty($debit) ? $debit[0]->total_debit : 0;

			$current_stock = ($product->total_credit - $debit) >= 0 ? ($product->total_credit - $debit) : 0;
			$total_stock += $current_stock * $product->buying_price;
		}

		return $total_stock;
	}


	public function TotalSale()
	{

		// DB::enableQueryLog();
		$query = Inventory::select(DB::raw('SUM(selling_price * debit) as total_sale_price'), DB::raw('SUM(buying_price * debit) as total_purchase_price'))->where('type', 2)->where('status', 2);

		if (Auth::user()->user_type == 2) {
			$query->where('supplier_id', Auth::user()->record_id);
		}

		$total_sale_data = $query->groupBy('id')->get();
		// dd(DB::getQueryLog());
		$total_sale = 0;
		$total_purchase = 0;
		foreach ($total_sale_data as  $item) {
			$total_sale += $item->total_sale_price;
		}

		return $total_sale;
	}
	public function TodaySale()
	{

		// DB::enableQueryLog();
		$query = Inventory::select(DB::raw('SUM(selling_price * debit) as total_sale_price'), DB::raw('SUM(buying_price * debit) as total_purchase_price'))->where('type', 2)->where('status', 2)->whereDate('created_at','=', Carbon::today());

		if (Auth::user()->user_type == 2) {
			$query->where('supplier_id', Auth::user()->record_id);
		}

		$total_sale_data = $query->groupBy('id')->get();
		// dd(DB::getQueryLog());
		$total_sale = 0;
		$total_purchase = 0;
		foreach ($total_sale_data as  $item) {
			$total_sale += $item->total_sale_price;
		}

		return $total_sale;
	}
	public function support()
	{
		return view('admin.supplier.support');
	}
	public function complain(Request $request)
	{
		if($request->ajax()){
			if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}
			$data = Complain::where('supplier_id',$supplier_id)->where('reply_type',0)->where('reply_from',0)->get();
			return Datatables::of($data)

				->addIndexColumn()


				->addColumn('created_at', function ($row) {
					return date('d-m-Y', strtotime($row->created_at));
				})
				->addColumn('customer', function ($row) {
					return $row->Client->name;
				})

				->addColumn('action', function ($row) {
					return '';
				})

				->rawColumns(['action', 'customer', 'created_at'])

				->make(true);
		}
		else{
			return view('admin.supplier.complain');
		}
	}
	public function complain_reply($id)
	{
		if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}
		$complain = Complain::find($id);
		$complain_reply = Complain::where('parent_id',$id)->get();
		$supplier = Supplier::find($supplier_id);
		return view('admin.supplier.complain_reply',compact('complain','complain_reply','supplier'));
	}
	public function complain_reply_submit(Request $request)
	{
		if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}

		$complain = Complain::find($request->complain_id);

		$instance = new Complain();

		$instance->client_id = $complain->client_id;
		$instance->supplier_id = $supplier_id;
		$instance->order_id = $complain->order_id;
		$instance->parent_id = $complain->id;
		$instance->reply_type  = 1;
		$instance->reply_from  = 1;
		$instance->message = $request->message;
		$instance->created_by = $supplier_id;
		$instance->created_by_ip = $request->ip();
		$instance->save();

		return response()->JSON([
			'status' => 'success',
			'message' => 'Message Successfully Submitted'
		]);
	}
	public function ecommerceSetup(Request $request)
    {
    	if($request->ajax()){
    		if($request->district_id != 0){
    			$data = EcommerceSetup::where('district_id',$request->district_id)->get();
    		}
    		else{
    			$data = EcommerceSetup::all();
    		}
			return Datatables::of($data)

				->addIndexColumn()


				->addColumn('created_at', function ($row) {
					return date('d-m-Y', strtotime($row->created_at));
				})
				->addColumn('district', function ($row) {
					return $row->District->en_name;
				})
				->addColumn('domain', function ($row) {
					return "<a href='http://".$row->domain.".gobazaar.com.bd' target='__blnak'>http://".$row->domain.".gobazaar.com.bd</a>";
				})

				->addColumn('logo', function ($row) {
					return '<img src="./public/logo/'.$row->logo.'" style="height:50px; width:50px;">';
				})

				->addColumn('action', function ($row) {
					return '<button type="button" class="SetupEdit btn btn-info btn-sm" data-id="'.$row->id.'">এডিট</button><button type="button" class="SetupDelete btn btn-danger btn-sm" data-id="'.$row->id.'">ডিলিট</button>';
				})

				->rawColumns(['action', 'district', 'created_at','logo','domain'])

				->make(true);
    	}
    	else{
    		$locations = Location::get_all_location();
    		return view('admin.ecommerce_setup',compact('locations'));
    	}
    }
    public function ecommerceSetupStore(Request $request)
    {
    	$validator = Validator::make($request->all(),[
    		'district_id' => 'required',
    		'name'		  => 'required',
    	]);

    	$validator->sometimes('domain',['required',Rule::unique('ecommerce_setup','domain')->whereNull('deleted_at')],function($input){
    		return empty($input->domain);
    	});
    	$validator->sometimes('logo',['required'],function($input){
    		return empty($input->id);
    	});

    	if($validator->fails()){
    		return response()->json([
    			'status' => 'errors',
    			'data'   => $validator->errors()
    		]);
    	}
    	else{

    		
    		$instance = isset($request->id) ?EcommerceSetup::find($request->id):new EcommerceSetup();

    		if($request->hasFile('logo')){
    			if(!empty($instance->logo)){
    				$logo = public_path('/public/logo/'.$instance->logo);
    				file_exists($logo) ? @unlink($logo) : '';
    			}
    			$logo = $request->domain.'_'.time().'.'.$request->logo->extension();
    			$request->logo->move(public_path('/logo'),$logo);
    			$instance->logo = $logo;
    		}

    		$instance->district_id = $request->district_id;
    		$instance->name = $request->name;
    		$instance->domain = strtolower($request->domain);
    		$instance->created_by = Auth::user()->id;
    		$instance->created_by_ip = $request->ip();
    		$isSave = $instance->save();

    		$return_data = $isSave ? ['status' => 'success','msg'=>'সেটআপ সফল হয়েছে']:['status' => 'error','msg'=>'সেটআপ সফল হয়নি'];

    		return response()->json($return_data);	
    	}
    }

    public function ecommerceEdit(Request $request)
    {
    	$data = EcommerceSetup::find($request->id);
    	return response()->json([
    		'status' => !empty($data)?'success':'error',
    		'data'   =>  !empty($data)?$data:[],
    		'msg'   =>  !empty($data)?'আপনার তথ্য পাওয়া গেছে':'আপনার তথ্য পাওয়া যায়নি'
    	]);
    }

    public function ecommerceDelete(Request $request)
    {
    	$isDelete = EcommerceSetup::find($request->id)->delete();
    	return response()->json([
    		'status' => $isDelete?'success':'error',
    		'msg'   =>  $isDelete?'আপনার নির্বাচিত ই-কমার্স টি সফল্ভাবে ডিলিট হয়েছে':'অনিবার্য কারন বশত আপনার নির্বাচিত ই-কমার্স টি  সফল্ভাবে ডিলিট হয়নি'
    	]);
	}
	
	public function get_shop_by_district(Request $request){
		$data = Supplier::where('district_id',$request->district_id)->get();
		
		return response()->json([
			'status' => count($data) !=0 ? "success" : "error",
			'msg'    => count($data) !=0 ? 'তথ্য পাওয়া গিয়েছে' : 'তথ্য পাওয়া যায়নি',
			'data'   => count($data) !=0 ? $data : []
		]);
	}
}
