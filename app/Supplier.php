<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Supplier extends Model 
{

    protected $table = 'suppliers';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];


    //supplier list
    public static function supplier_list($receive){

    	$query = DB::table('suppliers as SUPP')
    			
    			->select('SUPP.id', 'SUPP.name', 'SUPP.shop_name', 'SUPP.mobile', 'SUPP.email', 'SUPP.address', 'UGS.username', 'UGS.id as user_id', 'UPZ.en_name as upazila_name', 'UP.en_name as district_name', 'SUPT.name as supplier_type',"SUPP.status")
    			
    			->leftjoin('users AS UGS', function($join){

    				$join->on('UGS.upazila_id', '=', 'SUPP.upazila_id')
    					->on('UGS.district_id', '=', 'SUPP.district_id')
    					->on('UGS.record_id', '=', 'SUPP.id')
                        ->where('UGS.deleted_at',  NULL)
                        ->where('UGS.user_type',  2);
                        
    			})

    			->join('supplier_types AS SUPT', 'SUPT.id', '=', 'SUPP.supplier_types')

    			->leftjoin('bd_locations AS UPZ', 'UPZ.id', '=', 'SUPP.upazila_id')
    			->leftjoin('bd_locations AS UP', 'UP.id', '=', 'SUPP.district_id')
                
                ->where('SUPP.deleted_at', NULL)

    			
                ->where(function ($query) use ($receive) {

                    if($receive->district_id > 0){

                        $query->Where("SUPP.district_id", $receive->district_id);

                    }

                    if($receive->upazila_id > 0){

                        $query->Where("SUPP.upazila_id", $receive->upazila_id);

                    }
                    if($receive->status != null){

                        $query->Where("SUPP.status", $receive->status);

                    }
                    // else{

                    //     $query->Where("SUPP.status", 1)
                    //     ->orWhere("SUPP.status", 0);
                             
                    // }
                    
                })
    			->get();

    		return $query;
    }


    //supplier edit data
    public static function supplier_edit_data($id = null){

        $data = DB::table('suppliers AS SUPP')

                ->select('SUPP.*', 'UGS.username', 'UGS.id as user_id','UGS.role_id')

                ->join('users AS UGS', function($join){

                    $join->on('UGS.upazila_id', '=', 'SUPP.upazila_id')
                        ->on('UGS.district_id', '=', 'SUPP.district_id')
                        ->on('UGS.record_id', '=', 'SUPP.id');
                        
                })

                ->where('SUPP.id', $id)
                ->first();
        
        return $data;
    }

    
    public function Upazila(){
        return $this->hasOne('App\Location','id', 'upazila_id');
    }    
    public function Union(){
        return $this->hasOne('App\Location','id', 'union_id');
    }

    public function Type(){
        return $this->hasOne('App\SupplierType','id', 'supplier_types');
    }    

}