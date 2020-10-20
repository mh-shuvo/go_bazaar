@extends("layouts.web")
@section("title","পণ্যের তালিকা")
@section("content")
{{-- @php dd($data) @endphp --}}

<section class="cart-page section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

     {{--              <button type="button" class="btn btn-primary" onclick="number_convert(250)" >submit</button> --}}
                {{-- <form action="{{ route('product.checkout') }}" method="post" enctype="multipart/form-data"> --}}
                    {{-- @csrf --}}
                    <div class="card card-body cart-table">
                        <div class="table-responsive">
                            <table class="table cart_summary">
                                <thead>
                                    <tr>
                                        <th class="cart_product">পণ্যের ছবি </th>
                                        <th>পণ্যের নাম</th>
                                        <th>সরবরাহকারী</th>
                                        <th>মূল্য </th>
                                        <th>পরিমান</th>
                                        <th width="100px"> মোট </th>
                                        <th class="action"><i class="mdi mdi-delete-forever"></i></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php  $subtotal = 0;
                                            $net_amount = 0;
                                            $discount = 0;@endphp

                                    @foreach($cart_products as $k => $item)

                                    <tr>
                                        <td class="cart_product">

                                            <img class="img-fluid cart_product_img lozad" data-src="{{ asset('public/upload/product') }}/{{ $item['picture'] }}" alt="" />
                                        </td>

                                        <td class="cart_description">
                                            <h5 class="product-name"><a href="javascript:void(0)">{{ $item['product_name'] }}</a></h5>
                                            <h6></h6>
                                        </td>

                                        <td>
                                            {{ $item['shop_name'] }}<br /> <span>{{ $item['supplier_address'] }}</span>
                                        </td>

                                        <?php
                                        //offer calculation
                                            $price = 0;
                                            $total_product_price = 0;
                                            if($item['offer_id']!=null){
                                                if($item['offer_type'] == 1){
                                                    $price = $item['rate'] - $item['offer_amount'];
                                                }
                                                else{
                                                    $price = $item['rate'] - (($item['rate'] * $item['offer_amount'])/100);
                                                }
                                            }
                                            else{
                                                $price = $item['rate'];
                                            }

                                            $total_product_price = $price * $item['quantity'];
                                            $subtotal+= $item['rate']* $item['quantity'];
                                            $net_amount+=$total_product_price;
                                            $discount+=$subtotal - $net_amount;

                                        ?>

                                        <td class="price">
                                            <span>৳ {{ converter::en2bn(number_format($price,2)) }} </span>
                                        </td>

                                        <td class="qty">
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button id="minus_sign{{$k+1 }}" onclick="quantity_update('{{ $k+1 }}', -1, '2')" @if($item['quantity'] <=1) disabled="disabled" @endif class="btn btn-theme-round btn-number" type="button">-</button>
                                                </span>

                                                <input name="product_quantity[]" onkeyup="quantity_update('{{ $k+1 }}', this.value, '1')" id="quantity{{ $k+1 }}" type="text" max="10" min="1" value="{{ $item['quantity'] }}" class="form-control border-form-control form-control-sm input-number" name="quant[1]" required="">

                                                <span class="input-group-btn">
                                                    <button onclick="quantity_update('{{ $k+1 }}', 1, '2')" class="btn btn-theme-round btn-number" type="button">+</button>
                                                </span>
                                            </div>

                                            <span style="color: red" id="order_error_msg{{ $k+1 }}"></span>

                                        </td>

                                    

                                        <td width="100px" class="price">
                                            ৳ <span id="totalPrice{{ $k+1 }}">
                                                {{ converter::en2bn(number_format( $total_product_price,2)) }}
                                            </span>
                                            <input type="hidden" name="total_product_price[]" id="totalProductPrice{{$k+1}}" class="totalProductPrice" value="{{ $total_product_price }}" />
                                        </td>

                                        <td class="action">

                                            <input type="hidden" id="product_id{{ $k+1 }}" name="product_id" value="{{ $item['id']}}">
                                            
                                            <input type="hidden" id="rate{{ $k+1 }}" name="rate" value="{{ $price}}">
                                            
                                            <input type="hidden" name="stockquentity" id="stockQuentity{{$k+1}}" value="{{$data[$item['id']]['available']}}">

                                            <a class="btn btn-sm btn-danger" data-original-title="Remove" href="{{ url('product/remove') }}/{{ $item['id'] }}" title="" data-placement="top" data-toggle="tooltip"><i class="mdi mdi-close-circle-outline"></i></a>

                                        </td>
                                    </tr>


                                    @endforeach

                                </tbody>
                                <tfoot>

                                    <tr>
                                        <td class="text-right" colspan="6"><strong>মোট</strong></td>
                                        <td class="" colspan="2"><strong> : ৳ <span id="totalSum"> {{ converter::en2bn(number_format($subtotal,2)) }} </span></strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="text-right" colspan="3">ছাড়</td>
                                        <td colspan="2"> : ৳ {{converter::en2bn($discount,2)}} </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="6"><strong> সর্বমোট</strong></td>
                                        <td class="" colspan="2"><strong> : ৳ <span id="netAmount"> {{ converter::en2bn(number_format($net_amount,2)) }} </span></strong></td>
                                    </tr>

                                </tfoot>
                            </table>
                        </div>
                        @if(count($cart_products)>0)
                        <a @if(session()->has('client_id')) href="{{ route('product.checkout') }}" @else href='javascript:void(0)' data-target="#authentication_modal" data-toggle="modal" @endif class="btn btn-secondary btn-lg btn-block text-left" type="button">
                            <span class="float-left"><i class="mdi mdi-cart-outline"></i> চেক আউট করুন </span><span class="float-right"><strong> ৳ <span id="totalAmount">{{converter::en2bn($net_amount)}} </span></strong> <span class="mdi mdi-chevron-right"></span></span>
                        </a>
                        @else

                        <button class="btn btn-secondary btn-lg btn-block" disabled=""><i class="mdi mdi-cart-outline"></i> চেক আউট করুন</button>
                        @endif
                    </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>
</section>

@endsection
