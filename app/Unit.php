<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model 
{

    protected $table = 'units';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}