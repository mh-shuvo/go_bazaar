<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Client extends Model 
{

    protected $table = 'clients';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    //client list
    public static function client_list($receive){

    	$query = DB::table('clients as CLNT')
    			
    			->select('CLNT.id', 'CLNT.name', 'CLNT.mobile', 'CLNT.email', 'CLNT.address', 'UPZ.en_name as upazila_name', 'UP.en_name as district_name', 'UGS.id as user_id')

                ->join('users as UGS', 'UGS.record_id', '=', 'CLNT.id')

    			->leftjoin('bd_locations AS UPZ', 'UPZ.id', '=', 'CLNT.upazila_id')

    			->leftjoin('bd_locations AS UP', 'UP.id', '=', 'CLNT.district_id')
                
                ->where('CLNT.deleted_at', NULL)
                ->where('UGS.deleted_at', NULL)
                ->where('UGS.user_type', 3)
    			
    			->where(function ($query) use ($receive) {

                    if($receive->district_id > 0){

                        $query->Where("CLNT.district_id", $receive->district_id);

                    }

                    if($receive->upazila_id > 0){

                        $query->Where("CLNT.upazila_id", $receive->upazila_id);

                    }

                    if($receive->supplier_id > 0){
                        $query->Where("CLNT.created_by", $receive->supplier_id);
                        $query->Where("UGS.created_by", $receive->supplier_id);
                    }
                    
                })

    			->get();

    		return $query;
    }

}