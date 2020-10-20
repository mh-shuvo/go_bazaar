<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function UserType(){
        if(Auth::user()->upazila_id == null AND Auth::user()->union_id == null){
            return 1;
        }
        elseif(Auth::user()->upazila_id != null AND Auth::user()->union_id == null){
            return 2;
        }
        elseif(Auth::user()->upazila_id != null AND Auth::user()->union_id != null){
            return 3;
        }
        else{
            return 0; 
        }
    }

    public function details()
    {
        return $this->hasOne('App\Supplier','id','record_id');
    }
    public function District(){
        return $this->hasOne('App\Location','id','district_id');
    }
    public function Upazila(){
        return $this->hasOne('App\Location','id','upazila_id');
    }
}
