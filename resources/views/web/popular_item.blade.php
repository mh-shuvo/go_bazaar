@extends("layouts.web")
@section("title","পণ্যের তালিকা")
@section("content")
<!-- Start container-fluid -->
{{-- {{ dd($main_menu_name) }} --}}

<section class="shop-list section-padding">
    <div class="container">

        <div class="row">

            <div class="col-md-9">

                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="breadcrumbs">
                            <p class="mb-0 text-dark" style="padding: 16px;text-align: left;font-size: 13px;">

                                <a class="text-green" href="{{ route('index') }}">হোম</a>

                          <a class="text-dark" href="javascript:void(0)">
                            /জনপ্রিয় ক্যাটাগরি

                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                @if(count($popular_item) != 0)
                <div class="row no-gutters">
                    @foreach($popular_item as $k=> $item)
                    <div class="col-md-4">
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

                                    <span class="wish mdi mdi-heart removeWish2 wish-selected" data-id="{{ $item->product_id }}" @if(session()->has('client_id')) data-client="{{session()->get('client_id')}}" @else data-client="0" @endif ></span>

                                    @else

                                    <span class="wish mdi mdi-heart-outline Addwish" data-id="{{ $item->product_id }}" @if(session()->has('client_id')) data-client="{{session()->get('client_id')}}" @else data-client="0" @endif ></span>

                                    @endif
                                </div>
                            </div>
                            <br>
                            <br>
                            <a href="{{ url('product/view')}}/{{ $item->product_id }}">
                                <div class="product-header">
                                    <!-- <span class="badge badge-success">50% OFF</span> -->
                                    @if($item->picture !='')
                                    @php
                                    $images = explode("##",$item->picture);
                                    $img = $images[0];
                                    @endphp
                                    <img class="img-fluid lozad" data-src="{{ asset('public/upload/product/') }}/{{ $img }}" alt="">
                                    <!-- <span class="veg text-success mdi mdi-circle"></span> -->
                                    @else

                                    <img class="img-fluid lozad" data-src="{{ asset('public/upload/product/default.jpg') }}" alt="">

                                    @endif
                                </div>
                                <div class="product-body">
                                    <h5>{{$item->product_name}} </h5>
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
                                    <input type="hidden" name="product_id" id="product_id{{ $k+1 }}" value="{{ $item->product_id }} ">
                                </div>
                            </a>
                            <div class="product-footer addCart">
                                <div class="row">
                                    <div class="col-sm-7">
                                        @php
                                        $isCartedProduct = CheckWishlist::isCarted($item->product_id);
                                        $cartedQty = $isCartedProduct['qty'];
                                        @endphp
                                        <div class="qty">

                                            <div class="input-group" @if(!$isCartedProduct['status']) style="display: none;" @else style="display: flex;" @endif id="plus_minus_block{{ $item->product_id }}">

                                                <span class="input-group-btn"><button @if($cartedQty <=1) disabled @endif; id="minus_sign{{ $item->product_id }}" onclick="CartProductQtyUpdate({{ $item->product_id }}, -1, 2)" class="btn btn-theme-round btn-number" type="button">-</button></span>

                                                <input name="product_quantity" type="text" min="1" value="{{$cartedQty}}" class="form-control border-form-control form-control-sm input-number" name="quantity" id="quantity{{ $item->product_id }}" style="margin-top: 0px;" onkeyup="CartProductQtyUpdate({{ $item->product_id }}, this.value, 1)">

                                                <span class="input-group-btn"><button id="plus_sign{{ $item->product_id }}" onclick="CartProductQtyUpdate({{ $item->product_id }}, 1, 2)" class="btn btn-theme-round btn-number" type="button">+</button>
                                                </span>

                                                <input type="hidden" name="product_id" id="product_id{{ $item->product_id }}" value="{{ $item->product_id }}">

                                            </div>

                                        </div>
                                        @if(($item->total_credit - $item->total_debit)<=0) <label class="badge badge-danger" style="font-size: 14px;">আউট অফ স্টক</label>
                                            @else

                                            <a id="add_to_cart_btn{{ $item->product_id }}" onclick="addCart('{{ $item->product_id }}',2,{{ $item->product_id }})" class="btn btn-secondary btn-sm float-right" style="@if(!$isCartedProduct['status']) display: block; @else display: none; @endif "">
                                            <i class=" mdi mdi-cart-outline addCartext"></i> <span class="addCartext">ক্রয় করুন</span>
                                            </a>
                                            @endif
                                    </div>
                                    <div class="col-sm-5">
                                        <p class="offer-price mb-0">
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
                                </p>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-sm-12 text-center">
                        <p class="mt-5 mb-5">{{$popular_item->links()}}</p>
                    </div>
                </div>

                @else
                <h3 class="mt-5 text-danger text-center">জনপ্রিয় ক্যাটাগরি এর কোন পন্য খুজে পাওয়া যায়নি</h3>
                @endif

            </div>
        </div>
    </div>
</section>
<!-- end container-fluid -->
@endsection
