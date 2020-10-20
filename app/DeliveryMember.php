<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryMember extends Model 
{

    protected $table = 'delivery_members';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    //delivery member list
    public static function deliveryman_list($receive){

        $supplier_id = Auth::user()->record_id;

    	$query = DB::table('delivery_members as DELVM')
    			
    			->select('DELVM.id', 'DELVM.name', 'DELVM.mobile', 'DELVM.email', 'DELVM.nid', 'DELVM.address', 'DIS.en_name as district_name', 'DIS.id as district_id', 'UPZ.en_name as upazila_name', 'UPZ.id as upazila_id', 'UGS.username', 'UGS.id as user_id')

    			->join('users AS UGS', function($join) use ($supplier_id) {

                    $join
                        // ->on('UGS.upazila_id', '=', 'DELVM.upazila_id')
    					// ->on('UGS.union_id', '=', 'DELVM.union_id')
    					->on('UGS.record_id', '=', 'DELVM.id')
    					->whereNull('UGS.deleted_at')
    					->where('UGS.user_type', 4)
    					->where('DELVM.supplier_id', $supplier_id);
    			})

    			->leftjoin('bd_locations AS DIS', 'DIS.id', '=', 'DELVM.district_id')

    			->leftjoin('bd_locations AS UPZ', 'UPZ.id', '=', 'DELVM.upazila_id')
                
                ->where('DELVM.supplier_id', $supplier_id)
                ->whereNull('DELVM.deleted_at')
                
                ->when($receive->upazila_id > 0, function($query) use ($receive) {
                    $query->Where("DELVM.district_id", $receive->district_id);
                })
                
                ->when($receive->union_id > 0, function($query) use ($receive) {
                    $query->Where("DELVM.upazila_id", $receive->upazila_id);
                })

    			->get();

    		return $query;
    }
}