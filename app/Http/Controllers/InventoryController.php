<?php

namespace App\Http\Controllers;

use App\Category;
use App\Inventory;
use App\Product;
use App\ProductRate;
use App\Unit;
use App\Supplier;
use App\Employe;
use Carbon\Carbon;
use PDF;
use converter;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        if (Auth::user()->user_type == 5) {	// shop employee
            $employee = Employe::find(Auth::user()->record_id);

            $supplier_id = $employee->shop_id;
        } else {
            $supplier_id = Auth::user()->record_id;
        }

        $products = Product::where('supplier_id', $supplier_id)->get();
        return view('admin.supplier.inventory.index', compact('products'));
    }

    public function data(Request $request)
    {


        if ($request->ajax()) {

            $data = $this->stockList($request);

            return Datatables::of($data)

                ->addIndexColumn()

                ->addColumn('credit', function ($row) {
                    return $row->credit;
                })

                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y', strtotime($row->created_at));
                })

                ->rawColumns(['action', 'credit', 'created_at'])

                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            "category" => "required",
            "sub_category" => "required",
            "product" => "required",
            "buying_price" => "required",
            "saling_price" => "required",
            "quantity" => "required",
        ]);

        // echo "<pre>";
        // print_r($request->all());
        // exit;

        $upazila_id = Auth::user()->upazila_id;
        $union_id = Auth::user()->union_id;
        $operation_type = "জমা";
        $status = true;

        if (Auth::user()->user_type == 5) {	// shop employee
            $employee = Employe::find(Auth::user()->record_id);

            $currentUser = $employee->shop_id;
        } else {
            $currentUser = Auth::user()->record_id;
        }

        $userIp = $request->ip();

        if (!isset($request->rate_id)) {

            $product_rate = ProductRate::where('product_id', $request->product)->where('supplier_id', $currentUser)->first();

            if (empty($product_rate)) {
                $product_rate = new ProductRate();
                $product_rate->created_by = Auth::user()->record_id;
                $product_rate->created_by_ip = $userIp;
            } else {
                $product_rate->updated_by = Auth::user()->record_id;
                $product_rate->updated_by_ip = $userIp;
            }
        } else {
            $product_rate = ProductRate::find($request->rate_id);
            $product_rate->updated_by = Auth::user()->record_id;
            $product_rate->updated_by_ip = $userIp;
        }
        $product_rate->supplier_id = $currentUser;
        $product_rate->product_id = $request->product;
        $product_rate->rate = $request->saling_price;

        $rateSave = $product_rate->save();

        if ($rateSave) {

            if (!isset($request->inventory_id)) {
                $inventory = new Inventory();
                $inventory->created_by = Auth::user()->record_id;
                $inventory->created_by_ip = $userIp;
            } else {
                $inventory = Inventory::find($request->inventory_id);
                $inventory->updated_by = Auth::user()->record_id;
                $inventory->updated_by_ip = $userIp;
            }

            $inventory->type = 1; // 1 for purchase
            $inventory->supplier_id = $currentUser;
            $inventory->product_id = $request->product;
            $inventory->credit = $request->quantity;

            $inventory->is_sold = $request->quantity > 0 ? 0 : 1;

            $inventory->buying_price = $request->buying_price;
            $inventory->status = 0; // 0 for purchase
            $inventory->selling_price = $request->saling_price;
            $inventorySave = $inventory->save();

            if ($inventorySave) {
                $status = true;
            } else {
                $status = false;
            }
        } else {
            $status = false;
        }

        if ($status) {
            $msg = "আপনার পন্যটি সফল্ভাবে $operation_type হয়েছে।";
            $st = 'success';
        } else {
            $msg = "অনিবার্য কারণবশত আপনার পন্য $operation_type হয়নি";
            $st = 'error';
        }

        return [
            'status' => $st,
            'msg' => $msg,
            'inventory_id' => $inventory->id,
            'product_id'  =>  $inventory->product_id
        ];
    }

    public function edit(Request $request)
    {
        $product = Product::find($request->product_id);
        $rate = ProductRate::find($request->rate_id);
        $inventory = Inventory::find($request->inventory_id);
        return [
            'product' => $product,
            'rate' => $rate,
            'inventory' => $inventory,
        ];
    }

    public function delete(Request $request)
    {
        $data = Inventory::find($request->id);
        $data->delete();
        return [
            "status" => "success",
            'msg' => "আপনার পন্যের স্টক সফল্ভাবে ডিলিট হয়েছে।",
        ];
    }

    public function getProductByCategory(Request $request)
    {
        $categoryId = $request->category_id;
        $products = Product::where('supplier_id', Auth::user()->record_id)->where("category_id", $categoryId)->get();
        return $products;
    }

    public function report()
    {
        $products = Product::where('supplier_id', Auth::user()->record_id)->get();
        return view('admin.supplier.inventory.stock', compact('products'));
    }
    public function stockData(Request $request)
    {

        if ($request->ajax()) {

            $data = $this->stockReport2($request);

            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('status', function ($data) {
                    if ($data['current_stock'] <= 0) {
                        $html = '<label style="color:red">আউট অফ স্টক</label>';
                    } else {
                        $html = '<label style="color:green">স্টক আছে</label>';
                    }
                    return $html;
                })
                ->rawColumns(['status'])
                ->make(true);
        }
    }

    public function opening_stock()
    {
        if (Auth::user()->user_type == 5) {	// shop employee
            $employee = Employe::find(Auth::user()->record_id);

            $supplier_id = $employee->shop_id;
        } else {
            $supplier_id = Auth::user()->record_id;
        }

        $products = Product::where('supplier_id', $supplier_id)->get();

        return view('admin.supplier.inventory.opening_stock', compact('products'));
    }

    public function opening_stock_action(Request $request)
    {
        if (Auth::user()->user_type == 5) {	// shop employee
            $employee = Employe::find(Auth::user()->record_id);

            $supplier_id = $employee->shop_id;
        } else {
            $supplier_id = Auth::user()->record_id;
        }

        $products = DB::table("products AS PT")
            ->leftJoin("inventories AS INV", function($join) use($supplier_id) {
                $join->on("PT.id", "=", "INV.product_id")
                    ->where([
                        ["PT.supplier_id", "=", $supplier_id],
                        ["PT.category_id", "=", request('filter_category')],
                        ["PT.sub_category_id", "=", request('filter_sub_category')],
                        ["INV.status", "=", 9]
                    ])
                    ->whereNull("PT.deleted_at")
                    ->whereNull("INV.deleted_at");
            })
            ->where([
                ["PT.supplier_id", "=", $supplier_id],
                ["PT.category_id", "=", request('filter_category')],
                ["PT.sub_category_id", "=", request('filter_sub_category')]
            ])
            ->whereNull("PT.deleted_at")
            ->whereNull("INV.deleted_at")
            ->select("INV.id", "PT.id AS product_id", "PT.name AS product_name", "INV.credit AS quantity", "INV.buying_price", "INV.selling_price")
            ->get();

        $category_name = Category::find($request->filter_category)->name;
        $sub_category_name = Category::find($request->filter_sub_category)->name;

        return view('admin.supplier.inventory.opening_stock_action', compact('products', 'category_name', 'sub_category_name'));
    }

    public function opening_stock_save(Request $request)
    {

        if (Auth::user()->user_type == 5) {	// shop employee
            $employee = Employe::find(Auth::user()->record_id);

            $supplier_id = $employee->shop_id;
        } else {
            $supplier_id = Auth::user()->record_id;
        }

        foreach($request->product_id as $key => $item){
            // dd($request->selling_price[$key]);

            if($request->buying_price[$key] != null &&  $request->selling_price[$key] != null && $request->quantity[$key] != null){
                // insert
                if($request->inventory_id[$key] == null){
                    $data = new Inventory();

                    $data->product_id = $item;
                    $data->supplier_id = $supplier_id;
                    $data->type = 1;
                    $data->credit = $request->quantity[$key];
                    $data->buying_price = $request->buying_price[$key];
                    $data->selling_price = $request->selling_price[$key];
                    $data->status = 9;
                    $data->created_at = Carbon::now();
                    $data->created_by = Auth::user()->id;
                    $data->created_by_ip = $request->ip();

                    $data->save();


                } else {	// update
                    $data = Inventory::find($request->inventory_id[$key]);

                    $data->product_id = $item;
                    $data->supplier_id = $supplier_id;
                    $data->type = 1;
                    $data->credit = $request->quantity[$key];
                    $data->buying_price = $request->buying_price[$key];
                    $data->selling_price = $request->selling_price[$key];
                    $data->status = 9;
                    $data->updated_at = Carbon::now();
                    $data->updated_by = Auth::user()->id;
                    $data->updated_by_ip = $request->ip();

                    $data->save();
                }
                ProductRate::updateOrCreate(
                    [
                        'product_id' =>  $item,
                        'supplier_id'=> $supplier_id,
                    ],
                    [
                        'rate'      => $request->selling_price[$key],
                        'created_by' => Auth::user()->id,
                        'created_by_ip' => request()->ip(),
                    ]);
            }
        }

        return redirect(route('stock.opening'))->with('success', 'Opening stock added successfully.');

    }

    public function DownloadStockReport(Request $request)
    {
        $products = $this->stockReport2($request);
        if (Auth::user()->user_type == 5) {	// shop employee
            $employee = Employe::find(Auth::user()->record_id);

            $supplier_id = $employee->shop_id;
        } else {
            $supplier_id = Auth::user()->record_id;
        }

        $shop = Supplier::find($supplier_id);
        $dateText = '';

        $from_date = $request->from_date != null ? date('d-m-Y', strtotime($request->from_date)) : null;
        $to_date = $request->to_date != null ? date('d-m-Y', strtotime($request->to_date)) : null;

        if ($from_date != null) {
            $dateText .= converter::en2bn($from_date) . ' হতে ';
        }
        if ($to_date != null) {
            $dateText .= converter::en2bn($to_date) . ' পর্যন্ত';
        }

        $data = [
            'shop' => $shop->shop_name,
            'shop_image' => $shop->shop_image,
            'products' => $products,
            'dateText' => $dateText
        ];

        // $pdf = PDF::loadView('download.stock', $data);
        // return $pdf->stream('Stock Report' . date('dMY') . '.pdf');
        return view('download.stock', $data);
    }

    public function stockReport_old($request)
    {

        if (Auth::user()->user_type == 5) {	// shop employee
            $employee = Employe::find(Auth::user()->record_id);

            $supplier_id = $employee->shop_id;
        } else {
            $supplier_id = Auth::user()->record_id;
        }

        $query = "SELECT products.id,products.category_id, products.sub_category_id,products.unit_id, products.name,  SUM(inventories.credit) AS total_credit , SUM(inventories.debit) AS total_debit,inventories.buying_price,product_rate.rate
        FROM products
        JOIN inventories ON inventories.product_id = products.id
        JOIN product_rate ON product_rate.product_id = products.id
        WHERE inventories.supplier_id = $supplier_id AND (inventories.status = 0 OR inventories.status = 2) AND inventories.deleted_at IS NULL ";

        if ($request->category_id > 0) {

            $query .= "AND products.category_id = $request->category_id ";
        }

        if ($request->sub_category_id > 0) {

            $query .= "AND products.sub_category_id = $request->sub_category_id ";
        }

        if ($request->product_id != 0) {
            $query .= "AND products.id = $request->product_id ";
        }
        $query .= "GROUP BY inventories.product_id ";

        $results = DB::select(DB::raw($query));
        return $results;
    }

    public function stockReport2($request)
    {
        $request->category_id = $request->category_id != null ? $request->category_id : 0;
        $request->sub_category_id = $request->sub_category_id != null ? $request->sub_category_id : 0;
        $request->product_id = $request->product_id != null ? $request->product_id : 0;
        $request->from_date = $request->from_date != null ? $request->from_date : 0;
        $request->to_date = $request->to_date != null ? $request->to_date : 0;

        if (Auth::user()->user_type == 5) {	// shop employee
            $employee = Employe::find(Auth::user()->record_id);

            $supplier_id = $employee->shop_id;
        } else {
            $supplier_id = Auth::user()->record_id;
        }

        $query = "SELECT inventories.id,products.id AS product_id,products.name,cat.name as category,cat2.name as sub_category, inventories.buying_price,product_rate.rate as selling_price,SUM(inventories.credit) AS total_credit FROM products INNER JOIN inventories ON inventories.product_id = products.id AND inventories.is_sold !=1 AND inventories.supplier_id = products.supplier_id AND inventories.inventory_id IS NULL INNER JOIN categories as cat ON cat.id = products.category_id INNER JOIN categories as cat2 ON cat2.id =products.sub_category_id INNER JOIN product_rate ON product_rate.product_id = products.id AND product_rate.supplier_id = '{$supplier_id}' AND product_rate.deleted_at IS NULL WHERE products.supplier_id = '{$supplier_id}' AND inventories.`type`=1 AND products.deleted_at IS NULL AND inventories.deleted_at IS NULL ";

        if ($request->category_id > 0) {

            $query .= "AND products.category_id = $request->category_id ";
        }

        if ($request->sub_category_id > 0) {

            $query .= "AND products.sub_category_id = $request->sub_category_id ";
        }

        if ($request->product_id != 0) {
            $query .= "AND products.id = $request->product_id ";
        }

        if ($request->from_date != 0 && $request->to_date != 0) {
            $query .= "AND date(inventories.created_at) BETWEEN '$request->from_date' AND '$request->to_date' ";
        }

        $query .= "GROUP BY inventories.product_id ";

        $stockData = DB::select(DB::raw($query));
        $data = [];
        foreach ($stockData as $product) {
            $temp['name'] = $product->name;
            $temp['category'] = $product->category;
            $temp['sub_category'] = $product->sub_category;
            $temp['name'] = $product->name;
            $temp['id']   = $product->id;
            $temp['product_id']   = $product->product_id;
            $temp['buying_price']   = $product->buying_price;
            $temp['selling_price']   = $product->selling_price;
            $temp['total_credit'] = $product->total_credit;
            $debitQuery = "SELECT SUM(inventories.debit) AS total_debit FROM inventories WHERE inventories.`status`!=3 AND inventories.product_id = '{$product->product_id}' AND inventories.inventory_id IS NOT null AND inventories.deleted_at IS NULL ";

            if ($request->from_date != 0 && $request->to_date != 0) {
                $query .= "AND date(inventories.created_at) BETWEEN '$request->from_date' AND '$request->to_date' ";
            }

            $debitQuery .= "GROUP BY inventories.product_id";

            $debit = DB::select(DB::raw($debitQuery));
            $debit = !empty($debit) ? $debit[0]->total_debit : 0;
            $temp['total_debit'] = $debit;
            $temp['current_stock'] = ($product->total_credit - $debit) >= 0 ? ($product->total_credit - $debit) : 0;
            $temp['total_stock'] = $temp['current_stock'] * $product->buying_price;

            array_push($data, $temp);
        }

        return $data;
    }

    public function stockList($data, $type = '1')
    {

        if (Auth::user()->user_type == 5) {	// shop employee
            $employee = Employe::find(Auth::user()->record_id);

            $supplier_id = $employee->shop_id;
            $supplier = Supplier::find($employee->shop_id);
            $upazila_id = $supplier->upazila_id;
            $district_id = $supplier->district_id;
        } else {
            $supplier_id = Auth::user()->record_id;
            $upazila_id = Auth::user()->upazila_id;
            $district_id = Auth::user()->district_id;
        }

        $results = DB::table('products')
            ->select('products.name', 'cat.name as category', 'sub_cat.name as sub_category', 'inv.id as inventory_id', 'inv.credit', 'inv.debit', 'inv.selling_price as saling_price', 'rate.id as rate_id', 'products.id', 'sup.name as supplier', 'inv.created_at', 'inv.buying_price','inv.is_sold')

            ->join('inventories as inv', function ($join) {
                $join->on('inv.product_id', '=', 'products.id')
                    ->whereNotNull('inv.credit');
            })
            ->join('product_rate as rate', function ($join) {
                $join->on('rate.product_id', '=', 'products.id');
            })
            ->join('users', function ($join) {
                $join->on('users.record_id', '=', 'products.supplier_id')
                    ->where('users.user_type', '=', '2')->whereOr('users.user_type', '=', '5');
            })
            ->join('suppliers as sup', function ($join) {
                $join->on('sup.id', '=', 'users.record_id');
            })
            ->join('categories as cat', function ($join) {
                $join->on('cat.id', '=', 'products.category_id');
            })
            ->join('categories as sub_cat', function ($join) {
                $join->on('sub_cat.id', '=', 'products.sub_category_id');
            })
            ->where("sup.upazila_id", '=', $upazila_id)
            ->where('sup.district_id', '=', $district_id)
            ->where('inv.type', '=', $type)
            ->where('products.supplier_id', '=', $supplier_id)
            ->whereNull('inv.deleted_at')
            ->whereNull('products.deleted_at')
            ->whereNull('sup.deleted_at')
            ->whereNull('rate.deleted_at')
            ->whereNull('users.deleted_at');

        if ($data->category_id > 0) {

            $results->where('products.category_id', '=', $data->category_id);
        }

        if ($data->sub_category_id > 0) {

            $results->where('products.sub_category_id', '=', $data->sub_category_id);
        }

        if ($data->product_id != 0) {
            $results->where('products.id', '=', $data->product_id);
        }
        if($data->from_date != 0){
            $results->whereDate('inv.created_at','>=', $data->from_date);
        }
        if($data->to_date != 0){
            $results->whereDate('inv.created_at','<=', $data->to_date);
        }

        $output = $results->orderBy('inv.id', 'desc')->get();
        return $output;
    }


    public function productWaste()
    {
        if (Auth::user()->user_type == 5) {	// shop employee
            $employee = Employe::find(Auth::user()->record_id);

            $supplier_id = $employee->shop_id;
        } else {
            $supplier_id = Auth::user()->record_id;
        }
        $products = Product::where('supplier_id', $supplier_id)->get();
        return view('admin.supplier.waste', compact('products'));
    }

    public function productWasteStore(Request $request)
    {

        $request->validate([
            "category" => "required",
            "sub_category" => "required",
            "product" => "required",
            "quantity" => "required",
        ]);

        // echo "<pre>";
        // print_r($request->all());
        // exit;

        $upazila_id = Auth::user()->upazila_id;
        $union_id = Auth::user()->union_id;
        $operation_type = "জমা";
        $status = true;

        if (Auth::user()->user_type == 5) {	// shop employee
            $employee = Employe::find(Auth::user()->record_id);

            $currentUser = $employee->shop_id;
        } else {
            $currentUser = Auth::user()->record_id;
        }


        $userIp = $request->ip();

        if (!isset($request->inventory_id)) {
            $inventory = new Inventory();
            $inventory->created_by = Auth::user()->id;
            $inventory->created_by_ip = $userIp;
        } else {
            $inventory = Inventory::find($request->inventory_id);
            $inventory->updated_by = Auth::user()->id;
            $inventory->updated_by_ip = $userIp;
            $operation_type = "আপডেট";
        }
        $inventory->type = 3; // 3 for waste
        $inventory->status = 0; // 0 for  waste
        $inventory->supplier_id = $currentUser;
        $inventory->product_id = $request->product;
        $inventory->debit = $request->quantity;
        $inventorySave = $inventory->save();

        if ($inventorySave) {
            $status = true;
        } else {
            $status = false;
        }

        if ($status) {
            $msg = "আপনার পন্যটি সফল্ভাবে $operation_type হয়েছে।";
            $st = 'success';
        } else {
            $msg = "অনিবার্য কারণবশত আপনার পন্য $operation_type হয়নি";
            $st = 'error';
        }

        return [
            'status' => $st,
            'msg' => $msg,
        ];
    }

    public function productWasteData(Request $request)
    {

        if ($request->ajax()) {

            $data = $this->stockList($request, '3');

            return Datatables::of($data)

                ->addIndexColumn()

                ->addColumn('credit', function ($row) {
                    return $row->credit;
                })

                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y', strtotime($row->created_at));
                })

                ->rawColumns(['action', 'credit', 'created_at'])

                ->make(true);
        }
    }

    public function WasteEdit(Request $request)
    {
        $product = Product::find($request->product_id);
        $inventory = Inventory::find($request->inventory_id);
        return [
            'product' => $product,
            'inventory' => $inventory,
        ];
    }

    public function WasteDelete(Request $request)
    {
        $data = Inventory::find($request->id);
        $data->delete();
        return [
            "status" => "success",
            'msg' => "আপনার পন্যের স্টক সফল্ভাবে ডিলিট হয়েছে।",
        ];
    }

    public function barcodeGenerate(Request $request)
    {
        if (Auth::user()->user_type == 5) {	// shop employee
            $employee = Employe::find(Auth::user()->record_id);

            $supplier_id = $employee->shop_id;
        } else {
            $supplier_id = Auth::user()->record_id;
        }
        $product = Product::find($request->product_id);
        $rate = ProductRate::where('product_id', $product->id)->where('supplier_id', $supplier_id)->first()->rate;
        $data = [
            'product_name' => $product->name,
            'price' => $rate,
            'inventory_id'    => $request->inventory_id,
            'qty' 		   => $request->qty
        ];
        $pdf = PDF::loadView('admin.barcode', $data);
        return $pdf->stream($product->name . '' . date('dMY') . 'document.pdf');
    }

    public function BulkBarcode()
    {
        return view('admin.supplier.inventory.bulk_barcode');
    }
    public function GetProductByFiltering2(Request $request)
    {

        $products = $data;

        return response()->json([
            'status' => count($products)>0 ? 'success' : 'error',
            'msg' => count($products)>0 ? 'পন্য পাওয়া গিয়েছে' : 'আপনার সার্চ কৃত ক্যাটাগরির কোন পন্য খুজে পাওয়া যায়নি',
            'products' => count($products)>0 ? $products : []
        ]);

    }
    public function GetProductByFiltering(Request $request)
    {
        $request->category_id = $request->category_id != null ? $request->category_id : 0;
        $request->sub_category_id = $request->sub_category_id != null ? $request->sub_category_id : 0;
        $request->product_id = $request->product_id != null ? $request->product_id : 0;
        $request->from_date = $request->from_date != null ? $request->from_date : 0;
        $request->to_date = $request->to_date != null ? $request->to_date : 0;

        if (Auth::user()->user_type == 5) {	// shop employee
            $employee = Employe::find(Auth::user()->record_id);

            $supplier_id = $employee->shop_id;
        } else {
            $supplier_id = Auth::user()->record_id;
        }

        $query = DB::table('inventories as inv')
            ->select('pdt.name','inv.credit','inv.id','rate.rate as selling_price','pdt.id as product_id','inv.buying_price')
            ->join('products as pdt',function($join){
                $join->on('pdt.id','=','inv.product_id');
                $join->on('pdt.supplier_id','=','inv.supplier_id');
                $join->whereNull('pdt.deleted_at');
            })
            ->join('product_rate as rate',function($join){
                $join->on('rate.product_id','=','pdt.id');
                $join->on('rate.supplier_id','=','pdt.supplier_id');
                $join->whereNull('rate.deleted_at');
            })
            ->where('inv.supplier_id','=',$supplier_id)
            ->where('inv.credit','!=',0)
            ->where('inv.is_sold','!=',1)
            ->whereNull('inv.inventory_id')
            ->whereNull('inv.deleted_at');

        if ($request->from_date != 0) {
            $query->whereDate('inv.created_at','>=', $request->from_date);
        }
        if ($request->to_date != 0) {
            $query->whereDate('inv.created_at','<=', $request->to_date);
        }
        if ($request->category_id != 0) {
            $query->where('pdt.category_id','=',$request->category_id);
        }
        if ($request->sub_category_id != 0) {
            $query->where('pdt.sub_category_id','=',$request->sub_category_id);
        }

        $stockData = $query->orderBy('pdt.name','ASC')->get();


        $data = [];
        foreach ($stockData as $product) {
            $temp['name'] = $product->name;
            $temp['id']   = $product->id;
            $temp['product_id']   = $product->product_id;
            $temp['buying_price']   = $product->buying_price;
            $temp['selling_price']   = $product->selling_price;
            $temp['total_credit'] = $product->credit;
            $debitQuery = DB::table('inventories')
                ->select(DB::raw('SUM(inventories.debit) AS total_debit'))
                ->where('status','!=',3)
                ->where('product_id','=',$product->product_id)
                ->whereNotNull('inventory_id')
                ->where('inventory_id','=',$product->id)
                ->whereNull('deleted_at');
            if ($request->from_date != 0) {
                $query->whereDate('created_at','>=', $request->from_date);
            }
            if ($request->to_date != 0) {
                $query->whereDate('created_at','<=', $request->to_date);
            }


            $debit = $debitQuery->GroupBy('inventory_id')->first();

            $debit = !empty($debit) ? $debit->total_debit : 0;
            $temp['total_debit'] = $debit;
            $temp['current_stock'] = ($product->credit - $debit) >= 0 ? ($product->credit - $debit) : 0;
            $temp['total_stock'] = $temp['current_stock'] * $product->buying_price;

            array_push($data, $temp);
        }

        $products = $data;

        return response()->json([
            'status' => count($products)>0 ? 'success' : 'error',
            'msg' => count($products)>0 ? 'পন্য পাওয়া গিয়েছে' : 'আপনার সার্চ কৃত ক্যাটাগরির কোন পন্য খুজে পাওয়া যায়নি',
            'products' => count($products)>0 ? $products : []
        ]);

    }
   
	public function DownloadBulkBarcode (Request $request)
	{
		$data = [];

		for ($i=0; $i < count($request->inventory_id); $i++) { 
			if($request->quantity != null || $request->quantity != 0){
				for ($j=0; $j < $request->quantity[$i]; $j++) { 
					$tempArray = [
						'name' => $request->product_name[$i],
						'price' => $request->selling_price[$i],
						'inventory_id' => $request->inventory_id[$i]
					];
					array_push($data, $tempArray);
				}
			}
			
		}



		$products = [
			'data' => $data
		];
		$pdf = PDF::loadView('admin.bulk_barcode', $products);
		return $pdf->stream('Barcode' . date('dMY') . '.pdf');


	}
}
