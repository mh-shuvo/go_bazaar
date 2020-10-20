<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class EcommerceSetup extends Model
{
     use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'ecommerce_setup';

     public function District(){
        return $this->hasOne('App\Location','id', 'district_id');
    }
}
