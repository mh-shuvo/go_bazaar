<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model 
{

    protected $table = 'inventories';
    public $timestamps = true;
     protected $fillable = ['supplier_id','product_id','credit','type','status','buying_price','selling_price','created_by','created_by_ip'];

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    public function Category(){
        return $this->hasOne('App\Category','id', 'category_id');
    }
    public function SubCategory(){
        return $this->hasOne('App\Category','id', 'sub_category_id');
    }
    public function ProductRate(){
        return $this->belongsTo('App\ProductRate','product_id','id');
    }
    public function Product(){
        return $this->belongsTo('App\Product','product_id','id');
    }
}