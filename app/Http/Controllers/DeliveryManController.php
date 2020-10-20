<?php

namespace App\Http\Controllers;

use App\cr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Facades\DB;
use App\Traits\Sms;
use App\DeliveryMan;
use App\Inventory;
use App\Order;
use App\Employe;
class DeliveryManController extends Controller
{
    use Sms;
    public function orders(){
        return view('admin.deliveryman.orders');
    }

    public function ordersData(Request $request)
    {
        if($request->ajax()){
            if(Auth::user()->user_type == 5){   // shop employee
                $employee = Employe::find(Auth::user()->record_id);

                $delivery_id = $employee->shop_id;
            } else {
                $delivery_id = Auth::user()->record_id;
            }

            $query = DB::table('delivery_info AS DI')
                    ->select(DB::raw("SUM(INV.debit * INV.selling_price) AS total_amount"),'DI.status','clients.name','clients.mobile','DI.order_id','DI.supplier_id','suppliers.name as supplier_name')
                    ->join('inventories AS INV',function($join){
                        $join->on('INV.order_id','=','DI.order_id')
                        ->on('INV.supplier_id','=','DI.supplier_id');
                    })
                    ->leftJoin('clients',function($join){
                        $join->on('clients.id','=','INV.client_id');
                    })
                    ->leftJoin('suppliers',function($join){
                        $join->on('suppliers.id','=','DI.supplier_id');
                    })
                    ->where('DI.delivery_id','=',$delivery_id)
                    ->whereNull('INV.deleted_at')
                    ->whereNull('DI.deleted_at');

            if($request->status != ''){
                $query->where('DI.status','=',$request->status);
            }

            $data = $query->groupBy('INV.order_id')->get();

            return Datatables::of($data)

                ->addIndexColumn()

                ->addColumn('order_id_field',function($row){
                    return "<a href='".route('delivery.order_details',$row->order_id)."' target='_blank'>".$row->order_id."</a>";
                })

                ->addColumn('action', function ($row) {

                  return '';

                })
                ->rawColumns(['action','order_id_field'])

                ->make(true);
        }
    }

    public function orderStatusChange(Request $request) {
        $delivery_id = Auth::user()->record_id;
        $supplier_id = $request->supplier_id;

        $inv_status = 6; // 6 for delivery man rejected this order

        if($request->status == 1){
            $inv_status = 2; // 2 for delivery man confirmed this order
        }

        $delivery_info_update = DeliveryMan::where('supplier_id', $supplier_id)->where('delivery_id', $delivery_id)->where('order_id', $request->order_id)->update(['status' => $request->status]);


        $inventory_info_update = Inventory::where('supplier_id', $supplier_id)->where('order_id', $request->order_id)->update(['status' => $inv_status]);

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

    public function order_details($order_id)
    {
        $delivery_info = DeliveryMan::where('delivery_id',Auth::user()->record_id)->where('order_id',$order_id)->first();

        $supplier_id = $delivery_info->supplier_id;

        $order = DB::table('orders')
                ->join('clients',function($join){
                    $join->on('clients.id','=','orders.client_id');
                })
                ->select('orders.*','clients.name as client_name','clients.address as client_address','clients.mobile as client_mobile')
                ->where('order_id','=',$order_id)->whereNull('orders.deleted_at')->first();

        $orderedProducts = Inventory::where('supplier_id', $supplier_id)->where('order_id', $order_id)->get();
        return view('admin.deliveryman.order_details', compact('orderedProducts', 'order','delivery_info'));
    }
}
