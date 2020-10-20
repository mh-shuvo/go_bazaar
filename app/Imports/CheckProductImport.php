<?php

namespace App\Imports;

namespace App\Imports;

use App\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CheckProductImport implements ToCollection, WithValidation,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows){}

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
