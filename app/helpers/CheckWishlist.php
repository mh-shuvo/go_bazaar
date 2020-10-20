<?php 

class CheckWishlist{
	public static function isWishlisted($product_id,$client_id){
		$data = DB::table('wishlists')->where('product_id',$product_id)->where('client_id',$client_id)->whereNull('deleted_at')->get();
		if(count($data) > 0){
			return true;
		}
		else{
			return false;
		}
	}
	public static function isCarted($product_id){
		$cart_list = session('cart_product');
		$add_to_cart = false;
		$cart_quantity = 0;
		if(!empty($cart_list)){
			foreach($cart_list as $item){
				if($item['id'] == $product_id){
					$add_to_cart = true;
					$cart_quantity = $item['quantity'];
				}
			}
		}
		return [
			'status' =>$add_to_cart,
			'qty' => $cart_quantity
		];
	}
}

?>