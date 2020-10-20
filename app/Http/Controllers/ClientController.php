<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;
use App\Location;
use App\Supplier;
use App\Client;
use Carbon\Carbon;
use DataTables;
use DB;
use Session;
class ClientController extends Controller
{
    public function index()
    {
    	return view('admin.supplier.client')->with('data', Location::get_all_location());
    }
    public function data(Request $request){

    }
    public function store(Request$request)
    {

    	$name = $request->name;
		$mobile = $request->mobile;
		$user_pass = $request->password;
		$password = Hash::make($request->password);
		//cut mobile prefix
		$mobile_prefix = substr($mobile, 0, 3);

		$exists = User::where('username', $mobile)->first();


		if (!in_array($mobile_prefix, ['018', '019', '017', '015', '016', '013', '012'])) {

			return response()->json([
				'status' => "error",
				'message' => "ফোন নম্বর সঠিক নয়।"
			]);
		}

		if (!is_numeric($request->mobile)) {
			return response()->json([
				'status' => "error",
				'message' => "ফোন নম্বর অবশ্যই সংখ্যার হতে হবে"
			]);
		}
		if (strlen($request->mobile) < 11) {
			return response()->json([
				'status' => "error",
				'message' => "ফোন নম্বর অবশ্যই ১১ সংখ্যার হতে হবে"
			]);
		}

		if (strlen($request->mobile) > 11) {
			return response()->json([
				'status' => "error",
				'message' => "ফোন নম্বর অবশ্যই ১১ সংখ্যার হতে হবে"
			]);
		}
		if ($exists) {
			return response()->json([
				'status' => "error",
				'message' => "আপনার দেয়া মোবাইল নম্বর দিয়ে একটি একাউন্ট রয়েছে। আপনি চাইলে লগইন করতে পারেন।"
			]);
		}

		if ($request->password != $request->confirm_password) {
			return response()->json([
				'status' => "error",
				'message' => "পাসওয়ার্ড মিল নেই"
			]);
		}

		if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}

		$user_data = [
			'username' => $mobile,
			'password' => $password,
			'user_type' => 3,
			'created_by' => $supplier_id,
			'created_by_ip' => $request->ip(),
		];

		$customer_data = [
			'name' => $name,
			'mobile' => $mobile,
			'created_by' => $supplier_id,
			'created_by_ip' => $request->ip(),
		];

		DB::beginTransaction();

		try {

			$client_id = DB::table('clients')->insertGetId($customer_data);

			$user_data['record_id'] = $client_id;

			DB::table('users')
				->insert($user_data);

			DB::commit();

			return response()->json([
				'status' => "success",
				'message' => "রেজিস্ট্রেশন সম্পূর্ণ হয়েছে"
			]);

		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'status' => "error",
				'message' => "কোন কিছু ভুল হচ্ছে"
			]);
			
		}
    }
    public function edit($id){

    }
    public function delete($id){

    }
}
