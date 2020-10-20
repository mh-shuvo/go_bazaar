<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Employe;
class Product extends Model 
{

    protected $table = 'products';
    public $timestamps = false;
    protected $fillable = ['supplier_id','category_id','sub_category_id','name','picture','description','created_by','created_by_ip'];

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function Category(){
        return $this->hasOne('App\Category','id', 'category_id');
    }
    public function SubCategory(){
        return $this->hasOne('App\Category','id', 'sub_category_id');
    }
    public function Unit(){
        return $this->hasOne('App\Unit','id', 'unit_id');
    }
    public function ProductRate(){
        return $this->belongsTo('App\ProductRate','product_id','id');
    }

    public static function GetCurrentStock($product_id=null,$inventory_id=null){
        if(Auth::user()->user_type == 5){   // shop employee
                $employee = Employe::find(Auth::user()->record_id);

                $supplier_id = $employee->shop_id;
        } else {
                $supplier_id = Auth::user()->record_id;
        }
        
        $creditQuery = "SELECT inventories.id,products.id AS product_id,products.name, inventories.buying_price,SUM(inventories.credit) AS total_credit FROM products INNER JOIN inventories ON inventories.product_id = products.id AND inventories.supplier_id = products.supplier_id AND inventories.inventory_id IS NULL AND inventories.is_sold !=1 WHERE products.id = $product_id AND inventories.id = $inventory_id AND products.supplier_id = '{$supplier_id}' AND inventories.`type`=1 AND products.deleted_at IS NULL AND inventories.deleted_at IS NULL GROUP BY inventories.id";

        $credit = DB::select(DB::raw($creditQuery));
        $credit = !empty($credit)?$credit[0]->total_credit:0;

        $debitQuery = "SELECT SUM(inventories.debit) AS total_debit FROM inventories WHERE inventories.`status`!=3 AND inventories.inventory_id = '{$inventory_id}' AND inventories.inventory_id IS NOT null AND inventories.deleted_at IS NULL GROUP BY inventories.inventory_id";

        $debit = DB::select(DB::raw($debitQuery));
        $debit = !empty($debit)?$debit[0]->total_debit:0;
        
        $current_stock = ($credit-$debit)>=0 ? ($credit-$debit):0;

        return $current_stock;
    }

}