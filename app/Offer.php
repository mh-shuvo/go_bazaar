<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Offer extends Model
{
     protected $table = 'offers';
     use SoftDeletes;

    protected $dates = ['deleted_at'];
}
