<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Employe extends Model
{
	public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];


      public static function employe_list($receive){

        $supplier_id = Auth::user()->record_id;

    	$query = DB::table('employes as EMP')
    			
    			->select('EMP.id', 'EMP.name', 'EMP.mobile', 'EMP.email', 'EMP.nid', 'EMP.address', 'DIS.en_name as district_name', 'DIS.id as district_id', 'UPZ.en_name as upazila_name', 'UPZ.id as upazila_id', 'UGS.username', 'UGS.id as user_id', 'acl.role_name', 'UGS.role_id')

    			->join('users AS UGS', function($join) use ($supplier_id) {

                    $join->on('UGS.record_id', '=', 'EMP.id')
						->whereNull('UGS.deleted_at')
						->where("UGS.user_type", 5)		// 5 = shop employee
    					->where('EMP.shop_id', $supplier_id);
    			})

    			->leftjoin('acl', 'acl.id', '=', 'UGS.role_id')
				
				->leftjoin('bd_locations AS DIS', 'DIS.id', '=', 'EMP.district_id')

    			->leftjoin('bd_locations AS UPZ', 'UPZ.id', '=', 'EMP.upazila_id')
                
                ->where('UGS.user_type', 5)
                ->where('EMP.shop_id', $supplier_id)
                ->whereNull('EMP.deleted_at')
                
    			->get();

    		return $query;
    }
}
