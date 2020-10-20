<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Location extends Model
{

    protected $table = 'bd_locations';
    public $timestamps = false;

    protected $dates = ['deleted_at'];

    //get all location by  there type
    public static function get_all_location($receive = null)
    {

        // $parent_id = isset($receive['parent_id']) ? $receive['parent_id'] : NULL;
            $type = isset($receive['type']) ? $receive['type'] : '2';


            $data = DB::table('bd_locations')->select('*')->where("type", $type);
            
            if(isset($receive['parent_id'])){
                 $data->where("parent_id", $receive['parent_id']);
            }
            $result = $data->get();
            return $result;
    }

    //location list
    public static function location_list($receive)
    {

        return DB::table('bd_locations AS DIS')

            ->select(

                DB::raw('IF(DIS.type = 2, DIS.en_name, IF(DIS.type = 3, UPZ.en_name, NULL)) as district_name'),

                DB::raw('IF(DIS.type = 3, DIS.en_name, NULL) as upazila_name'),

                'DIS.id',
                'DIS.type',
                'DIS.parent_id'
            )

            ->leftjoin('bd_locations AS UPZ', 'UPZ.id', '=', 'DIS.parent_id')

            ->where('DIS.deleted_at', NULL)

            ->where(function ($query) use ($receive) {

                if ($receive->district_id > 0) {

                    $query->Where("DIS.parent_id", $receive->district_id);
                }

                if ($receive->upazila_id > 0) {

                    $query->Where("DIS.id", $receive->upazila_id);
                }
            })

            ->where('DIS.type','!=','4')
            ->where('DIS.type','!=','5')
            ->where('DIS.type','!=','6')

            ->orderby('DIS.id', 'DESC')

            ->get();
    }
}
