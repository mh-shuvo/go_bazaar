@extends("layouts.web")
@section("title","পণ্যের তালিকা")
@section("content")

<section class="shop-single section-padding pt-3">
    <div class="container">
        {{-- <form action="{{ route('product.checkout') }}" method="post" enctype="multipart/form-data"> --}}
        {{-- @csrf --}}
        <div class="row">
            <div class="col-md-6">
                <div class="shop-detail-left">
                    <div class="shop-detail-slider">
                        <div class="favourite-icon">

                        </div>
                        <div id="sync1" class="owl-carousel">
                             @if($get_product_info->picture !='')
                                @php
                                $images = explode('##',$get_product_info->picture);
                                @endphp
                                @foreach($images as $img)
                                @if(!empty($img))
                                <div class="item">
                                <img alt="" data-src="{{ asset('public/upload/product')}}/{{$img}}" class="img-fluid img-center lozad">
                                 </div>
                                 @endif
                                 @endforeach

                                @else
                                <div class="item">
                                <img alt="" data-src="{{ asset('public/upload/product/default.jpg') }}" class="img-fluid img-center lozad">
                                 </div>
                                @endif
                            {{-- <div class="item"><img alt="" src="img/item/2.jpg" class="img-fluid img-center"></div> --}}

                        </div>

                        <div id="sync2" class="owl-carousel">
                            

                                @if($get_product_info->picture !='')
                                @php
                                $images = explode('##',$get_product_info->picture);
                                @endphp
                                @foreach($images as $img)
                                @if(!empty($img))
                                <div class="item">
                                <img alt="" data-src="{{ asset('public/upload/product')}}/{{$img}}" class="img-fluid img-center lozad">
                                 </div>
                                 @endif
                                 @endforeach

                                @else
                                <div class="item">
                                <img alt="" data-src="{{ asset('public/upload/product/default.jpg') }}" class="img-fluid img-center lozad">
                                 </div>
                                @endif

                           

                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="shop-detail-right">
                    <h2>{{ $get_product_info->product_name }}  </h2>
                    <h5 style="margin-top: 15px">{{ $get_product_info->shop_name }}</h5>
                    <h6>{{ $get_product_info->supplier_address }} </h6>

                        <span class="badge badge-primary" style="font-size: 15px; padding: 2px;">
                         @if($get_product_info->offer_id != null)
                         {{$get_product_info->offer_amount}}{{$get_product_info->offer_type==1?"৳":"%"}} ছাড়
                          @endif
                      </span>
                    

                    <table>
                        <td>
                            <p class="offer-price mb-0"><span class="text-success"><span id="totalPrice1">
                                @php
                                $offer_rate = 0;
                                if($get_product_info->offer_type!=null){
                                    if($get_product_info->offer_type == 1){
                                        $offer_rate = $get_product_info->rate - $get_product_info->offer_amount;
                                    }
                                    else{
                                         $offer_rate = $get_product_info->rate - (($get_product_info->offer_amount * $get_product_info->rate)/100);
                                    }

                                    echo '৳ '.converter::en2bn(($offer_rate*$cart_quantity),2);
                                    echo "<span class='regular-price'>৳ ".converter::en2bn(($get_product_info->rate * $cart_quantity),2)."</span>";
                                }
                                else{
                                 echo '৳ '.converter::en2bn(($get_product_info->rate * $cart_quantity),2); 
                              }
                                @endphp

                                </span></span></p>
                        </td>

                        <td width="50px"></td>

                        <td class="qty" width="200px">

                            @if($total_stock<=0)
                                <label class="badge badge-warning" style="font-size: 14px;">আউট অফ স্টক</label>
                            @else
                            <a id="add_to_cart_btn" onclick="addCart('{{ $get_product_info->product_id }}', 2)" class="btn btn-secondary btn-sm float-right" style="width: 100%;line-height: 30px; @if($add_to_cart == false) display: block; @else display: none; @endif">
                                <i class="mdi mdi-cart-outline addCartext"></i> <span class="addCartext">ক্রয় করুন</span>
                            </a>

                            @endif

                            <div class="input-group" @if($add_to_cart == false) style="display: none;" @else style="display: flex;" @endif id="plus_minus_block" >
                                <span class="input-group-btn"><button @if($cart_quantity <= 1) disabled @endif;  id="minus_sign1" onclick="quantity_update(1, -1, 2)" class="btn btn-theme-round btn-number" type="button">-</button></span>

                                <input name="product_quantity[]" type="text" min="1" value="{{ $cart_quantity }}" class="form-control border-form-control form-control-sm input-number" name="quantity" id="quantity1" style="margin-top: 30px;" onkeyup="quantity_update(1, this.value, 1)">

                                <span class="input-group-btn"><button id="plus_sign1" onclick="quantity_update(1, 1, 2)" class="btn btn-theme-round btn-number" type="button">+</button>
                                </span>
                            </div>

                            <span id="order_error_msg1"></span>

                            {{-- @endif --}}


                        </td>


                    </table>

                    <input type="hidden" name="product_id" id="product_id1" value="{{ $get_product_info->product_id }}">

                    <input type="hidden" name="rate" id="rate1" value="{{ $get_product_info->rate }}">

                    <input type="hidden" name="stockQuentity" id="stockQuentity1" value="{{ $total_stock }}">



                    @if(!empty($get_product_info->description))
                    <div class="short-description">
                        <p>{{ $get_product_info->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- </form> --}}
    </div>
</section>

<section class="product-items-slider section-padding bg-white border-top">
    <div class="container">
        <div class="section-header">
            <h5 class="heading-design-h5">এই ক্যাটেগরির আরো পন্য
                <a class="float-right text-secondary" href="{{ url('product/list')}}/{{ $get_product_info->category_id}}">আরো দেখুন</a>
            </h5>
        </div>
        <div class="owl-carousel owl-carousel-featured">
            {{-- {{ dd($get_product) }} --}}
            @foreach($get_product as $k=> $item)
                @if($get_product_info->product_id != $item->product_id)
                <div class="item">
                    <div class="product">
                        <a href="{{ url('product/view')}}/{{ $item->product_id}}">
                            <div class="product-header">
                                @if($item->picture !='')

                                <img class="img-fluid lozad" data-src="{{ asset('public/upload/product/') }}/{{ $item->picture}}" alt="">
                                <!-- <span class="veg text-success mdi mdi-circle"></span> -->
                                @else

                                <img class="img-fluid lozad" data-src="{{ asset('public/upload/product/default.jpg') }}" alt="">

                                @endif
                            </div>
                            <div class="product-body">
                                <h5>{{ $item->product_name}} </h5>
                                <h6>
                                    <strong style="" class="mdi mdi-map-marker">
                                        {{ $item->district_name}}
                                    </strong>
                                </h6>
                                <h6>
                                    <strong class="mdi mdi-map-marker">
                                        {{ $item->upazila_name}}
                                    </strong>
                                </h6>
                                <input type="hidden" name="product_id" id="product_id{{ $k+1}}" value="{{ $item->id}} ">

                                

                            </div>
                            <div class="product-footer">
                                <a onclick="addCart('{{ $item->product_id}}')" class="btn btn-secondary btn-sm float-right"><i class="mdi mdi-cart-outline addCartext"></i> <span class="addCartext"> ক্রয় করুন</span>
                                </a>
                                <p class="offer-price mb-0">৳ {{ converter::en2bn($item->rate,2) }}

                                </p>
                            </div>
                        </a>
                    </div>
                </div>
                @endif
            @endforeach

        </div>
    </div>
</section>

@endsection
