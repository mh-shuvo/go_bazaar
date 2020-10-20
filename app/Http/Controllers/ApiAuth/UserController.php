<?php

namespace App\Http\Controllers\ApiAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Client;
use App\Supplier;
use App\AccessToken;
use App\DeliveryMember;
use DateTime;
use DB;
use Image;
use Validator;
use DateInterval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public $successStatus = 200; 
    private $user_type = [
    	'1' => 'Admin',
    	'2' => 'Supplier',
    	'3' => 'Client',
    	'4' => 'Delivery'
    ];
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            
            if($user->user_type != 1 && !empty($user)){
            	$method_name = 'get'.$this->user_type[$user->user_type];
            	$user = $this->$method_name($user);
            }

            return response()->json(
            	[
					"status" => "success",
					"message" => "User login successfull.",
					"data" => [
						'user_info' => $user
					],
					"token" => $this->generateToken($user->record_id)
				]
            	, $this->successStatus);

        } else {
           return response()->json(
            	[
					"status" => "error",
					"message" => "Credential do not match",
					"data" => []
				]
            	, 401);
        }
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    public function client_registration(Request $request) {

    	$validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(
            	[
            		'status' => 'error',
            		'message'    => 'Something Went Wrong',
            		'data'   => [
            				'erros' => $validator->errors()
            		]
            	]
            	, 428);
        }

		$name = $request->name;
		$mobile = $request->mobile;
		$password = Hash::make($request->password);

		$exists = User::where('username', $mobile)->first();

		if ($exists) {
			return response()->json(
            	[
            		'status' => 'error',
            		'message'    => 'You have already an account with this mobile number.',
            		'data'   => []
            	]
            	, 409);
		}

		$user_data = [
			'username' => $mobile,
			'password' => $password,
			'user_type' => 3,
			'created_by' => 1,
			'created_by_ip' => $request->ip(),
		];

		$customer_data = [
			'name' => $name,
			'mobile' => $mobile,
			'created_by' => 1,
			'created_by_ip' => $request->ip(),
		];

		DB::beginTransaction();

		try {

			$client_id = DB::table('clients')->insertGetId($customer_data);

			$user_data['record_id'] = $client_id;

			$user_id = DB::table('users')->insertGetId($user_data);

			DB::commit();

			$user = User::find($user_id);
			
			if($user->user_type != 1 && !empty($user)){
            	$method_name = 'get'.$this->user_type[$user->user_type];
            	$user = $this->$method_name($user);
            }

			return response()->json(
            	[
					"status" => "success",
					"message" => "User Registration successfull.",
					"data" => [
						'user_info' => $user
					],
					"token" => $this->generateToken($user->record_id)
				]
            	, $this->successStatus);

		} catch (\Exception $e) {
			DB::rollback();
			return response()->json(
            	[
            		'status' => 'error',
            		'message'    => 'User Registration Unsuccessfull',
            		'data'   => []
            	]
            	, 417);

		}

	}

    public function client_update(Request $request){
        
        $user_id = $request->user_id;
        $client_id = $request->header('CLIENT-ID');
        $name = $request->name;
        $mobile = $request->mobile;
        $email = $request->email;
        $address = $request->address;
        $upazila = $request->upazila;
        $union = $request->union;

    
        // existing check       
        $exists = User::where('username', $mobile)
                    ->where('id', '!=', $user_id)
                    ->where('user_type', 3)
                    ->first();

        if ($exists) {
            return response()->json(
                [
                    'status' => 'error',
                    'message'    => 'Mobile number already exists',
                    'data'   => []
                ]
                , 409);
        }


        $user_data = [
            'username' => $mobile,
            'user_type' => 3,
            'upazila_id' => $upazila,
            'union_id' => $union,
            'updated_by' => $client_id,
            'updated_by_ip' => $request->ip(),
        ];

        $customer_data = [
            'name' => $name,
            'mobile' => $mobile,
            'email' => $email,
            'address' => $address,
            'upazila_id' => $upazila,
            'union_id' => $union,
            'updated_by' => $client_id,
            'updated_by_ip' => $request->ip(),
        ];

        if ($request->hasFile("photo")) {
            //insert image
            $image = $request->file("photo");

            $img = $mobile . "." . $image->getClientOriginalExtension();

            $location = public_path("/upload/clients/" . $img);

            //upload image in folder
            $move = Image::make($image)->resize(300, 300)->save($location);


            if ($move) {
                $customer_data['photo'] = $img;
            }
        }


        DB::beginTransaction();

        try {

            $client_update = DB::table('clients')->where('id', $client_id)->update($customer_data);
            $user_update = DB::table('users')->where('id', $user_id)->update($user_data);

            DB::commit();
            
            $user = User::find($user_id);
            
            if($user->user_type != 1 && !empty($user)){
                $method_name = 'get'.$this->user_type[$user->user_type];
                $user = $this->$method_name($user);
            }
            return response()->json(
                [
                    "status" => "success",
                    "message" => "User Information Updated.",
                    "data" => [
                        'user_info' => $user
                    ]
                ]
                , $this->successStatus);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'status' => 'error',
                    'message'    => 'User Information Unsuccessfully Not Updated',
                    'data'   => []
                ]
                , 417);

        }

    }
    public function password_change(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'old_password' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

         if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'message'    => 'Something Went Wrong',
                    'data'   => [
                            'erros' => $validator->errors()
                    ]
                ]
                , 428);
        }

        $password = Hash::make($request->password);
        $user = User::find($request->user_id);
        $password_check = Hash::check($request->old_password, $user->password);
        if(!$password_check){
            return response()->json(
                [
                    'status' => 'error',
                    'message'    => 'Old password do not match',
                    'data'   => []
                ]
                , 401);
        }

        $user->password = $password;
        if($user->save()){

            return response()->json(
                [
                    'status' => 'status',
                    'message'    => 'Password Successfull Changed',
                    'data'   => []
                ]
                , $this->successStatus);
        }
        else{
             return response()->json(
                [
                    'status' => 'status',
                    'message'    => 'Something Went Wrong',
                    'data'   => []
                ]
                , 417);
        }
    }
	public function supplier_registration(Request $request){

		$validator = Validator::make($request->all(), [
            'supplier_name' => 'required',
            'mobile' => 'required',
            'upazila_id' => 'required',
            'union_id' => 'required',
            'shop_name' => 'required',
            'supplier_type' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
            	[
            		'status' => 'error',
            		'message'    => 'Something Went Wrong',
            		'data'   => [
            				'erros' => $validator->errors()
            		]
            	]
            	, 428);
        }

		$supplier = new Supplier();

			//supplier data create
			$supplier->upazila_id = $request->upazila_id;
			$supplier->union_id = $request->union_id;
			$supplier->supplier_types = $request->supplier_type;
			$supplier->shop_name = $request->shop_name;
			$supplier->name = $request->supplier_name;
			$supplier->mobile = $request->mobile;
			$supplier->email = $request->email;
			$supplier->address = $request->address;

			$supplier->created_by = 1;
			$supplier->created_by_ip = $request->ip();

			$supplier->save();

			return response()->json(
            	[
					"status" => "success",
					"message" => "User Registration successfull. Your account now on pending state.",
					"data" => []
				]
            	, $this->successStatus);
	}
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */

    private function getSupplier($user = []){
    	$data = Supplier::find($user->record_id);
    	if(!empty($data)){
    		$user->name = $data->name;
	    	$user->mobile = $data->mobile;
	    	$user->email = $data->email;
	    	$user->address = $data->address;
	    	$user->type = $data->Type->name;
	    	$user->shop_name = $data->Type->shop_name;
    	}
    	
    	return $user;
    }
     private function getClient($user = []){
    	$data = Client::find($user->record_id);
    	if(!empty($data)){
    		$user->name = $data->name;
	    	$user->mobile = $data->mobile;
	    	$user->email = $data->email;
            $user->address = $data->address;
	    	$user->photo = $data->photo;
    	}
    	
    	return $user;
    }
     private function getDelivery($user = []){
    	$data = DeliveryMember::find($user->record_id);
    	if(!empty($data)){
    		$user->name = $data->name;
	    	$user->mobile = $data->mobile;
	    	$user->email = $data->email;
	    	$user->address = $data->address;
    	}
    	return $user;
    	
    }

    private function Token($strength = 16){
    	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		  $input_length = strlen($permitted_chars);
		    $random_string = '';
		    for($i = 0; $i < $strength; $i++) {
		        $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
		        $random_string .= $random_character;
		    }
		 
		    return $random_string;
    }

    private function generateToken($record_id,$strength = 16){
		$token = $this->Token($strength);
		$expire_time = new DateTime();
		$expire_time->add(new DateInterval('PT3H'));
		$data = new AccessToken();
		$data->record_id = $record_id;
		$data->token = $token;
		$data->expire_time = $expire_time;

		if($data->save()){
			return $token;
		}
		else{
			return null;
		}
    	
    }
		   
}
