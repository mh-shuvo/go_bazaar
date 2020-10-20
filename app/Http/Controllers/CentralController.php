<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\User;
use App\Traits\Sms;
use App\Location;
use App\Farmer;
use App\Category;
use App\Supplier;
use App\Contact;
use App\Client;
use App\Order;
use App\Complain;
use App\DeliveryMember;
use App\SuperAdmin;
use App\CentralUser;
use Carbon\Carbon;
use DataTables;
use DB;
use Response;
use Session;


class CentralController extends Controller
{
    public function dashboard(Request $request)
    {
                //get total supplier
        $total_supplier = $this->total_supplier();

        //get total client
        $total_clients = $this->total_clients();

        //total orders
        $total_orders = $this->total_orders();

        //today orders
        $today_orders = $this->today_orders();
        //total sale
        $total_sale = $this->TotalSale();

        return view('central.dashboard')->with([
            'total_supplier'       => $total_supplier,
            'total_clients'        => $total_clients,
            'total_orders'         => $total_orders,
            'today_orders'         => $today_orders,
            'total_sale'           => number_format($total_sale),
        ]);
    }
    public function customer_list(Request $request)
    {
        if($request->ajax()){

            $request->district_id = Auth::user()->district_id;
            $request->upazila_id  = !empty(Auth::user()->upazila_id) ? Auth::user()->upazila_id : $request->upazila_id;
            $data = Client::client_list($request);
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        else{
            $districts = Location::get_all_location();
            return view('central.customer_list',compact('districts'));
        }
    }
    public function supplier_list(Request $request)
    {
        if($request->ajax()){
            $request->district_id = Auth::user()->district_id;
            $request->upazila_id  = !empty(Auth::user()->upazila_id) ? Auth::user()->upazila_id : $request->upazila_id;
            $data = Supplier::supplier_list($request);
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        else{
            $districts = Location::get_all_location();
            return view('central.supplier_list',compact('districts'));
        }
    }
    public function order_list(Request $request)
    {

        $district_id = Auth::user()->district_id;
        $upazila_id  = !empty(Auth::user()->upazila_id) ? Auth::user()->upazila_id : (isset($request->upazila_id) ? $request->upazila_id : 0);

        if($request->ajax()){
            // dd($request);

            $query = DB::table('inventories')
                    ->select('inventories.order_id','inventories.status','clients.name','clients.mobile','clients.address as shipping_address','clients.email','loc.en_name as district_name','suppliers.shop_name',DB::raw('SUM(inventories.debit * inventories.selling_price) AS order_amount'),'orders.origin')
                    ->join('clients',function($join){
                        $join->on('clients.id','=','inventories.client_id');
                    })
                    ->join('orders',function($join){
                        $join->on('orders.order_id','=','inventories.order_id');
                    })
                    ->join('suppliers',function($join){
                        $join->on('suppliers.id', '=', 'inventories.supplier_id');
                    })
                    ->leftjoin('bd_locations as loc',function($join){
                        $join->on('loc.id', '=', 'suppliers.district_id');
                    })
                    ->where('inventories.type', '=',' 2')
                    ->whereNull('inventories.deleted_at');

            if ($request->status != 0) {
                $query->where('inventories.status','=',$request->status);
            }
            if ($request->district_id != 0) {
                $query->where('suppliers.district_id','=',$district_id);
            }
            if ($request->upazila_id != 0) {
                $query->where('suppliers.upazila_id','=',$upazila_id);
            }
            if ($request->supplier_id != 0) {
                $query->where('inventories.supplier_id','=',$request->supplier_id);
            }
            $orders = $query->groupBy('inventories.order_id')->orderBy('orders.id', 'DESC')->get();

            return Datatables::of($orders)

                ->addIndexColumn()

                ->addColumn('action', function ($row) {

                    $btn = '<a href="'.route("order_details",$row->order_id).'" class="btn btn-info btn-sm" target="_blank">বিস্তারিত</a>';

                    return $btn;

                })

                ->rawColumns(['action'])

                ->make(true);
        }
        else{
            $districts = Location::get_all_location();
            $suppliers = Supplier::where('district_id',$district_id)->whereOr('upazila_id',$upazila_id)->get();
            return view('central.order_list',compact('districts','suppliers'));
        }
    }
    public function contact(Request $request)
    {
       
        return view('central.contact_list');
    }
    public function complain(Request $request)
    {
            $district_id = Auth::user()->district_id;
            $upazila_id  = !empty(Auth::user()->upazila_id) ? Auth::user()->upazila_id : 0;

            if($request->ajax()){

            $query = DB::table('complains as cmpl')
                    ->select('cmpl.*','clnt.name as customer','spl.name as supplier')
                    ->join('suppliers as spl',function($join) use ($district_id,$upazila_id){
                        $join->on('spl.id','=','cmpl.supplier_id');
                        $join->whereNull('spl.deleted_at');
                        $join->where('spl.district_id','=',$district_id);
                        $join->whereOr('spl.upazila_id','=', $upazila_id);
                    })
                    ->join('clients as clnt',function($join){
                        $join->on('clnt.id','=','cmpl.client_id');
                        $join->whereNull('clnt.deleted_at');
                    })
                    ->where('cmpl.reply_type',0)->where('cmpl.reply_from',0);
            
            if($request->supplier_id!=0){
                $query->where('cmpl.supplier_id','=',$request->supplier_id);
            }

            if($request->from_date != 0 && $request->to_date){
                $start = date("Y-m-d",strtotime($request->from_date));
                $end = date("Y-m-d",strtotime($request->to_date."+1 day"));
                $query->whereBetween('cmpl.created_at',[$start,$end]);
            }

            $data = $query->orderBy('id','desc')->get();

            return Datatables::of($data)

                ->addIndexColumn()

                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y', strtotime($row->created_at));
                })

                ->addColumn('action', function ($row) {
                    return '';
                })

                ->rawColumns(['action', 'created_at',])

                ->make(true);
        }
        else{
            $suppliers = Supplier::where('district_id',$district_id)->whereOr('upazila_id',$upazila_id)->get();
            return view('central.complain',compact('suppliers'));        
        }
    }

    public function total_supplier()
    {
        $district_id = Auth::user()->district_id;
        $upazila_id  = !empty(Auth::user()->upazila_id) ? Auth::user()->upazila_id : 0;

        $total_suuplier = Supplier::where('district_id',$district_id)->whereOr('upazila_id',$upazila_id)->count();

        return $total_suuplier;
    }

    //total client
    public function total_clients()
    {

        $district_id = Auth::user()->district_id;
        $upazila_id  = !empty(Auth::user()->upazila_id) ? Auth::user()->upazila_id : 0;

        $data = Client::where('district_id',$district_id)->whereOr('upazila_id',$upazila_id)->count();

        return $data;
    }

    //total orders
    public function total_orders()
    {
        $district_id = Auth::user()->district_id;
        $upazila_id  = !empty(Auth::user()->upazila_id) ? Auth::user()->upazila_id : 0;

        $data = DB::table('inventories')
                ->select('inventories.*')
                ->join('suppliers',function($join) use($district_id,$upazila_id){
                    $join->on('suppliers.id','=','inventories.supplier_id');
                    $join->where('suppliers.district_id','=',$district_id);
                    $join->whereOr('suppliers.upazila_id','=',$upazila_id);
                    $join->whereNull('suppliers.deleted_at');
                })
                ->whereNotNull('order_id')
                ->whereNull('inventories.deleted_at')
                ->groupBy('inventories.order_id')->get()->count();

        return $data;
    }

    //today orders
    public function today_orders()
    {

        $district_id = Auth::user()->district_id;
        $upazila_id  = !empty(Auth::user()->upazila_id) ? Auth::user()->upazila_id :0;

        $data = DB::table('inventories')
                ->join('suppliers',function($join) use($district_id,$upazila_id){
                    $join->on('suppliers.id','=','inventories.supplier_id');
                    $join->where('suppliers.district_id','=',$district_id);
                    $join->whereOr('suppliers.upazila_id','=',$upazila_id);
                    $join->whereNull('suppliers.deleted_at');
                })
                ->whereNotNull('order_id')
                ->whereNull('inventories.deleted_at')
                ->whereDate('inventories.created_at', Carbon::today())
                ->groupBy('inventories.order_id')->get()->count();

        return $data;
    }

    public function TotalSale()
    {
        $district_id = Auth::user()->district_id;
        $upazila_id  = !empty(Auth::user()->upazila_id) ? Auth::user()->upazila_id : 0;

        $data = DB::table('inventories')
                ->select(DB::raw('SUM(selling_price * debit) as total_sale_price'), DB::raw('SUM(buying_price * debit) as total_purchase_price'))
                ->join('suppliers',function($join) use($district_id,$upazila_id){
                    $join->on('suppliers.id','=','inventories.supplier_id');
                    $join->where('suppliers.district_id','=',$district_id);
                    $join->whereOr('suppliers.upazila_id','=',$upazila_id);
                    $join->whereNull('suppliers.deleted_at');
                })
                ->where('inventories.type', 2)
                ->where('inventories.status', 2)
                ->whereNotNull('order_id')
                ->whereNull('inventories.deleted_at')
                ->groupBy('inventories.order_id')->get();

    
        // dd(DB::getQueryLog());
        $total_sale = 0;
        $total_purchase = 0;
        foreach ($data as  $item) {
            $total_sale += $item->total_sale_price;
        }

        return $total_sale;
    }
}
