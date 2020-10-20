<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
class CartController extends Controller
{
  //   public function add_cart_product(Request $request)
  //   {
  //   	$product_id = $request->product_id;

  //   	$product_info   = DB::table('products AS PRODUCT')
		// 					->join('inventories AS INVT', function($join){
		// 						$join->on('INVT.product_id', '=', 'PRODUCT.id');
		// 					})
		// 					->join('product_rate AS PRORAT', function($join){
		// 						$join->on('PRORAT.product_id', '=', 'PRODUCT.id');
		// 					})
		// 					->join('units AS UNIT', function($join){
		// 						$join->on('UNIT.id', '=', 'PRODUCT.unit_id');
		// 					})
		// 					->select('PRORAT.id','PRORAT.rate','PRODUCT.name as product_name','PRODUCT.picture','UNIT.name as unit_name')
		// 					->where([
		// 			                ['PRODUCT.id', '=', $product_id],  
		// 			            ])
		// 					->first();

							
		      

		// 			$products=[
		// 				'id'          => $product_info->id,
		// 				'product_name'=> $product_info->product_name,
		// 				'rate'        => $product_info->rate,
		// 				'unit_name'   => $product_info->unit_name,
		// 			];		
				

		//  $session_data = Session::push('cart_product', $products);
		//  //print_r(Session::get('cart_product'));exit();

					
		// return response([
  //       		'status' => "success",
  //       		'data'   => $product_info,

  //       ]);				
  //   }
}
