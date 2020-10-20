<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Order extends Model 
{

    protected $table = 'orders';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function Client(){
        return $this->belongsTo('App\Client','client_id', 'id');
    }

    //order list
    public static function order_list($receive){

    	$query = DB::table('orders AS ORD')
    			
    			->select('ORD.id', 'ORD.order_id', 'ORD.client_id', 'ORD.total_amount', 'ORD.discount', 'ORD.net_amount', 'ORD.status', 'ORD.shipping_address', 'CLNT.name', 'CLNT.email', 'CLNT.mobile', 'CLNT.address')
    			
    			->join('clients AS CLNT', 'CLNT.id', '=', 'ORD.client_id')

    			->where(function ($query) use ($receive) {

                    if($receive->status > 0){

                        $query->Where("ORD.status", $receive->status);

                    }else{
                        $query->Where("ORD.status", 1);   
                    }
                    
                })

    			->where('ORD.deleted_at', NULL)
    			// ->where('CLNT.deleted_at', NULL)

    			->get();

    	return $query;
    }

    //order details
    public static function order_details($order_id = NULL){

        //get product and supplier data
    	$data['products'] = DB::table('inventories AS INVNT')

                ->select('PRDT.picture', 'PRDT.name as product_name', 'INVNT.debit','INVNT.status', 'INVNT.selling_price','SUP.shop_name', 'SUP.address as shop_address')

                ->join('products AS PRDT', 'PRDT.id', '=', 'INVNT.product_id')

                ->join('suppliers AS SUP', 'SUP.id', '=', 'INVNT.supplier_id')

                ->where('INVNT.order_id', '=', $order_id)
                ->where('INVNT.deleted_at', NULL)
                ->get();

        //get order and client data
        $data['client'] = DB::table('orders AS ORD')

                ->select('ORD.order_id', 'ORD.shipping_address', 'ORD.created_at', 'ORD.status', 'ORD.total_amount', 'ORD.discount', 'ORD.net_amount', 'CLNT.name as client_name', 'CLNT.address as client_address', 'CLNT.mobile as client_mobile')

                ->join('clients AS CLNT', 'CLNT.id', '=', 'ORD.client_id')

                ->where('ORD.order_id', '=', $order_id)
                ->where('ORD.deleted_at', NULL)
                // ->where('CLNT.deleted_at', NULL)
                ->first(); 

        return $data;

    }


}