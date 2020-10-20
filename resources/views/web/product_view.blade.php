@extends("layouts.web")
@section("title","পণ্যের তালিকা")
@section("content")
<section class="pt-3 pb-3 page-info section-padding border-bottom bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ url('/') }}"><strong><span class="mdi mdi-home"></span> Home</strong></a>
            </div>
        </div>
    </div>
</section>
<section class="shop-single section-padding pt-3">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="shop-detail-left">
                    <div class="shop-detail-slider">
                        <div id="sync1" class="owl-carousel product_view_img">
                            <div class="item">
                                @if($get_product_info->picture !='')
                                @php
                                    $images = explode("##",$get_product_info->picture);
                                    $img = $images[0];
                                @endphp
                                <img alt="" data-src="{{ asset('public/upload/product')}}/{{$img}}" class="img-fluid img-center lozad">
                                @else
                                <img alt="" data-src="{{ asset('public/upload/product/default.jpg') }}" class="img-fluid img-center lozad">
                                @endif

                            </div>

                        </div>
                        <div id="sync2" class="owl-carousel">
                            @if($get_product_info->picture !='')
                                @php
                                    $images = explode("##",$get_product_info->picture);
                                @endphp
                                @foreach($images as $img)
                            <div class="item">
                                <img alt="" data-src="{{ asset('upload/product')}}/{{$img}}" class="img-fluid img-center" lozad>
                            </div>
                             @endforeach
                             @else
                             <div class="item">
                             <img alt="" data-src="{{ asset('upload/product/default.jpg') }}" class="img-fluid img-center lozad">
                             </div>
                             @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="shop-detail-right product_view_img">
                    <table>
                        <tr>
                            <td class="product_name_td">
                                <h5> পণ্যের নাম </h5>
                            </td>
                            <td>
                                <h5> : {{ $get_product_info->product_name}} </h5>
                            </td>
                        </tr>
                        <tr>
                            <td class="product_name_td">
                                <h5> মজুদ </h5>
                            </td>
                            <td>
                                <h5> : {{$total_stock}} <!-- {{ $get_product_info->unit_name}} --> </h5>
                            </td>
                        </tr>
                        <tr>
                            <td class="product_name_td">
                                <h5> রেট </h5>
                            </td>
                            <td>
                                <h5> : {{ $get_product_info->rate}} </h5>
                            </td>
                        </tr>
                        <tr>
                            <td class="product_name_td">
                                <h5> উপজেলা </h5>
                            </td>
                            <td>
                                <h5> : {{ $get_product_info->upazila_name}} </h5>
                            </td>
                        </tr>
                        <tr>
                            <td class="product_name_td">
                                <h5> ইউনিয়ন </h5>
                            </td>
                            <td>
                                <h5> : {{ $get_product_info->union_name}} </h5>
                            </td>
                        </tr>
                        <tr>
                            <td width="140px">
                                <h5>পরিমান </h5>
                            </td>
                            <td>
                                <table>
                                    <td> <button class="plus-btn" type="button" name="button" disabled id="minus_sign" onclick="quantity_update(-1, 2)">
                                            {{-- <img src="plus.svg" alt="" /> --}} -</button></td>
                                    <td> <input style="width: 100px" type="text" name="quantity" id="quantity" min="1" value="1" class="form-control border-form-control form-control-sm input-number" onkeyup="quantity_update(this.value, 1)"></td>
                                    <td> <button class="plus-btn" type="button" name="button" id="plus_sign" onclick="quantity_update(1, 2)">
                                            {{-- <img src="plus.svg" alt="" /> --}} + </button></td>
                                </table>

                            </td>
                        </tr>
                        <tr>
                            <td class="product_name_td">
                                <h5> বিস্তারিত </h5>
                            </td>
                            <td>
                                <h5> : {{ $get_product_info->description}} </h5>
                            </td>
                        </tr>
                    </table>


                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-body cart-table">
                    <input type="hidden" name="rate" id="rate" value="{{ $get_product_info->rate}}">

                    <button class="btn btn-secondary btn-lg btn-block text-left" type='submit'>

                        <span class="float-left">
                            <i class="mdi mdi-cart-outline"></i> ক্রয় করুন
                        </span>

                        <span class="float-right"><strong> মোট : ৳ <span id="total_amount_show"> {{ $get_product_info->rate}}</span> </strong> <span class="mdi mdi-chevron-right"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
