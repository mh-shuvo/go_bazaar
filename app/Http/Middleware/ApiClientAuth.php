<?php

namespace App\Http\Middleware;

use Closure;
use DB;
class ApiClientAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $client_id = $request->header('CLIENT-ID');
        $token = $request->header('ACCESS-TOKEN');
        
        if(empty($client_id) || $client_id == null){
             return response()->json(
                        [
                            "status" => "error", 
                            "message" => "You must pass the client id for this request", 
                            "data" => []
                        ]
                        ,428);
        }

        if(empty($token) || $token == null){
             return response()->json(
                        [
                            "status" => "error", 
                            "message" => "You must pass the client token for this request", 
                            "data" => []
                        ]
                        ,428);
        }

         $access_token = DB::table('access_tokens')->select('*')->where('record_id',$client_id)->orderBy('id', 'desc')->first();

                if(empty($access_token)){
                    return response()->json(
                        [
                            "status" => "error", 
                            "message" => "We can not find any access token for you.Please Login", 
                            "data" => []
                        ]
                        ,404);
                }

                if($access_token->token != $token){
                     return response()->json(
                        [
                            "status" => "error", 
                            "message" => "Token Mismatch", 
                            "data" => []
                        ]
                        ,401);
                }

                $currentTime = date("Y-m-d H:i:s");

                if($currentTime > $access_token->expire_time){
                    return response()->json(
                        [
                            "status" => "error", 
                            "message" => "Token Expired", 
                            "data" => []
                        ]
                        ,405);
                }

                $request->headers->set('TYPE', 'user');


        return $next($request);
    }
}
