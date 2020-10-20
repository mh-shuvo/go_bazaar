<!DOCTYPE html>
<html>
    <head>
        <title>স্টক রিপোর্ট</title>
        <meta charset="utf-8">
        <link href="{{ asset('public/admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />

    </head>
    <body onload="window.print()">
        <div class="container mt-2 mb-2">
            <div class="row border border-primary p-3">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2 text-center">
                            <img src="{{asset('public/logo/narsingdi_logo_black.png')}}" style="width: 100px; height: 50px;">
                        </div>
                        <div class="col-md-8 text-center">
                                <p style="font-size: 18px;">{{$shop}}</p>
                                <p style="font-size: 18px;padding-top:0;padding-bottom:5px;margin:0; font-weight:bold;">স্টক রিপোর্ট</p>
                                <p style="font-size: 18px;padding-top:0;padding-bottom:5px;margin:0">{{$dateText}}</p>
                        </div>
                        <div class="col-md-2 text-center">
                            <?php
                                if($shop_image!=null){
                            ?>
                            <img src="{{asset('public/upload/supplier')}}/{{$shop_image}}" style="width: 100px; height: 50px;">
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if(isset($products) && count($products) != 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center;">নং</th>

                                            <th style="text-align:center;">ক্যাটাগরি</th>

                                            <th style="text-align:center;">সাব ক্যাটাগরি</th>

                                            <th style="text-align:center;">পন্যের নাম</th>

                                            <th style="text-align:center;">স্টক</th>

                                            <th style="text-align:center;">মূল্য</th>

                                            <th style="text-align:center;">মোট</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $count = 1;$total_stock=0;?>
                                        @foreach($products as $product)
                                        <tr>
                                            <td>{{converter::en2bn($count)}}</td>
                                            <td>{{$product['category']}}</td>
                                            <td style="font-family: sans-serif;">{{$product['sub_category']}}</td>
                                            <td style="font-family:sans-serif;">{{$product['name']}}</td>
                                            <td>{{converter::en2bn($product['current_stock'])}}</td>
                                            <td style="text-align:right;">{{converter::en2bn($product['buying_price'])}}</td>
                                            <td style="text-align:right;">{{converter::en2bn(number_format($product['total_stock']))}}</td>
                                        </tr>
                                        <?php $count++; $total_stock+=$product['total_stock'];?>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align:right;"></th>
                                            <th style="text-align:right;">মোট:</th>
                                            <th style="text-align:right;">{{converter::en2bn(number_format($total_stock))}}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                @else
                                <h5 style="text-align: center; color: red;">দুঃখিত। কোন তথ্য খুজে পাওয়া যায়নি</h5>
                                @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p style="font-family: sans-serif; text-align:right; font-size:13px;">Developed By <a href="http://innovationit.com.bd/" style="text-decoration:none">Innovation IT</a></p>
                        </div>
                    </div>
                </div>
            </div>              
        </div>
    </body>
</html>
