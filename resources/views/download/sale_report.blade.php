<!DOCTYPE html>
<html>
    <head>
        <title>{{$report_name}}</title>
        <style type="text/css">
            body{
                font-family: 'bangla';
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
                bproduct: 1px solid #ccc;
                text-align: left;
            }
             @page {
                header: page-header;
                footer: page-footer;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div style="width: 100%;">
            	<div style="float:left;width: 15%;">
            		<img src="{{asset('public/logo/narsingdi_logo_black.png')}}" style="width: 100px; height: 50px;">
            	</div>
            	<div style="text-align:center;font-weight: bold;float:left; width: 60%;">
	            	<center>
	            		<p style="font-size: 18px;font-family: sans-serif; font-weghit:bold; padding:0;margin:0">{{$shop}}</p>
                        <p style="font-size: 18px; font-weghit:bold;padding:0;margin:0">{{$report_name}}</p>
                        @if(isset($date_text))
                        <p style="font-size: 14px; font-family:sans-serif;padding-top:0;padding-bottom:5px;margin:0">{{$date_text}}</p>
                        @endif
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
            @if(isset($employe_name))
            <div style="height:50px;margin-top:35px; text-align:center;">
                     
                <div style="width:30%; float:left">
                <span>তারিখ হতেঃ <span style="font-family:sans-serif;font-size:12px;">{{$from_date}}</span></span>
                </div>
                <div style="width:30%; float:left">
                    <span>কর্মীর নামঃ <span style="font-family:sans-serif;font-size:12px;">{{$employe_name}}</span></span>
                </div>
                <div style="width:30%; float:left">
                <span>তারিখ পর্যন্তঃ  <span style="font-family:sans-serif;font-size:12px;">{{$to_date}}</span></span>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    @if(count($products) != 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>নং</th>
                                <th>পন্যের নাম</th>
                                <th>পরিমাণ</th>
                                <th>বিক্রয় মূল্য (প্রতি একক)</th>
                                <th>মোট টাকা</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $count = 1;$total_amount=0; $total_selling_price=0;?>
                            @foreach($products as $product)
                            <tr>
                                <td>{{converter::en2bn($count)}}</td>
                                <td style="font-family:sans-serif;">{{$product->name}}</td>
                                <td>{{converter::en2bn($product->quantity)}}</td>
                                <td style="text-align:right;">{{converter::en2bn($product->selling_price,2)}}</td>
                                <td style="text-align:right;">{{converter::en2bn($product->amount,2)}}</td>
                            </tr>
                            <?php $count++; $total_amount+=$product->amount; $total_selling_price+=$product->selling_price;?> @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2"></th>
                                <th style="text-align:right;">মোট:</th>
                                <th style="text-align:right;">{{converter::en2bn(number_format($total_selling_price),2)}}</th>
                                <th style="text-align:right;">{{converter::en2bn(number_format($total_amount),2)}}</th>
                            </tr>
                        </tfoot>
                    </table>
                    @else
                    <h5 style="text-align: center; color: red;">দুঃখিত! কোঁন তথ্য খুজে পাওয়া যায়নি</h5>
                    @endif
                </div>
            </div>
        </div>
        <htmlpagefooter name="page-footer">
            <p style="font-family: sans-serif; text-align:right; font-size:13px;">Developed By <a href="http://innovationit.com.bd/" style="text-decoration:none">Innovation IT</a></p>
        </htmlpagefooter>
    </body>
</html>
