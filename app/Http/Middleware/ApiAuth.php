<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Support\Facades\Hash;

class ApiAuth
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
        $app_key = 'A1b1C2d32564kjhkjadu';
        
        $request_app_key = $request->header('APP-KEY');
        $client_id = $request->header('CLIENT-ID');
        $token = $request->header('ACCESS-TOKEN');


        if($request_app_key == $app_key){ 

            $request->headers->set('TYPE', 'guest');
             return $next($request);
        }
        else{
            return response()->json(
                [
                    "status" => "error", 
                    "message" => "Un-Athorized", 
                    "data" => []
                ]
                ,401);
        }
    }
}
