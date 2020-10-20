<?php

namespace App\Http\Middleware;

use Closure;

class ClientCheck
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
        // return $next($request);

         if(\Auth::check()){

            if ($request->user()->user_type == 3) {

                return back();
            
                

            }else{

                return $next($request);
                
            }
       

        }else{

            return  redirect('/');

        }
    }
}
