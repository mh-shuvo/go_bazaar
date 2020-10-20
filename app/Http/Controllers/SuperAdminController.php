<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\User;
use App\Traits\Sms;
use App\Location;
use App\Farmer;
use App\Category;
use App\Supplier;
use App\Contact;
use App\Client;
use App\Order;
use App\Complain;
use App\DeliveryMember;
use App\SuperAdmin;
use App\CentralUser;
use Carbon\Carbon;
use DataTables;
use DB;
use Response;
use Session;


class SuperAdminController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	use Sms;
	
	public function __construct() {
		// $this->middleware('auth');
		// //client can't login main dash board
		// $this->middleware('client.check');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */

	//get all location
	public function get_location(Request $request){

		//get location
		$response = Location::get_all_location($request);

		if (!empty($response)) {
            
            echo json_encode(['status' => 'success', "message" => "data found", 'data' => $response]);

        }else{

            echo json_encode(['status' => 'error', "message" => "data not found", 'data' => []]);
        }
	}
	
	//location list
	public function location_list(Request $request) {

		$locations = Location::where(['type' => 2, 'deleted_at' => NULL])->get();

		if ($request->ajax()) {

			//get location list data	
			$response = Location::location_list($request);

			return Datatables::of($response)

				->addIndexColumn()

				->rawColumns(['action'])

				->make(true);

		}

		return view('admin.location_list')->with('data', $locations);
	}

	//location store
	public function location_store(Request $request){

		$data = new Location();

		//validation start
		$validator = Validator::make($request->all(), [

            'name' => ['required',Rule::unique('locations', 'name')->whereNull('deleted_at')],
        	],

	        //validation error custom message
	        [
	            "name.required" => "নাম প্রদান করুন",  
	            "name.unique" => "উক্ত নাম পূর্বে অন্তর্ভুক্ত আছে",  
	        ]
        );

		//if validation success
        if ($validator->passes()) {

			if ($request->district_id > 0) {
				
				$data->type = 3;
				$data->parent_id = $request->district_id;

				$text = 'উপজেলা';
			
			}else{

				$data->type = 2;
				$data->parent_id = NULL;
				$text = 'জেলা';
			}

			$data->en_name = $request->name;
			$data->created_at = Carbon::now();
			$data->created_by = Auth::user()->id;
			$data->created_by_ip =  request()->ip();

			$insert = $data->save();

			if ($insert) {
	            
	            $response = ["status" => "success", "message" => "$text সফলভাবে সম্পন্ন হয়েছে"]; 
	        }else{

	            $response = ["status" => "error", "message" => "$text সম্পন্ন হয়নি"]; 
	        }

	        return Response::json($response);
	    }


	    return Response::json(['errors' => $validator->errors()]);


	}

	//categroy update store
	public function location_update(Request $request){

		// $data = new Category();

		$data = Location::find($request->row_id);

		//validation start
		$validator = Validator::make($request->all(), [

            'name' => ['required',Rule::unique('locations', 'name')->ignore($request->row_id)->whereNull('deleted_at')],
        	],

	        //validation error custom message
	        [
	            "name.required" => "নাম প্রদান করুন",  
	            "name.unique" => "উক্ত নাম পূর্বে অন্তর্ভুক্ত আছে",  
	        ]
        );

		//if validation success
        if ($validator->passes()) {

			if ($request->district_id > 0) {
				
				$data->type = 3;
				$data->parent_id = $request->district_id;

				$text = 'উপজেলা';
			
			}else{
				$data->type = 2;
				$data->parent_id = NULL;
				$text = 'জেলা';
			}

			$data->en_name = $request->name;
			$data->updated_at = Carbon::now();
			$data->updated_by = Auth::user()->id;
			$data->updated_by_ip =  request()->ip();

			$update = $data->save();

			if ($update) {
	            
	            $response = ["status" => "success", "message" => "$text আপডেট সফলভাবে সম্পন্ন হয়েছে"]; 
	        }else{

	            $response = ["status" => "error", "message" => "$text আপডেট সম্পন্ন হয়নি"]; 
	        }

	        return Response::json($response);

	    }

	    return Response::json(['errors' => $validator->errors()]);


	}

	//location delete
	public function location_delete(Request $request){

		$delete = Location::where('id', $request->id)->delete();
		
		if ($delete) {
            
            echo json_encode( ["status" => "success", "message" => "সফলভাবে ডিলিট সম্পন্ন হয়েছে"]); 
        }else{

            echo json_encode( ["status" => "error", "message" => "ডিলিট সম্পন্ন হয়নি"]); 
        }
	}

	//get all categores for modal
	public function categores(){

		$data = Category::where(['type' => 1, 'deleted_at' => NULL])->get();

		echo json_encode($data);

	}

	//this is for category and subcategory list
	public function category_list(Request $request) {

		if ($request->ajax()) {

			//get category list data	
			$response = Category::category_list();

			return Datatables::of($response)

				->addIndexColumn()

				->rawColumns(['action'])

				->make(true);

		}

		return view('admin.category_subcategory_list');
	}

	//categroy store
	public function category_store(Request $request){

        if(isset($request->row_id)){
			$data = Category::find($request->row_id);

			if ($request->hasFile('icon')) {

				$old_img = public_path('upload/category/'.$data->icon);			
				if (file_exists($old_img)) {
					@unlink($old_img);
				}
				$fileName = time() . '.' . $request->icon->extension();
				$data->icon = $fileName;
				$request->icon->move(public_path('upload/category'), $fileName);

			}
			$validator = Validator::make($request->all(), [

	            'name' => ['required'],
	        	],

		        //validation error custom message
		        [
		            "name.required" => "নাম প্রদান করুন", 
		        ]
	        );
		}
		else{
					//validation start
			$validator = Validator::make($request->all(), [

	            'name' => ['required',Rule::unique('categories', 'name')->whereNull('deleted_at')],
	        	],

		        //validation error custom message
		        [
		            "name.required" => "নাম প্রদান করুন",  
		            "name.unique" => "উক্ত নাম পূর্বে অন্তর্ভুক্ত আছে",  
		        ]
	        );

			$data = new Category();

			if ($request->hasFile('icon')) {
				$fileName = time() . '.' . $request->icon->extension();
				$data->icon = $fileName;
				$request->icon->move(public_path('upload/category'), $fileName);

			}

		}


		//if validation success
        if ($validator->passes()) {

			if ($request->category > 0) {
				
				$data->type = 2;
				$data->parent_id = $request->category;

				$text = 'সাব-ক্যাটাগরি';
			
			}else{

				$data->type = 1;
				$data->parent_id = NULL;

				$text = 'ক্যাটাগরি';
			}

			$data->name = $request->name;
			$data->is_show = $request->is_show;
			$data->is_feature = $request->is_feature;
			$data->sorting = $request->sorting;
			$data->created_at = Carbon::now();
			$data->created_by = Auth::user()->id;
			$data->created_by_ip =  request()->ip();

			$insert = $data->save();

			if ($insert) {
	            
	            $response = ["status" => "success", "message" => "$text সফলভাবে সম্পন্ন হয়েছে"]; 
	        }else{

	            $response = ["status" => "error", "message" => "$text সম্পন্ন হয়নি"]; 
	        }

	        return Response::json($response);
	    }


	    return Response::json(['errors' => $validator->errors()]);


	}

	//categroy update store
	public function category_update(Request $request){

		// $data = new Category();

		$data = Category::find($request->row_id);

		//validation start
		$validator = Validator::make($request->all(), [

            'name' => ['required',Rule::unique('categories', 'name')->ignore($request->row_id)->whereNull('deleted_at')],
        	],

	        //validation error custom message
	        [
	            "name.required" => "নাম প্রদান করুন",  
	            "name.unique" => "উক্ত নাম পূর্বে অন্তর্ভুক্ত আছে",  
	        ]
        );

		//if validation success
        if ($validator->passes()) {

			if ($request->category > 0) {
				
				$data->type = 2;
				$data->parent_id = $request->category;

				$text = 'সাব-ক্যাটাগরি';
			
			}else{

				$data->type = 1;
				$data->parent_id = NULL;

				$text = 'ক্যাটাগরি';
			}

			$data->name = $request->name;
			$data->is_show = $request->is_show;
			$data->is_feature = $request->is_feature;
			$data->sorting = $request->sorting;
			$data->updated_at = Carbon::now();
			$data->updated_by = Auth::user()->id;
			$data->updated_by_ip =  request()->ip();

			$update = $data->save();

			if ($update) {
	            
	            $response = ["status" => "success", "message" => "$text আপডেট সফলভাবে সম্পন্ন হয়েছে"]; 
	        }else{

	            $response = ["status" => "error", "message" => "$text আপডেট সম্পন্ন হয়নি"]; 
	        }

	        return Response::json($response);

	    }

	    return Response::json(['errors' => $validator->errors()]);


	}

	//category delete
	public function category_delete(Request $request){

		$delete = Category::where('id', $request->id)->delete();
		
		if ($delete) {
            
            echo json_encode( ["status" => "success", "message" => "সফলভাবে ডিলিট সম্পন্ন হয়েছে"]); 
        }else{

            echo json_encode( ["status" => "error", "message" => "ডিলিট সম্পন্ন হয়নি"]); 
        }
	}

	//for supplier type
	public function supplier_type(Request $request) {

		if ($request->ajax()) {

			//get supplier type list data	
			$supplier = DB::table('supplier_types')->where('deleted_at', '=', NULL)->get();

			return Datatables::of($supplier)

				->addIndexColumn()

				->rawColumns(['action'])

				->make(true);

		}

		return view('admin.supplier.supplier_type_list');
	}

	//supplier type store
	public function supplier_type_store(Request $request){

		//validation start
		$validator = Validator::make($request->all(), [

            'name' => ['required',Rule::unique('supplier_types', 'name')->whereNull('deleted_at')],
        	],

	        //validation error custom message
	        [
	            "name.required" => "সাপ্লাইয়ারের ধরন দিতে হবে",  
	            "name.unique" => "সাপ্লাইয়ারের ধরন পূর্বে দেওয়া আছে",  
	        ]
        );

		//if validation success
        if ($validator->passes()) {

	        $data = [

				'name' => $request->name,
				'created_at' => Carbon::now(),
				'created_by' => Auth::user()->id,
				'created_by_ip' =>  request()->ip(),
			];
		
			$insert = DB::table('supplier_types')->insert($data);

			if ($insert) {
	            
	            $response =  ["status" => "success", "message" => "সাপ্লাইয়ারের ধরন যোগ হয়েছে"]; 
	        }else{

	            $response = ["status" => "error", "message" => "সাপ্লাইয়ারের ধরন যোগ হয়নি"]; 
	        }


            return Response::json($response);

        }
        
        return Response::json(['errors' => $validator->errors()]);

	}

	//supplier type update store
	public function supplier_type_update(Request $request){

		// dd($request->row_id);
		// exit;

		//validation start
		$validator = Validator::make($request->all(), [

            'name' => ['required',Rule::unique('supplier_types', 'name')->ignore($request->row_id)->whereNull('deleted_at')],
        	],

	        //validation error custom message
	        [
	            "name.required" => "সাপ্লাইয়ারের ধরন দিতে হবে",  
	            "name.unique" => "সাপ্লাইয়ারের ধরন পূর্বে দেওয়া আছে",  
	        ]
        );

		//if validation success
        if ($validator->passes()) {

	        $update_data = [

				'name' => $request->name,
				'updated_at' => Carbon::now(),
				'updated_by' => Auth::user()->id,
				'updated_by_ip' =>  request()->ip(),
			];
		
			$update = DB::table('supplier_types')->where('id', $request->row_id)->update($update_data);

			if ($update) {
	            
	            $response =  ["status" => "success", "message" => "সাপ্লাইয়ারের ধরন আপডেট হয়েছে"]; 
	        }else{

	            $response = ["status" => "error", "message" => "সাপ্লাইয়ারের ধরন আপডেট হয়নি"]; 
	        }


            return Response::json($response);

        }
        
        return Response::json(['errors' => $validator->errors()]);

	}

	//supplier type delete
	public function supplier_type_delete(Request $request){

		$delete = DB::table('supplier_types')->where('id', $request->id)->update(['deleted_at' => Carbon::now()]);
		
		if ($delete) {
            
            echo json_encode( ["status" => "success", "message" => "সফলভাবে ডিলিট হয়েছে"]); 
        }else{

            echo json_encode( ["status" => "error", "message" => "ডিলিট হয়নি"]); 
        }
	}

	//supplier list
	public function suppliers(Request $request) {

		if ($request->ajax()) {
			//get supplier list data	
			$response = Supplier::supplier_list($request);

			return Datatables::of($response)

				->addIndexColumn()

				->rawColumns(['action'])

				->make(true);

		}
		return view('admin.supplier.supplier_list')->with('data', Location::get_all_location());
	}

	//supplier add
	public function supplier_add(){

		//get supplier type list data	
		$supplier_type = DB::table('supplier_types')->where('deleted_at', '=', NULL)->get();
		$permissions = DB::table('acl')->select("*")->where('type','=',1)->where('is_active','=',1)->get();

		return view('admin.supplier.supplier_add')

			->with([
				'data' => Location::get_all_location(),	
				'supplier_type' => $supplier_type,
				'permissions' => $permissions
			]);
	}

	//suplier store
	public function supplier_store(Request $request){


		//validation start
        $validation =  $this->validate($request, [

		        'district_id' => ['required'],
	            'upazila_id' => ['required'],
	            'shop_name' => ['required'],
	            'supplier_type' => ['required'],
	            'name' => 'required',
	            'mobile' => 'required',
	            'address' => 'required',
	            'username' => ['required',Rule::unique('users', 'username')->whereNull('deleted_at')],
	            'password' => 'required|min:6',
	            'nid' => ['required'], 
	            'trade_id' => ['required'],
	            'permission' => ['permission'],
	            

		    ], 
		    [//validation custom message
		      	"district_id.required" => "জেলা সিলেক্ট করুন",  
	            "upazila_id.required" => "উপজেলা সিলেক্ট করুন",  
	            "supplier_type.required" => "সাপ্লাইয়ার সিলেক্ট করুন",  
	            "shop_name.required" => "দোকানের নাম দিন",  
	            "name.required" => "মালিকের নাম দিন",  
	            "mobile.required" => "মোবাইল নাম্বার দিন",  
	            "address.required" => "ঠিকানা দিন",  
	            "username.required" => "ইউজারনেম দিন",  
	            "username.unique" => "ইউজারনেম পূর্বে ব্যবহার হয়েছে",  
	            "password.required" => "পাসওয়ার্ড দিন",
	            "password.min" => "পাসওয়ার্ড কমপক্ষে ৬ সংখ্যা দিন",
	            "nid.required" => "জাতীয় প্রিচয়পত্র নম্বর দিন",  
				"trade_id.required" => "ট্রেড লাইসেন্স এর নম্বর দিন", 
				"permission.required" => "পারমিশন নির্বাচন করুন" 
		      
		    ]);

		//if validation success
        if ($validation) {

        	//supplier  object
			$supplier = new Supplier();

			//supplier data create
			$supplier->district_id = $request->district_id;
			$supplier->upazila_id = $request->upazila_id;
			$supplier->supplier_types = $request->supplier_type;
			$supplier->shop_name = $request->shop_name;
			$supplier->name = $request->name;
			$supplier->mobile = $request->mobile;
			$supplier->email = $request->email;
			$supplier->address = $request->address;
			$supplier->status = 1;
			$supplier->nid = $request->nid;
			$supplier->trade_id = $request->trade_id;

			$supplier->created_at = Carbon::now();
			$supplier->created_by = Auth::user()->id;
			$supplier->created_by_ip = request()->ip();

			if ($request->hasFile('shop_image')) {
				$fileName = time() . '.' . $request->shop_image->extension();
				$supplier->shop_image = $fileName;
				$request->shop_image->move(public_path('upload/supplier'), $fileName);

			}

			if ($request->hasFile('trade_photo')) {
				$tradePhoto = time() . '.' . $request->trade_photo->extension();
				$supplier->trade_photo = $tradePhoto;
				$request->trade_photo->move(public_path('upload/supplier'), $tradePhoto);

			}

			$supplier->save();

			//supplier last insert id
			$supplier_id = DB::getPdo()->lastInsertId();


			//user object
			$user = new User();

			//users data create
			$user->upazila_id = $request->upazila_id;
			$user->district_id = $request->district_id;
			$user->record_id = $supplier_id;
			$user->username = $request->username;
			$user->password = bcrypt($request->password);
			$user->user_type = 2;
			$user->role_id = $request->permission;

			$user->created_at = Carbon::now();
			$user->created_by = Auth::user()->id;
			$user->created_by_ip = request()->ip();

			$user->save();

			//sweet alert toast
			// toast('নতুন সাপ্লাইয়ার যুক্ত হয়েছে','success');

			return redirect('admin/suppliers')->with('success','নতুন সাপ্লাইয়ার যুক্ত হয়েছে');

        } 

        return redirect()->back()->withInput();
	}

	//supplier edit
	public function supplier_edit($id = NULL){

		//get supplier data
		$supplier_data = Supplier::supplier_edit_data($id);
		
		//get supplier type list data	
		$supplier_type = DB::table('supplier_types')->where('deleted_at', '=', NULL)->get();
		$permissions = DB::table('acl')->select("*")->where('type','=',1)->where('is_active','=',1)->get();

		return view('admin.supplier.supplier_edit')->with([

				'data' => Location::get_all_location(),
			
				'union_data' => Location::get_all_location(['parent_id' => $supplier_data->district_id, 'type' => 3]),
			
				'suppliers_type' => $supplier_type,
				
				'supplier' => $supplier_data,
				'permissions' => $permissions
			]);

	}

	//suppler update save
	public function supplier_update(Request $request){



		//validation start
        $validation =  $this->validate($request, [

		        'upazila_id' => ['required'],
	            'district_id' => ['required'],
	            'shop_name' => ['required'],
	            'supplier_type' => ['required'],
	            'name' => 'required',
	            'mobile' => 'required',
	            'address' => 'required',
	            // 'username' => ['required',Rule::unique('users', 'username')->ignore($request->user_id)->whereNull('deleted_at')],
	            'nid' => ['required'],
	            'trade_id' => ['required'],
		    ], 
		    [//validation custom message
		      	"upazila_id.required" => "উপজেলা সিলেক্ট করুন",  
	            "district_id.required" => "জেলা সিলেক্ট করুন",  
	            "supplier_type.required" => "সাপ্লাইয়ার সিলেক্ট করুন",  
	            "shop_name.required" => "দোকানের নাম দিন",  
	            "name.required" => "মালিকের নাম দিন", 
	            "mobile.required" => "মোবাইল নাম্বার দিন",  
	            "address.required" => "ঠিকানা দিন",  
	            "username.required" => "ইউজারনেম দিন",  
	            "username.unique" => "ইউজারনেম পূর্বে ব্যবহার হয়েছে", 
	            "nid.required" => "জাতীয় প্রিচয়পত্র নম্বর দিন", 
	             "trade_id.required" => "ট্রেড লাইসেন্স এর নম্বর দিন",  
	            
		      
		    ]);



		//if validation success
        if ($validation) {

        	//supplier  object
			$supplier = Supplier::find($request->row_id);

			//supplier data create
			$supplier->upazila_id = $request->upazila_id;
			$supplier->district_id = $request->district_id;
			$supplier->supplier_types = $request->supplier_type;
			$supplier->shop_name = $request->shop_name;
			$supplier->name = $request->name;
			$supplier->mobile = $request->mobile;
			$supplier->email = $request->email;
			$supplier->address = $request->address;
			$supplier->nid = $request->nid;
			$supplier->trade_id = $request->trade_id;

			$supplier->updated_at = Carbon::now();
			$supplier->updated_by = Auth::user()->id;
			$supplier->updated_by_ip = request()->ip();

			if ($request->hasFile('shop_image')) {

				$old_img = public_path('upload/supplier/'.$supplier->shop_image);
				echo $old_img;				
				if (file_exists($old_img)) {
					@unlink($old_img);
				}
				$fileName = time() . '.' . $request->shop_image->extension();
				$supplier->shop_image = $fileName;
				$request->shop_image->move(public_path('upload/supplier'), $fileName);

			}

			if ($request->hasFile('trade_photo')) {

				$old_img = public_path('upload/supplier/'.$supplier->trade_photo);
				echo $old_img;				
				if (file_exists($old_img)) {
					@unlink($old_img);
				}
				$tradePhoto = time() . '.' . $request->trade_photo->extension();
				$supplier->trade_photo = $tradePhoto;
				$request->trade_photo->move(public_path('upload/supplier'), $tradePhoto);

			}

			$supplier->save();

			//user object
			$user = User::find($request->user_id);

			//users data create
			$user->upazila_id = $request->upazila_id;
			$user->district_id = $request->district_id;
			$user->username = $request->username;
			$user->role_id = $request->permission;

			//if insert new password
			if($request->password != null){
				$user->password = bcrypt($request->password);
			}

			$user->updated_at = Carbon::now();
			$user->updated_by = Auth::user()->id;
			$user->updated_by_ip = request()->ip();

			$user->save();

			//sweet alert toast
			// toast('সাপ্লাইয়ারের তথ্য আপডেট হয়েছে','success');

			return redirect('admin/suppliers')->with('success','সাপ্লাইয়ারের তথ্য আপডেট হয়েছে');

        } 


        return redirect()->back()->withInput();
	}


	//supplier delete
	public function supplier_delete(Request $request){

		$suppler_delete = Supplier::where('id', $request->id)->delete();

		$user_delete = User::where('id', $request->user_id)->delete();
		
		if ($user_delete) {
            
            echo json_encode( ["status" => "success", "message" => "সফলভাবে ডিলিট হয়েছে"]); 
        }else{

            echo json_encode( ["status" => "error", "message" => "ডিলিট হয়নি"]); 
        }

	}

	public function statusChange(Request $request){

		$supplier =  Supplier::find($request->id);
		
		if($request->status == 1){
			$supplier->status = $request->status;
			$supplier->save();

			$user = new User();
			//users data create
			$user->upazila_id = $supplier->upazila_id;
			$user->district_id = $supplier->district_id;
			$user->record_id = $supplier->id;
			$user->username = $supplier->mobile;
			$user->password = bcrypt($supplier->mobile);
			$user->user_type = 2;
			$user->created_by = Auth::user()->id;
			$user->created_by_ip = request()->ip();

			$user->save();

		$mobile = $supplier->mobile;
		$msg = "Gobazaar.com.bd তে আপনার একাউন্ট টি অনুমোদিত হয়েছে। আপনার Username:".$supplier->mobile.' এবং Password: '.$supplier->mobile.'। ধন্যবাদ Gobazaar.com.bd এর সদস্য হওয়ার জন্য';
		$response = Sms::sendSms($mobile,$msg);

			$msg = "অনুমোদিত";

		}
		elseif($request->status == 2){
			$user = User::where('record_id',$request->id)->first();
			if(!empty($user)){
				$user->delete();
			}

			$supplier->status = $request->status;
			$supplier->save();
			$msg = "নিষ্ক্রিয়";
		}

		return response()->json([
			"status" => "success",
			"msg"   =>  "সাপ্লাইয়ার সফলভাবে ".$msg.' হয়েছে',
			"data" => []
		]);
	}

	//customers list
	public function clients(Request $request){

		if ($request->ajax()) {

			//get supplier list data	
			$response = Client::client_list($request);

			return Datatables::of($response)

				->addIndexColumn()

				->rawColumns(['action'])

				->make(true);

		}

		return view('admin.client.client_list')->with('data', Location::get_all_location());

	}

	//client delete
	public function client_delete(Request $request){

		$client_delete = Client::where('id', $request->id)->delete();

		$user_delete = User::where('id', $request->user_id)->delete();
		
		if ($user_delete) {
            
            echo json_encode( ["status" => "success", "message" => "সফলভাবে ডিলিট হয়েছে"]); 
        }else{

            echo json_encode( ["status" => "error", "message" => "ডিলিট হয়নি"]); 
        }
	}

	

	//orders
	public function orders(Request $request){

		if ($request->ajax()) {

			//get orderlist list data	

			$query = "SELECT inventories.order_id,inventories.status, clients.name, clients.mobile,clients.address as shipping_address,clients.email,loc.en_name as district_name, suppliers.shop_name, SUM(inventories.debit * inventories.selling_price) AS order_amount,orders.origin FROM inventories JOIN clients ON clients.id = inventories.client_id JOIN orders on orders.order_id=inventories.order_id JOIN suppliers on suppliers.id = inventories.supplier_id LEFT JOIN bd_locations as loc on loc.id = suppliers.district_id WHERE inventories.type = 2 AND inventories.deleted_at IS NULL ";


			if ($request->status != 0) {
				$query .= "AND inventories.status = $request->status ";
			}
			if ($request->district_id != 0) {
				$query .= "AND suppliers.district_id = $request->district_id ";
			}
			if ($request->shop_id != 0) {
				$query .= "AND inventories.supplier_id = $request->shop_id ";
			}
			$query .= "GROUP BY inventories.order_id ORDER BY orders.id DESC";

			$orders = DB::select(DB::raw($query));

			return Datatables::of($orders)

				->addIndexColumn()

				->addColumn('action', function ($row) {

                    $btn = '<a href="'.route("order_details",$row->order_id).'" class="btn btn-info btn-sm" target="_blank">বিস্তারিত</a>';

					return $btn;

				})

				->rawColumns(['action'])

				->make(true);

		}


		$districts = Location::get_all_location();
		return view('admin.order.order_list',compact('districts'));

	}

	//order details
	public function order_details($order_id){

		$response = Order::order_details($order_id);

		if(count($response['products']) > 0){

			return view('admin.order.order_details')->with($response);

		}else{

			echo "<h1 style='text-align:center;color:red;'>দুঃখিত ! ডাটা পাওয়া যায়নি</h1>";
		}
		
	}

	//password changes
	public function password_changes(Request $request){
		

		$data = [
			'password' => Hash::make($request->password),
			'updated_at' => Carbon::now(),
			'updated_by' => Auth::user()->id,
			'updated_by_ip' => request()->ip(),
		];

		$user = User::find($request->user_id);

	        if(!Hash::check($request->old_password , $user->password)){
	            return response()->json(
	                [
	                    'status' => 'error',
	                    'statusText' => 'ভূল হয়েছে',
	                    'message'    => 'পুরাতন পাসওয়ার্ড মিল নাই',
	                    'data'   => Hash::make($request->old_password)
	                ]);
	        }

	        if($request->password != $request->confirm_password){
	            return response()->json(
	                [
	                    'status' => 'error',
	                    'statusText' => 'ভূল হয়েছে',
	                    'message'    => 'কনফার্ম পাসওয়ার্ড মিল নাই',
	                    'data'   => []
	                ]);
	        }


		$user_update = DB::table('users')->where('id', $request->user_id)->update($data);

		if ($user_update) {
	
			return response()->json(
	                [
	                    'status' => 'success',
	                    'statusText' => 'সফল',
	                    'message'    => 'পাসওয়ার্ড আপডেট হয়েছে',
	                    'data'   => []
	                ]);

		}
	}



	//supplier profile
	public function supplier_profile(){

		$supplier_data = Supplier::find(Auth::user()->record_id);

		//get supplier type list data	
		$supplier_type = DB::table('supplier_types')->where('deleted_at', '=', NULL)->get();

		return view('admin.supplier.supplier_profile')->with([

				'data' => Location::get_all_location(),
			
				'upazila_data' => Location::get_all_location(['parent_id' => $supplier_data->district_id, 'type' => 3]),
			
				'suppliers_type' => $supplier_type,
				
				'supplier' => $supplier_data,
			]);
	}


	//suppler profile update save
	public function supplier_profile_update(Request $request){

		//validation start
        $validation =  $this->validate($request, [

		        'upazila_id' => ['required'],
	            'district_id' => ['required'],
	            'shop_name' => ['required'],
	            'supplier_type' => ['required'],
	            'name' => 'required',
	            'mobile' => 'required',
	            'address' => 'required',
	            'username' => ['required'],
	            'nid' => ['required'], 
	            'trade_id' => ['required'],
	           
	            

		    ], 
		    [//validation custom message
		      	"upazila_id.required" => "উপজেলা সিলেক্ট করুন",  
	            "district_id.required" => "জেলা সিলেক্ট করুন",  
	            "supplier_type.required" => "সাপ্লাইয়ার সিলেক্ট করুন",  
	            "shop_name.required" => "দোকানের নাম দিন",  
	            "name.required" => "মালিকের নাম দিন", 
	            "mobile.required" => "মোবাইল নাম্বার দিন",  
	            "address.required" => "ঠিকানা দিন",  
	            "username.required" => "ইউজারনেম দিন",  
	            "username.unique" => "ইউজারনেম পূর্বে ব্যবহার হয়েছে",  
	            "nid.required" => "জাতীয় প্রিচয়পত্র নম্বর দিন",  
	            "trade_id.required" => "ট্রেড লাইসেন্স এর নম্বর দিন", 
		    ]);




		//if validation success
        if ($validation) {

        	//supplier  object
			$supplier = Supplier::find($request->row_id);

			//supplier data create
			$supplier->upazila_id = $request->upazila_id;
			$supplier->district_id = $request->district_id;
			$supplier->supplier_types = $request->supplier_type;
			$supplier->shop_name = $request->shop_name;
			$supplier->name = $request->name;
			$supplier->mobile = $request->mobile;
			$supplier->email = $request->email;
			$supplier->address = $request->address;
			$supplier->nid = $request->nid;
			$supplier->trade_id = $request->trade_id;

			$supplier->updated_at = Carbon::now();
			$supplier->updated_by = Auth::user()->id;
			$supplier->updated_by_ip = request()->ip();

			if ($request->hasFile('trade_photo')) {

				$old_img = public_path('upload/supplier/'.$supplier->trade_photo);
				echo $old_img;				
				if (file_exists($old_img)) {
					@unlink($old_img);
				}
				$tradePhoto = time() . '.' . $request->trade_photo->extension();
				$supplier->trade_photo = $tradePhoto;
				$request->trade_photo->move(public_path('upload/supplier'), $tradePhoto);

			}


			if ($request->hasFile('shop_image')) {

				$old_img = public_path('upload/supplier/'.$supplier->shop_image);
				echo $old_img;				
				if (file_exists($old_img)) {
					@unlink($old_img);
				}
				$ShopImageFileName = time() . '.' . $request->shop_image->extension();
				$supplier->shop_image = $ShopImageFileName;
				$request->shop_image->move(public_path('upload/supplier'), $ShopImageFileName);

			}

			$supplier->save();

			//user object
			$user = User::find($request->user_id);

			//users data create
			$user->upazila_id = $request->upazila_id;
			$user->district_id = $request->district_id;
			$user->username = $request->username;

			//if insert new password
			if($request->password != null){
				$user->password = bcrypt($request->password);
			}

			$user->updated_at = Carbon::now();
			$user->updated_by = Auth::user()->id;
			$user->updated_by_ip = request()->ip();

			$user->save();

			//sweet alert toast
			// toast('সাপ্লাইয়ারের তথ্য আপডেট হয়েছে','success');

			return redirect('/supplier_profile')->with('success','আপনার তথ্য আপডেট হয়েছে');

        } 


        return redirect()->back()->withInput();
	}

	public function contact()
	{
		return view('admin.contact');
	}

	public function contactData(Request $request)
	{
		
		if ($request->ajax()) {
			$query = "SELECT * FROM contacts WHERE deleted_at IS NULL ";

			if ($request->from_date != 0) {
				$from_date = $request->from_date . ' 00:00:00';

				$query .= "AND DATE(created_at) >= '$from_date' ";
			}
			if ($request->to_date != 0) {
				$to_date = $request->to_date . ' 23:59:59';
				$query .= "AND DATE(created_at) <= '$to_date'";
			}
			
			$data = DB::select(DB::raw($query));

			return Datatables::of($data)
				->addIndexColumn()

				->make(true);
		}
	}


	public function supplier_fp_otp_send(Request $request){

		$isExists = Supplier::where('mobile',$request->mobile)->first();

		if(empty($isExists)){
			return [
				'status' => "error",
				'message' => "এই নাম্বার দিয়ে কোন একাউন্ট খুজে পাওয়া যায়নি।"
			];
		}

		$otp_code = mt_rand(100000, 999999);
		$mobile = $request->mobile;
		$msg = "Your verification code is " . $otp_code . ' valid for 1 minute. Gobazaar.com.bd. Thank you.';
		$response = Sms::sendSms($mobile,$msg);
		// $response = 1;
		if ($response == 1) {
			$status = 'success';
			session(['otp_code' => $otp_code]);
		} else {
			$status = 'fail';
		}
			return [
				'status' => $status,
				'message' => "এই নাম্বার দিয়ে একাউন্ট খুজে পাওয়া গেছে।",
				"record_id" => $isExists->id,
				// "otp_code" => $otp_code
			];
	}
	public function supplier_fp_otp_verify(Request $request){
		$otp = session('otp_code');
		if ($request->otp == $otp) {
			$status = "success";
			$msg = "ভেরিফাইড";
			session()->forget(['otp_code']);
		} else {
			$status = 'error';
			$msg = "OTP কোড মিলে নাই";
		}
		return [
			'status' => $status,
			'message' => $msg,
		];
	}
	public function supplier_password_change(Request $request){
		$user = User::where('record_id',$request->record_id)->first();
		$password = Hash::make($request->password);
		$user->password = $password;
		 if($user->save()){

            return [
                    'status' => 'success',
                    'message'    => 'পাসওয়ার্ড পরিবর্তন সফল হয়েছে'
                ];
        }
        else{
        	return [
                    'status' => 'error',
                    'message'    => 'পাসওয়ার্ড পরিবর্তন সফল হয়নি'
                ];	
        }
	}

	public function complain(Request $request)
	{
		if($request->ajax()){
			
			$query = Complain::where('reply_type',0)->where('reply_from',0);
			
			if($request->supplier_id!=0){
				$query->where('supplier_id',$request->supplier_id);
			}

			if($request->from_date != 0 && $request->to_date){
				$start = date("Y-m-d",strtotime($request->from_date));
		        $end = date("Y-m-d",strtotime($request->to_date."+1 day"));
		        $query->whereBetween('created_at',[$start,$end]);
			}

			$data = $query->orderBy('id','desc')->get();


			return Datatables::of($data)

				->addIndexColumn()


				->addColumn('created_at', function ($row) {
					return date('d-m-Y', strtotime($row->created_at));
				})
				->addColumn('customer', function ($row) {
					return $row->Client->name;
				})
				->addColumn('supplier', function ($row) {
					return $row->Supplier->name;
				})

				->addColumn('action', function ($row) {
					return '';
				})

				->rawColumns(['action', 'customer', 'created_at','supplier'])

				->make(true);
		}
		else{
			return view('admin.complain');
		}
	}

	public function singleComplain($id)
	{
		$complain = Complain::find($id);
		$complain_reply = Complain::where('parent_id',$id)->get();
		$supplier = Supplier::find($complain->supplier_id);
		return view('admin.single_complain',compact('complain','complain_reply','supplier'));
	}

	public function aclIndex()
	{
		return view('admin.admin_acl.list');
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
			->where("type", 1)
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
        return view('admin.admin_acl.create');
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
            "type" => 1,
            "role_name" => $role_name,
            "widget" => json_encode($widget_list),
            "created_by" => Auth::user()->id,
            "created_at" => date("Y-m-d H:i:s"),
            "created_by_ip" => $request->ip()
        ];

        $insert = DB::table("acl")->insert($data);

        if ($insert) {
            return redirect(route('admin.acl'))->with("success", "New ACL save successfully.");
        } else {
            return redirect(route('admin.acl'))->with("error", "Fail to save ACL.Please try again.");
        }
    }

    public function acl_edit(Request $request)
    {
        $data_qry = DB::table("acl")->where("id", $request->id)->first();

        $data = collect($data_qry)->toArray();

        $data['widget'] = json_decode($data['widget'], true);

        return view('admin.admin_acl.edit', compact("data"));
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
			"type" => 1,
            "role_name" => $role_name,
            "widget" => json_encode($widget_list),
            "updated_by" => Auth::user()->id,
            "updated_at" => date("Y-m-d H:i:s"),
            "updated_by_ip" => $request->ip()
        ];

        $update = DB::table("acl")->where("id", $role_id)->update($data);

        if ($update) {
            return redirect(route('admin.acl'))->with("success", "ACL update successfully.");
        } else {
            return redirect(route('admin.acl'))->with("error", "Fail to update ACL.Please try again.");
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
	
	public function userList(Request $request)
	{
		if($request->ajax()){

			$data = DB::table('users')
					->select('users.id as user_id','users.username','users.district_id','users.upazila_id','central_users.name','central_users.email','central_users.phone','central_users.post','DIS.en_name as district_name','UPZ.en_name as upazila_name')
					->join('central_users','users.record_id','=','central_users.id')
					->leftjoin('bd_locations as DIS','users.district_id','=','DIS.id')
					->leftjoin('bd_locations as UPZ','users.upazila_id','=','UPZ.id')
					->where('user_type','6')
					->whereNull('users.deleted_at')
					->whereNull('central_users.deleted_at')->get();
			return DataTables::of($data)
					->addIndexColumn()
					->make(true);
 		}
		else{
			$district_list = Location::get_all_location();
			return view('admin.central_user_list', compact('district_list'));
		}
	}

	public function userStore(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'district_id' => 'required',
			'name' 		  => 'required',
			'email' 	  => 'required',
			'phone' 	  => 'required',
			'post' 	      => 'required'
		]);

		$validator->sometimes('password', 'required|min:8', function ($input) {
            return empty($input->id);
		});
		$validator->sometimes('username', ['required',Rule::unique('users', 'username')->whereNull('deleted_at')], function ($input) {
            return empty($input->id);
        });

		if($validator->fails()){
			return response()->json([
				'status' => 'errors',
				'msg'    => 'ভ্যালিডেশন সমস্যা' ,
				'data'   => $validator->errors()
			]);
		}
		else{

			if(isset($request->id)){
				$userInstance = User::find($request->id);
				$recordInstance = CentralUser::find($userInstance->record_id);
				
				$recordInstance->updated_by = Auth::user()->id;
				$recordInstance->updated_by_ip = $request->ip();

				$userInstance->updated_by = Auth::user()->id;
				$userInstance->updated_by_ip = $request->ip();
			}
			else{
				$userInstance = new User();
				$recordInstance = new CentralUser();

				$recordInstance->created_by = Auth::user()->id;
				$recordInstance->created_by_ip = $request->ip();

				$userInstance->created_by = Auth::user()->id;
				$userInstance->created_by_ip = $request->ip();
			}

			$recordInstance->name = $request->name;
			$recordInstance->email = $request->email;
			$recordInstance->phone = $request->phone;
			$recordInstance->post = $request->post;
			$recordSave = $recordInstance->save();
			if($recordSave){
				$userInstance->district_id = $request->district_id;
				$userInstance->upazila_id = $request->upazila_id;
				$userInstance->username = $request->username;
				$userInstance->user_type = 6;
				if(!empty($request->password)){
					$userInstance->password = Hash::make($request->password);	
				}
				$userInstance->record_id = $recordInstance->id;
				$userSave = $userInstance->save();

				if($userSave){
					return response()->json([
						'status' => 'success',
						'msg'    => 'সফলভাবে ইউজার এর তথ্য জমা হয়েছে',
						'data'  => []
					]);
				}
				else{

					return response()->json([
						'status' => 'error',
						'msg'    => 'কোন সমস্যা হয়েছে' ,
						'data'   => []
					]);
				}

			}
			else{
				return response()->json([
					'status' => 'error',
					'msg'    => 'কোন সমস্যা হয়েছে' ,
					'data'   => []
				]);
			}
		}
	}

	public function userEdit(Request $request)
	{
		$data = DB::table('users')
					->select('users.id as user_id','users.username','users.district_id','users.upazila_id','central_users.name','central_users.email','central_users.phone','central_users.post','DIS.en_name as district_name','UPZ.en_name as upazila_name')
					->join('central_users','users.record_id','=','central_users.id')
					->leftjoin('bd_locations as DIS','users.district_id','=','DIS.id')
					->leftjoin('bd_locations as UPZ','users.upazila_id','=','UPZ.id')
					->where('user_type','6')
					->where('users.id','=',$request->id)
					->whereNull('users.deleted_at')
					->whereNull('central_users.deleted_at')->first();

		return response()->json([
			'status' => !empty($data) ? 'success' : 'error',
			'msg' => !empty($data) ? 'তথ্য পাওয়া গিয়েছে' : 'তথ্য পাওয়া যায়নি',
			'data' => !empty($data) ? $data : [],

		]);
	}

	public function userDelete(Request $request)
	{
		$user = User::find($request->id);
		$record_id = $user->record_id;
		$isDelete = $user->delete();
		
		if($isDelete){
			$record = CentralUser::find($record_id);
			$isRecordDelete = $record->delete();
			if($isRecordDelete){
				return response()->json([
					'status' => 'success',
					'msg'    => 'সফল্ভাবে ডিলিট হয়েছে' ,
					'data'   => []
				]);
			}
			else{
				DB::table('users')->where('id','=',$request->id)->update(['deleted_at' => null]);
				return response()->json([
					'status' => 'error',
					'msg'    => 'কোন সমস্যা হয়েছে' ,
					'data'   => []
				]);
			}
		}
		else{
			return response()->json([
				'status' => 'error',
				'msg'    => 'কোন সমস্যা হয়েছে' ,
				'data'   => []
			]);
		}
		
	}


}
