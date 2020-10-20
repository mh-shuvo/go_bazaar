<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductRate extends Model 
{

    protected $table = 'product_rate';
    public $timestamps = true;
     protected $fillable = ['supplier_id','product_id','rate','created_by','created_by_ip'];

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}