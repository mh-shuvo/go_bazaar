<div class="cart-sidebar">
    <div class="cart-sidebar-header">
        <h5>
            কার্ট<span class="text-success"> (  <span id="cart_product_total_sidebar">@if(Session('cart_product'))
                {{converter::en2bn(count(Session('cart_product')))}}
                @endif</span> টি পণ্য ) </span> <a data-toggle="offcanvas" class="float-right" href="javascript:void(0)"><i class="mdi mdi-close"></i>
            </a>
        </h5>
    </div>

    <div class="cart-sidebar-body" id="cart_body">
        <?php $sub_total = 0; $discount = 0; $net_amount = 0; $c_net_amount = 0; ?>
        
        @if(Session('cart_product'))
        @foreach(Session('cart_product') as $key => $value)

        <div class='cart-list-product'>
            <a style='cursor:pointer' class='float-right remove-cart' onclick="CartProductRemove({{ $value['id'] }})">
                <i class='mdi mdi-close'></i>
            </a>
            <img class='img-fluid' src="{{ asset('public/upload/product/'.$value['picture']) }}">

            <h5>
                <a>{{ $value['product_name'] }}</a>
            </h5>

            <h5><a>{{ $value['shop_name'] }} </a></h5>
            <h6><a>{{ $value['supplier_address'] }} </a></h6>

            <p class='offer-price mb-0'>
                <!-- {{ converter::en2bn($value['rate'] * $value['quantity']) }} <?php //$sub_total += ($value['rate'] * $value['quantity']) ?>  -->
                 @php
                    $offer_rate = 0;
                    if($value['offer_type']!=null){
                        if($value['offer_type'] == 1){
                            $offer_rate = $value['rate'] - $value['offer_amount'];
                        }
                        else{
                            $offer_rate = $value['rate'] - (($value['offer_amount'] * $value['rate'])/100);
                        }

                        echo '৳ '.converter::en2bn(($offer_rate*$value['quantity']),2);
                        echo "<span class='regular-price'>৳ ".converter::en2bn(($value['rate'] * $value['quantity']),2)."</span>";
                    }
                    else{
                        echo '৳ '.converter::en2bn(($value['rate'] * $value['quantity']),2); 
                        $offer_rate = $value['rate'] * $value['quantity'];
                    }

                    $sub_total += ($value['rate'] * $value['quantity']);
                    $net_amount += ($offer_rate * $value['quantity']);
                    $discount += $sub_total - $net_amount;
                @endphp
            </p>
        </div>

        @endforeach
        @endif

    </div>

    
    <div class="cart-sidebar-footer" id="cart_footer" @if(Session('cart_product')) style="display: block;" @else style="display: none;" @endif;>
        <div class="cart-store-details">
            <p>মোট <strong class="float-right" id="sub_total">{{ converter::en2bn(number_format($sub_total,2)) }}</strong></p>
            <p>ছাড় <strong class="float-right text-danger" id="discount">{{converter::en2bn($discount,2)}}</strong></p>
            <h6>সর্বমোট <strong class="float-right text-danger" id="net_amount">{{ converter::en2bn(number_format($net_amount,2)) }}</strong></h6>
        </div>

        <a href="{{ route('product.cart_list') }}">
            <button class="btn btn-secondary btn-lg btn-block text-left" type="button">
                <span class="float-left"><i class="mdi mdi-cart-outline"></i> অর্ডার স্থাপন করুন </span>

                <span class="float-right"><strong id="c_net_amount">{{ converter::en2bn(number_format($net_amount,2)) }}</strong> <span class="mdi mdi-chevron-right"></span></span>
            </button>
        </a>
    </div>

</div>
