<?php

/**
 * 
 */
class CheckSubDomain
{


	public static function getSubDomain(){

		if(env('APP_ENV') == 'production'){
			$host = explode('.',$_SERVER['HTTP_HOST']);
			$subdomain = count($host) == 4 ? $host[0] : '';
		}
		else{
			$subdomain = 'narsingdi';
		}


	//	dd($subdomain);

		$subdomain_info = DB::table('ecommerce_setup')->where('domain','=',$subdomain)->whereNull('deleted_at')->first();
		return !empty($subdomain_info) ? $subdomain_info : [];

	}


}
