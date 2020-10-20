<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;



class Category extends Model 
{

    protected $table = 'categories';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];


    //get category list
    public static function category_list(){

        $query = DB::table('categories AS SUBCTG')
            
            ->select(

                 DB::raw('IF(SUBCTG.type = 1, SUBCTG.name, IF(SUBCTG.type = 2, CAT.name, NULL)) as category'),

                 DB::raw('IF(SUBCTG.type = 2, SUBCTG.name, NULL) as sub_category'),

                'SUBCTG.id', 'SUBCTG.type','SUBCTG.parent_id', 'SUBCTG.is_show', 'SUBCTG.is_feature', 'SUBCTG.sorting','SUBCTG.id'
            )

            ->leftjoin('categories AS CAT', 'CAT.id', '=', 'SUBCTG.parent_id')

     		->where('SUBCTG.deleted_at', NULL)

            ->get();

        return $query;

    }

}