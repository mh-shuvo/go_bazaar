@extends('layouts.web')
@section("title","Home")
@section('slider')
{{-- slider --}}
@include("layouts.includes.web.slider")

@endsection
@section("content")

{{-- {{dd($product_info)}} --}}
<section class="product-items-slider section-padding">
  
    @foreach ($product_info as $pitem):

    <div class="container">
        <div class="section-header">
            <h5 class="heading-design-h5">{{$pitem["category_name"]}}
                @if(count($pitem['product_list']) >= 8)
                    <a class="float-right text-secondary" href="{{ url('product/list')}}/{{ $pitem["category_id"] }}"> আরো দেখুন
                    </a>
                @endif
            </h5>
        </div>
        <div class="owl-carousel owl-carousel-featured home_product_list" >
            @foreach($pitem['product_list'] as $k => $sitem)
            <div class="item" >
                <div class="product">
                    <div class="row">
                        <div class="col-sm-6">
                            @if($sitem->offer_id != null)
                            <span class="badge badge-danger" style="font-size: 13px; padding: 2px;">{{$sitem->offer_amount}}{{$sitem->offer_type==1?"৳":"%"}} ছাড়
                            </span>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            
                            @if((session()->has('client_id')) && CheckWishlist::isWishlisted($sitem->product_id,session()->get('client_id')))

                            <span class="wish mdi mdi-heart removeWish2 wish-selected" data-id ="{{ $sitem->product_id }}" @if(session()->has('client_id')) data-client="{{session()->get('client_id')}}" @else data-client="0" @endif ></span>

                            @else

                            <span class="wish mdi mdi-heart-outline Addwish" data-id ="{{ $sitem->product_id }}" @if(session()->has('client_id')) data-client="{{session()->get('client_id')}}" @else data-client="0" @endif ></span>

                            @endif
                        </div>
                    </div>

                     
                    {{-- <br>
                    <br> --}}
                    <a href="{{ url('product/view')}}/{{ $sitem->product_id }}">
                        <div class="product-header">

                            <!-- <span class="badge badge-success">50% OFF</span> -->
                            @if($sitem->picture !='')

                            @php
                                $images = explode("##",$sitem->picture);
                                $img = $images[0];
                            @endphp
                            <img class="img-fluid lozad" data-src="{{ asset('public/upload/product/') }}/{{ $img }}" alt="">
                            <!-- <span class="veg text-success mdi mdi-circle" onclick="alert(1)"></span> -->
                            @else
                            <img class="img-fluid lozad" data-src="{{ asset('public/upload/product/default.jpg') }}" alt="">
                            @endif
                        </div>
                        <div class="product-body">
                            <h5>
                                {{$sitem->product_name}}</h5>
                            <h6>
                                <strong style="" class="mdi mdi-home">
                                    {{$sitem->shop_name}}
                                </strong>
                            </h6>
                            <h6 class="text-truncate" title="{{ $sitem->district_name }}, {{ $sitem->upazila_name }}">
                                <strong class="mdi mdi-map-marker">
                                {{ $sitem->district_name }}, {{ $sitem->upazila_name }}
                                </strong>
                            </h6>
                            
                        </div>
                    </a>
                        <div class="product-footer addCart">
                          <div class="row">
                               <div class="col-sm-6">
                                    @php
                                        $isCartedProduct = CheckWishlist::isCarted($sitem->product_id);
                                        $cartedQty = $isCartedProduct['qty'];
                                        @endphp
                                         <div class="qty">
                                             
                                            <div class="input-group" @if(!$isCartedProduct['status']) style="display: none;" @else style="display: flex;" @endif id="plus_minus_block{{ $sitem->product_id }}" >

                                            <span class="input-group-btn">
                                                <button @if($cartedQty <= 1) disabled @endif; id="minus_sign{{ $sitem->product_id }}" onclick="CartProductQtyUpdate({{ $sitem->product_id }}, -1, 2)" class="btn btn-theme-round btn-number" type="button" style="width: 20px;">-</button></span>

                                            <input name="product_quantity" type="text" min="1" value="{{$cartedQty}}" class="form-control border-form-control form-control-sm input-number" name="quantity" id="quantity{{ $sitem->product_id }}" style="margin-top: 0px;" onkeyup="CartProductQtyUpdate({{ $sitem->product_id }}, this.value, 1)">

                                            <span class="input-group-btn"><button id="plus_sign{{ $sitem->product_id }}" onclick="CartProductQtyUpdate({{ $sitem->product_id }}, 1, 2)" class="btn btn-theme-round btn-number" type="button" style="width: 20px;">+</button>
                                            </span>

                                            <input type="hidden" name="product_id" id="product_id{{ $sitem->product_id }}" value="{{ $sitem->product_id }}">
                                            
                                        </div> 

                                         </div>
                                           @if(($sitem->total_credit - $sitem->total_debit)<=0)
                                            <label class="badge badge-danger" style="font-size: 14px;">আউট অফ স্টক</label>
                                            @else
                                            <a id="add_to_cart_btn{{ $sitem->product_id }}" onclick="addCart('{{ $sitem->product_id }}',2,{{ $sitem->product_id }})" class="btn btn-secondary btn-sm" style="padding: 6px 0;@if(!$isCartedProduct['status']) display: block; @else display: none; @endif">
                                                <i class="mdi mdi-cart-outline addCartext"></i> <span class="addCartext">ক্রয় করুন</span>
                                            </a>
                                            @endif
                              </div>
                              <div class="col-sm-6">
                                <p class="offer-price mb-0 text-right">
                                <!-- offer calculation -->
                                @php
                                $offer_rate = 0;
                                if($sitem->offer_type!=null){
                                    if($sitem->offer_type == 1){
                                        $offer_rate = $sitem->rate - $sitem->offer_amount;
                                    }
                                    else{
                                         $offer_rate = $sitem->rate - (($sitem->offer_amount * $sitem->rate)/100);
                                    }

                                    echo '৳ '.converter::en2bn($offer_rate,2);
                                    echo "<span class='regular-price'>৳ ".converter::en2bn($sitem->rate,2)."</span>";
                                }
                                else{
                                 echo '৳ '.converter::en2bn($sitem->rate,2); 
                              }
                                @endphp

                                </p>  
                              </div>
                          </div>
                        </div>

                   
                </div>
            </div>
            @endforeach
        </div>
    </div>


    @endforeach

    <div class="container">
        <div class="section-header">
            <h5 class="heading-design-h5">জনপ্রিয় ক্যাটাগরি
                @if(count($popular_item) >= 8)
                    <a class="float-right text-secondary" href="{{ url('/popular_category')}}"> আরো দেখুন
                    </a>
                @endif
            </h5>
        </div>
        <div class="owl-carousel owl-carousel-featured home_product_list" >
            @foreach($popular_item as $k => $item)
            <div class="item" >
                <div class="product">
                    <div class="row">
                        <div class="col-sm-6">
                            @if($item->offer_id != null)
                            <span class="badge badge-danger" style="font-size: 13px; padding: 2px;">{{$item->offer_amount}}{{$item->offer_type==1?"৳":"%"}} ছাড়
                            </span>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            
                            @if((session()->has('client_id')) && CheckWishlist::isWishlisted($item->product_id,session()->get('client_id')))

                            <span class="wish mdi mdi-heart removeWish2 wish-selected" data-id ="{{ $item->product_id }}" @if(session()->has('client_id')) data-client="{{session()->get('client_id')}}" @else data-client="0" @endif ></span>

                            @else

                            <span class="wish mdi mdi-heart-outline Addwish" data-id ="{{ $item->product_id }}" @if(session()->has('client_id')) data-client="{{session()->get('client_id')}}" @else data-client="0" @endif ></span>

                            @endif
                        </div>
                    </div>

                     
                    {{-- <br>
                    <br> --}}
                    <a href="{{ url('product/view')}}/{{ $item->product_id }}">
                        <div class="product-header">

                            <!-- <span class="badge badge-success">50% OFF</span> -->
                            @if($item->picture !='')

                            @php
                                $images = explode("##",$item->picture);
                                $img = $images[0];
                            @endphp
                            <img class="img-fluid lozad" data-src="{{ asset('public/upload/product/') }}/{{ $img }}" alt="">
                            <!-- <span class="veg text-success mdi mdi-circle" onclick="alert(1)"></span> -->
                            @else
                            <img class="img-fluid lozad" data-src="{{ asset('public/upload/product/default.jpg') }}" alt="">
                            @endif
                        </div>
                        <div class="product-body">
                            <h5>
                                {{$item->product_name}}</h5>
                            <h6>
                                <strong style="" class="mdi mdi-home">
                                    {{$item->shop_name}}
                                </strong>
                            </h6>
                            <h6 class="text-truncate" title="{{ $item->district_name }}, {{ $item->upazila_name }}">
                                <strong class="mdi mdi-map-marker">
                                {{ $item->district_name }}, {{ $item->upazila_name }}
                                </strong>
                            </h6>
                            
                        </div>
                    </a>
                        <div class="product-footer addCart">
                          <div class="row">
                               <div class="col-sm-6">
                                    @php
                                        $isCartedProduct = CheckWishlist::isCarted($item->product_id);
                                        $cartedQty = $isCartedProduct['qty'];
                                        @endphp
                                         <div class="qty">
                                             
                                            <div class="input-group" @if(!$isCartedProduct['status']) style="display: none;" @else style="display: flex;" @endif id="plus_minus_block{{ $item->product_id }}" >

                                            <span class="input-group-btn">
                                                <button @if($cartedQty <= 1) disabled @endif; id="minus_sign{{ $item->product_id }}" onclick="CartProductQtyUpdate({{ $item->product_id }}, -1, 2)" class="btn btn-theme-round btn-number" type="button" style="width: 20px;">-</button></span>

                                            <input name="product_quantity" type="text" min="1" value="{{$cartedQty}}" class="form-control border-form-control form-control-sm input-number" name="quantity" id="quantity{{ $item->product_id }}" style="margin-top: 0px;" onkeyup="CartProductQtyUpdate({{ $item->product_id }}, this.value, 1)">

                                            <span class="input-group-btn"><button id="plus_sign{{ $item->product_id }}" onclick="CartProductQtyUpdate({{ $item->product_id }}, 1, 2)" class="btn btn-theme-round btn-number" type="button" style="width: 20px;">+</button>
                                            </span>

                                            <input type="hidden" name="product_id" id="product_id{{ $item->product_id }}" value="{{ $item->product_id }}">
                                            
                                        </div> 

                                         </div>
                                           @if(($item->total_credit - $item->total_debit)<=0)
                                            <label class="badge badge-danger" style="font-size: 14px;">আউট অফ স্টক</label>
                                            @else
                                            <a id="add_to_cart_btn{{ $item->product_id }}" onclick="addCart('{{ $item->product_id }}',2,{{ $item->product_id }})" class="btn btn-secondary btn-sm" style="padding: 6px 0;@if(!$isCartedProduct['status']) display: block; @else display: none; @endif">
                                                <i class="mdi mdi-cart-outline addCartext"></i> <span class="addCartext">ক্রয় করুন</span>
                                            </a>
                                            @endif
                              </div>
                              <div class="col-sm-6">
                                <p class="offer-price mb-0 text-right">
                                <!-- offer calculation -->
                                @php
                                $offer_rate = 0;
                                if($item->offer_type!=null){
                                    if($item->offer_type == 1){
                                        $offer_rate = $item->rate - $item->offer_amount;
                                    }
                                    else{
                                         $offer_rate = $item->rate - (($item->offer_amount * $item->rate)/100);
                                    }

                                    echo '৳ '.converter::en2bn($offer_rate,2);
                                    echo "<span class='regular-price'>৳ ".converter::en2bn($item->rate,2)."</span>";
                                }
                                else{
                                 echo '৳ '.converter::en2bn($item->rate,2); 
                              }
                                @endphp

                                </p>  
                              </div>
                          </div>
                        </div>

                   
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection


@section('js')
<script type="text/javascript">

</script>
@endsection
