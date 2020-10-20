<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CentralUser extends Model
{
    protected $table = 'central_users';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    

}
