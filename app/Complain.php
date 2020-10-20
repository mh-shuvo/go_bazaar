<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complain extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function Supplier(){
        return $this->hasOne('App\Supplier','id', 'supplier_id');
    }
    public function Client(){
        return $this->hasOne('App\Client','id', 'client_id');
    }
}
