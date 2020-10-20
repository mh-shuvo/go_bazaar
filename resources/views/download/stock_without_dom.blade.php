<!DOCTYPE html>
<html>
    <head>
        <title>স্টক রিপোর্ট</title>
        <meta charset="utf-8">
        <style type="text/css">
            body{
                font-family: 'bangla';
            }
            .m-0 {
                margin: 0;
            }
            .fs-12 {
                font-size: 12px;
            }
            .text-center {
                text-align: center;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            /* Zebra striping */
            tr:nth-of-type(odd) {
                background: #eee;
            }
            th {
                background: #333;
                color: white;
                font-weight: bold;
            }
            td,
            th {
                padding: 6px;
                border: 1px solid #ccc;
                text-align: left;
            }
            @page {
                header: page-header;
                footer: page-footer;
            }
        </style>
    </head>
    <body>
        <div>
            <div style="width: 100%;">
            	<div style="float:left;width: 15%;">
            		<img src="{{asset('public/logo/narsingdi_logo_black.png')}}" style="width: 100px; height: 50px;">
            	</div>
            	<div style="text-align:center;font-weight: bold;float:left; width: 60%;">
	            	<center>
	            		<p style="font-size: 18px;font-family: sans-serif;">{{$shop}}</p>
	                	<p style="font-size: 18px;padding-top:0;padding-bottom:5px;margin:0; font-weight:bold;">স্টক রিপোর্ট</p>
                        <p style="font-size: 18px;padding-top:0;padding-bottom:5px;margin:0">{{$dateText}}</p>
	            	</center>
            	</div>
            	<div style="float:right; width: 15%;">
            		<?php
            			if($shop_image!=null){
            		?>
            		<img src="{{asset('public/upload/supplier')}}/{{$shop_image}}" style="width: 100px; height: 50px;">
            		<?php } ?>
            	</div>
            </div>
            <div style="width: 100%">
                @if(isset($products) && count($products) != 0)
                <table class="table">
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
        <htmlpagefooter name="page-footer">
            <p style="font-family: sans-serif; text-align:right; font-size:13px;">Developed By <a href="http://innovationit.com.bd/" style="text-decoration:none">Innovation IT</a></p>
        </htmlpagefooter>
    </body>
</html>
