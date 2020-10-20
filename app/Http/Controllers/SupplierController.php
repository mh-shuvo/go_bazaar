<?php

namespace App\Http\Controllers;

use App\DeliveryMan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Response;
use App\Location;
use App\DeliveryMember;
use App\Employe;
use App\Inventory;
use App\User;
use App\Supplier;
use PDF;

class SupplierController extends Controller
{
    //delivery man
    public function deliverymans(Request $request)
    {

        $locations = Location::where(['type' => 2, 'deleted_at' => NULL])->get();

        if ($request->ajax()) {

            //get deliveryman list data	
            $response = DeliveryMember::deliveryman_list($request);

            return Datatables::of($response)

                ->addIndexColumn()

                ->rawColumns(['action'])

                ->make(true);
        }

        return view('admin.deliveryman.deliveryman_list')->with('data', $locations);
    }

    //deliveryman store
    public function deliveryman_store(Request $request)
    {

        //validation start
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required'],
                'mobile' => ['required'],
                'address' => ['required'],
                'username' => ['required', Rule::unique('users', 'username')->whereNull('deleted_at')],
                'password' => 'required|min:8',


            ],
            [ //validation custom message
                "name.required" => "নাম দিন",
                "mobile.required" => "মোবাইল নাম্বার দিন",
                "address.required" => "ঠিকানা দিন",
                "username.required" => "ইউজারনেম দিন",
                "username.unique" => "ইউজারনেম পূর্বে ব্যবহার হয়েছে",
                "password.required" => "পাসওয়ার্ড দিন",
                "password.min" => "পাসওয়ার্ড কমপক্ষে ৮ সংখ্যা দিন",

            ]
        );


        //if validation success
        if ($validator->passes()) {

            //delivermember  object
            $deliveryman = new DeliveryMember();

            // dd($request);

            //deliveryman data create
            $deliveryman->upazila_id = $request->upazila_id;
            $deliveryman->district_id = $request->district_id;
            $deliveryman->supplier_id = Auth::user()->record_id;
            $deliveryman->name = $request->name;
            $deliveryman->mobile = $request->mobile;
            $deliveryman->email = $request->email;
            $deliveryman->nid = $request->nid;
            $deliveryman->address = $request->address;

            $deliveryman->created_at = Carbon::now();
            $deliveryman->created_by = Auth::user()->id;
            $deliveryman->created_by_ip = request()->ip();

            $deliveryman_save = $deliveryman->save();

            //deliveryman last insert id
            $deliveryman_id = DB::getPdo()->lastInsertId();


            //user object
            $user = new User();

            //users data create
            $user->upazila_id = $request->upazila_id;
            $user->district_id = $request->district_id;
            $user->record_id = $deliveryman_id;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->user_type = 4;

            $user->created_at = Carbon::now();
            $user->created_by = Auth::user()->id;
            $user->created_by_ip = request()->ip();

            $user_save = $user->save();

            if ($deliveryman_save && $user_save) {

                $response = ['status' => 'success', 'message' => 'ডেলিভারিম্যান সফলভাবে যুক্ত হয়েছে'];
            } else {

                $response = ['status' => 'error', 'message' => 'ডেলিভারিম্যান যুক্ত হয়নি'];
            }

            return Response::json($response);
        }

        return Response::json(['errors' => $validator->errors()]);
    }



    //deliveryman update save
    public function deliveryman_update(Request $request)
    {
        //validation start
        $validator =  validator::make(
            $request->all(),
            [
                'name' => 'required',
                'mobile' => 'required',
                'address' => 'required',
                'username' => ['required', Rule::unique('users', 'username')->ignore($request->user_id)->whereNull('deleted_at')],
                'password' => (isset($request->password)) ? ['min:8'] : '',
            ],
            [ //validation custom message
                "name.required" => "নাম দিন",
                "mobile.required" => "মোবাইল নাম্বার দিন",
                "address.required" => "ঠিকানা দিন",
                "username.required" => "ইউজারনেম দিন",
                "username.unique" => "ইউজারনেম পূর্বে ব্যবহার হয়েছে",
                "password.min" => "পাসওয়ার্ড কমপক্ষে ৮ সংখ্যা দিন",

            ]
        );




        //if validation success
        if ($validator->passes()) {

            //deliveryman  object
            $deliveryman = DeliveryMember::find($request->row_id);

            //deliveryman data create
            $deliveryman->upazila_id = $request->upazila_id;
            $deliveryman->district_id = $request->district_id;
            $deliveryman->name = $request->name;
            $deliveryman->mobile = $request->mobile;
            $deliveryman->nid = $request->nid;
            $deliveryman->email = $request->email;
            $deliveryman->address = $request->address;

            $deliveryman->updated_at = Carbon::now();
            $deliveryman->updated_by = Auth::user()->id;
            $deliveryman->updated_by_ip = request()->ip();

            $deliveryman_update = $deliveryman->save();

            //user object
            $user = User::find($request->user_id);

            //users data create
            $user->upazila_id = $request->upazila_id;
            $user->district_id = $request->district_id;
            $user->username = $request->username;

            //if insert new password
            if ($request->password != null) {
                $user->password = Hash::make($request->password);
            }

            $user->updated_at = Carbon::now();
            $user->updated_by = Auth::user()->id;
            $user->updated_by_ip = request()->ip();

            $user_update = $user->save();

            if ($deliveryman_update && $user_update) {

                $response = ['status' => 'success', 'message' => 'ডেলিভারিম্যান সফলভাবে আপডেট হয়েছে'];
            } else {

                $response = ['status' => 'error', 'message' => 'ডেলিভারিম্যান আপডেট হয়নি'];
            }

            return Response::json($response);
        }


        return Response::json(['errors' => $validator->errors()]);
    }


    //deliveryman delete
    public function deliveryman_delete(Request $request)
    {

        $deliveryman_delete = DeliveryMember::where('id', $request->id)->delete();

        $user_delete = User::where('id', $request->user_id)->delete();

        if ($deliveryman_delete && $user_delete) {

            echo json_encode(["status" => "success", "message" => "সফলভাবে ডিলিট হয়েছে"]);
        } else {

            echo json_encode(["status" => "error", "message" => "ডিলিট হয়নি"]);
        }
    }

    public function delivery_man_list(Request $request)
    {
        $response['delivery_list'] = DeliveryMember::deliveryman_list($request);

        $response['delivery_man_id'] = 0;

        if(Auth::user()->user_type == 5){   // shop employee
                $employee = Employe::find(Auth::user()->record_id);

                $supplier_id = $employee->shop_id;
            } else {
                $supplier_id = Auth::user()->record_id;
            }
        $exists = DeliveryMan::where([
            ["order_id", "=", $request->order_id],
            ["supplier_id", "=", $supplier_id]
        ])->get();

        if ($exists->isNotEmpty()) {
            $response['delivery_man_id'] = $exists->first()->delivery_id;
        }

        // $response['delivery_man_id'] = 

        return response()->json(['status' => 'success', 'data' => $response]);
    }

    public function delivery_man_order_assign(Request $request)
    {
        if (empty($request->order_id) || empty($request->delivery_man_id)) {
            return response()->json(['status' => 'error', 'message' => 'Un-authorized request.', 'data' => []]);
        }

        if(Auth::user()->user_type == 5){   // shop employee
                $employee = Employe::find(Auth::user()->record_id);

                $supplier_id = $employee->shop_id;
            } else {
                $supplier_id = Auth::user()->record_id;
            }

        // bind all query under transaction
        try {

            // invertory table status update
            Inventory::where([
                ["order_id", "=", $request->order_id],
                ["supplier_id", "=", $supplier_id]
            ])->update(['status' => 5]);    // 5 = delivery on processing

            // if another delivery man assign to this supplier order
            $existing = DeliveryMan::where([
                ["order_id", "=", $request->order_id],
                ["supplier_id", "=", $supplier_id],
                ["delivery_id", "!=", $request->delivery_man_id],
                ["status", "=", 0]
            ])->get();

            if ($existing->isNotEmpty()) {
                $id = $existing->first()->id;

                $del_exist = DeliveryMan::find($id);
                $del_exist->delete();
            }

            // delivery info adding
            $check_current = DeliveryMan::where([
                ["order_id", "=", $request->order_id],
                ["supplier_id", "=", $supplier_id],
                ["delivery_id", "=", $request->delivery_man_id]
            ])->get();

            if ($check_current->isEmpty()) {
                $data = new DeliveryMan();
                $data->created_by_ip = $request->ip();
            } else {
                $data = DeliveryMan::find($check_current->first()->id);
                $data->updated_by_ip = $request->ip();
            }

            $data->delivery_id = $request->delivery_man_id;
            $data->supplier_id = $supplier_id;
            $data->order_id = $request->order_id;

            $data->save();

            return response()->json(['status' => 'success', 'message' => 'Delivery man assign successfuly.', 'data' => []]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Adding Delivery man failed.', 'data' => []]);
        }
    }

    public function employe(Request $request)
    {
        $locations = Location::where(['type' => 2, 'deleted_at' => NULL])->get();

        $acl_data = DB::table("acl")->where("is_active", 1)->get();

        if ($request->ajax()) {

            //get deliveryman list data 
            $response = Employe::employe_list($request);

            return Datatables::of($response)

                ->addIndexColumn()

                ->rawColumns(['action'])

                ->make(true);
        }

        return view('admin.supplier.users', compact('locations', 'acl_data'));
    }

    public function employeStore(Request $request)
    {
        //validation start
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required'],
                'mobile' => ['required'],
                'address' => ['required'],

            ],
            [ //validation custom message
                "name.required" => "নাম দিন",
                "mobile.required" => "মোবাইল নাম্বার দিন",
                "address.required" => "ঠিকানা দিন",
                "username.required" => "ইউজারনেম দিন",
                "username.unique" => "ইউজারনেম পূর্বে ব্যবহার হয়েছে",
                "password.required" => "পাসওয়ার্ড দিন",
                "password.min" => "পাসওয়ার্ড কমপক্ষে ৮ সংখ্যা দিন",

            ]
        );

        $validator->sometimes('username', ['required', Rule::unique('users', 'username')->whereNull('deleted_at')], function ($input) {
            return empty($input->row_id);
        });

        $validator->sometimes('password', 'required|min:8', function ($input) {
            return empty($input->row_id);
        });


        //if validation success
        if ($validator->passes()) {

            $operation_type = "যুক্ত";

            //employee  object
            if (isset($request->row_id) && !empty($request->row_id)) {
                $employe = Employe::find($request->row_id);
                $operation_type = "আপডেট";
                $employe->updated_at = Carbon::now();
                $employe->updated_by = Auth::user()->id;
                $employe->updated_by_ip = request()->ip();
            } else {
                $employe = new Employe();
                $employe->created_at = Carbon::now();
                $employe->created_by = Auth::user()->id;
                $employe->created_by_ip = request()->ip();
            }

            //employe data create
            $employe->upazila_id = $request->upazila_id;
            $employe->district_id = $request->district_id;
            $employe->shop_id = Auth::user()->record_id;
            // $employe->user_type = $request->user_type;
            $employe->name = $request->name;
            $employe->mobile = $request->mobile;
            $employe->email = $request->email;
            $employe->nid = $request->nid;
            $employe->address = $request->address;

            $employe_save = $employe->save();

            //employe last insert id
            $employe_id = DB::getPdo()->lastInsertId();

            //user object
            if (isset($request->user_id) && !empty($request->user_id)) {
                $user = User::find($request->user_id);
                $user->updated_at = Carbon::now();
                $user->updated_by = Auth::user()->id;
                $user->updated_by_ip = request()->ip();
            } else {
                $user = new User();
                $user->record_id = $employe_id;
                $user->created_at = Carbon::now();
                $user->created_by = Auth::user()->id;
                $user->created_by_ip = request()->ip();
            }

            if (isset($request->password)) {
                $user->password = Hash::make($request->password);
            }

            //users data create
            $user->upazila_id = $request->upazila_id;
            $user->district_id = $request->district_id;
            $user->role_id = $request->role_id;
            $user->username = $request->username;
            $user->user_type = 5; // 5 = shop employee

            $user_save = $user->save();

            if ($employe_save && $user_save) {

                $response = ['status' => 'success', 'message' => 'ইউজার সফলভাবে ' . $operation_type . ' হয়েছে'];
            } else {

                $response = ['status' => 'error', 'message' => 'ইউজার ' . $operation_type . ' হয়নি'];
            }

            return Response::json($response);
        }

        return Response::json(['errors' => $validator->errors()]);
    }


    public function employeDelete(Request $request)
    {
        $employe_delete = Employe::where('id', $request->id)->delete();

        $user_delete = User::where('id', $request->user_id)->delete();

        if ($employe_delete && $user_delete) {

            echo json_encode(["status" => "success", "message" => "সফলভাবে ডিলিট হয়েছে"]);
        } else {

            echo json_encode(["status" => "error", "message" => "ডিলিট হয়নি"]);
        }
    }

    // ACL Module

    public function acl(Request $request)
    {
        return view('admin.acl.list');
    }

    public function acl_list(Request $request)
    {
        $offset = $request->start;
        $limit = $request->length;
        $draw = $request->draw;

        $data = [];

        $data['data'] = DB::table("acl")
            ->select(DB::raw("SQL_CALC_FOUND_ROWS id"), "role_name", "created_at", "updated_at")
            ->where("is_active", 1)
            ->where("type", 2)
            ->limit($limit)
            ->offset($offset)
            ->get();

        $total = DB::select(DB::raw("SELECT FOUND_ROWS() AS total"))[0]->total;
        $data['recordsTotal'] = $total;
        $data['recordsFiltered'] = $total;
        $data['draw'] = $draw;

        return response()->json($data);
    }

    public function acl_create()
    {
        return view('admin.acl.create');
    }

    public function acl_save(Request $request)
    {
        $role_name = $request->role_name;
        $widget = (array)$request->widget;

        $widget_list = [];

        foreach ($widget as $item) {

            $split = explode("_", $item);

            if (count($split) == 2) {
                if (!isset($widget_list[$split[0]])) {
                    $widget_list[$split[0]] = [];
                }

                $widget_list[$split[0]][] = $split[1];
            } else if (count($split) == 3) {
                if (!isset($widget_list[$split[0]])) {
                    $widget_list[$split[0]] = [];
                }

                if (!isset($widget_list[$split[0]][$split[1]])) {
                    $widget_list[$split[0]][$split[1]] = [];
                }

                $widget_list[$split[0]][$split[1]][] = $split[2];
            }
        }

        $data = [
            "role_name" => $role_name,
            "type" => 2,
            "widget" => json_encode($widget_list),
            "created_by" => Auth::user()->id,
            "created_at" => date("Y-m-d H:i:s"),
            "created_by_ip" => $request->ip()
        ];

        $insert = DB::table("acl")->insert($data);

        if ($insert) {
            return redirect(route('acl'))->with("success", "New ACL save successfully.");
        } else {
            return redirect(route('acl'))->with("error", "Fail to save ACL.Please try again.");
        }
    }

    public function acl_edit(Request $request)
    {
        $data_qry = DB::table("acl")->where("id", $request->id)->first();

        $data = collect($data_qry)->toArray();

        $data['widget'] = json_decode($data['widget'], true);

        // dd($data['widget']['inventory']);

        return view('admin.acl.edit', compact("data"));
    }

    public function acl_update(Request $request)
    {
        $role_id = $request->role_id;
        $role_name = $request->role_name;
        $widget = (array)$request->widget;

        $widget_list = [];

        foreach ($widget as $item) {

            $split = explode("_", $item);

            if (count($split) == 2) {
                if (!isset($widget_list[$split[0]])) {
                    $widget_list[$split[0]] = [];
                }

                $widget_list[$split[0]][] = $split[1];
            } else if (count($split) == 3) {
                if (!isset($widget_list[$split[0]])) {
                    $widget_list[$split[0]] = [];
                }

                if (!isset($widget_list[$split[0]][$split[1]])) {
                    $widget_list[$split[0]][$split[1]] = [];
                }

                $widget_list[$split[0]][$split[1]][] = $split[2];
            }
        }

        $data = [
            "role_name" => $role_name,
            "type" => 2,
            "widget" => json_encode($widget_list),
            "updated_by" => Auth::user()->id,
            "updated_at" => date("Y-m-d H:i:s"),
            "updated_by_ip" => $request->ip()
        ];

        $update = DB::table("acl")->where("id", $role_id)->update($data);

        if ($update) {
            return redirect(route('acl'))->with("success", "ACL update successfully.");
        } else {
            return redirect(route('acl'))->with("error", "Fail to update ACL.Please try again.");
        }
    }

    public function acl_delete(Request $request)
    {
        $id = $request->acl_id;

        $data = [
            "is_active" => 0,
            "updated_at" => Carbon::now(),
            "updated_by" => Auth::user()->id,
            "updated_by_ip" => $request->ip()
        ];

        $update = DB::table("acl")->where("id", $id)->update($data);

        if ($update) {
            return response()->json(['status' => 'success', 'message' => 'ACL role deleted.', 'data' => []]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Fail to delete ACL role.', 'data' => []]);
        }
    }
    
    public function employeSaleReport(Request $request)
    {
        $employes = Employe::where('shop_id',Auth::user()->record_id)->get();
        return view('admin.supplier.employe_sale_report',compact('employes'));
    }
    public function employeSaleReportData(Request $request){
		$report_name = "কর্মীর বিক্রয় রিপোর্ট";
        $employe_id = $request->employe_id;
        $supplier_id = Auth::user()->record_id;
        
		$query = "SELECT products.name,clients.name as client_name,SUM(inventories.debit) as quantity,inventories.selling_price, SUM(inventories.debit * inventories.selling_price) AS amount FROM inventories JOIN products ON products.id = inventories.product_id AND products.deleted_at IS NULL LEFT JOIN clients ON clients.id = inventories.client_id AND clients.deleted_at IS NULL WHERE inventories.`type` = 2 AND inventories.`status` = 2 AND inventories.deleted_at IS NULL AND inventories.supplier_id='$supplier_id' AND inventories.created_by = '$employe_id' ";
			
			if(isset($request->from_date)){
				$query.="AND DATE(inventories.created_at)>='$request->from_date' ";
            }
            
            if(isset($request->to_date)){
				$query.="AND DATE(inventories.created_at)<='$request->to_date' ";
			}

			$query .= "GROUP BY inventories.product_id ";
			$products = DB::select(DB::raw($query));
            
			$shop = Supplier::find($supplier_id);
			$data = [
					'shop' => $shop->shop_name,
					'shop_image' => $shop->shop_image,
					'report_name' => $report_name,
					'products' => $products,
                    'date_text' => '',
                    'from_date' => date('d-m-Y',strtotime($request->from_date)),
                    'to_date' => date('d-m-Y',strtotime($request->to_date)),
                    'employe_name' => Employe::find($employe_id)->name
				];

			$pdf = PDF::loadView('download.sale_report',$data);
			return $pdf->stream('Sale Report '.date('d M Y').'.pdf');
	}
    public function employePurchaseReport(Request $request)
    {
        $employes = Employe::where('shop_id',Auth::user()->record_id)->get();
        return view('admin.supplier.employe_purchase_report',compact('employes'));
    }

    public function employePurchaseReportData(Request $request)
    {
		$employe_id = $request->employe_id;
        $supplier_id = Auth::user()->record_id;

		$results = DB::table('products')
			->select('products.name', 'cat.name as category', 'sub_cat.name as sub_category','inv.credit', 'inv.debit', 'rate.rate as saling_price','inv.buying_price')

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
            ->where('inv.type', '=', 1)
            ->where('inv.created_by', '=', $employe_id)
			->where('products.supplier_id', '=', $supplier_id)
			->whereNull('inv.deleted_at')
			->whereNull('products.deleted_at')
			->whereNull('sup.deleted_at')
			->whereNull('rate.deleted_at')
            ->whereNull('users.deleted_at');
            
		if($request->from_date != 0){
			$results->whereDate('inv.created_at','>=', $request->from_date);
		}
		if($request->to_date != 0){
			$results->whereDate('inv.created_at','<=', $request->to_date);
		}

		$stockData = $results->orderBy('inv.id', 'desc')->get();

        
        $shop = Supplier::find($supplier_id);
		$data = [
			'shop' => $shop->shop_name,
			'shop_image' => $shop->shop_image,
			'products' => $stockData,
            'dateText' => '',
            'from_date' => date('d-m-Y',strtotime($request->from_date)),
            'to_date' => date('d-m-Y',strtotime($request->to_date)),
            'employe_name' => Employe::find($employe_id)->name
		];

		$pdf = PDF::loadView('download.employe_purchase', $data);
		return $pdf->stream('Stock Report' . date('dMY') . '.pdf');
    }

    // End

}
