<?php

namespace App\Imports;

use App\Product;
use App\User;
use App\Inventory;
use App\ProductRate;
use DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
class ProductsImport implements ToCollection,WithHeadingRow,WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        $product_id;
        foreach ($rows as $row) 
        {
            if(!empty($row)){

                $product_id = $row['product_id'];
                $created_by = User::where('record_id',$row['supplier_id'])->where('user_type','2')->first()->id;
                if($product_id == null){
                    $product_id = Product::create([
                        'supplier_id' => $row['supplier_id'],
                        'category_id' => $row['category_id'],
                        'sub_category_id' => $row['sub_category_id'],
                        'name' => $row['name'],
                        'picture' => 'default.jpg',
                        'description' => $row['description'],
                        'created_by' => $created_by,
                        'created_by_ip' => request()->ip(),
                    ])->id;

                }
                ProductRate::updateOrCreate(
                            [
                                'product_id' =>  $product_id,
                                'supplier_id'=>$row['supplier_id'],
                            ],
                            [
                                'rate'      => $row['selling_price'],
                                'created_by' => $created_by,
                                'created_by_ip' => request()->ip(),
                            ]);

                Inventory::create(
                            [
                                'product_id' =>  $product_id,
                                'supplier_id'=>$row['supplier_id'],
                                'type'=> 1,
                                'status'=> 0,
                                'buying_price'=> $row['buying_price'],
                                'selling_price'=> $row['selling_price'],
                                'credit'      => $row['quantity'],
                                'created_by' => $created_by,
                                'created_by_ip' => request()->ip(),
                            ]);
            }
        }
    }
    public function rules(): array
    {
        return [
            '*.supplier_id' => 'required',
            '*.name' => 'required|unique:products',
            '*.category_id' => 'required',
            '*.sub_category_id' => 'required',
            '*.description' => 'required',
            '*.buying_price' => 'required',
            '*.selling_price' => 'required',
            '*.quantity' => 'required',
        ];
    }
}
