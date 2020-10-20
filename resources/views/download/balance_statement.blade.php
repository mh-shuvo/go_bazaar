<!DOCTYPE html>
<html>
    <head>
        <title>ব্যালেন্স   স্টেটমেন্ট</title>
        <meta charset="utf-8" />
        <style type="text/css">
            body {
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
	            		<p style="font-size: 18px; font-family: sans-serif;">{{$shop}} </p>
	                	<p style="font-size: 18px;">ব্যালেন্স   স্টেটমেন্ট</p>
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
              
                <table class="table">
                    <thead>
                        <tr>
                            <th>নং</th>

                            <th>হেড</th>

                            <th>টাকার পরিমাণ</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>১</td>
                            <td>মোট স্টক</td>
                            <td>{{ converter::en2bn(number_format($total_stock))}}</td>
                        </tr>
                        <tr>
                            <td>২</td>
                            <td>মোট বিক্রয়</td>
                            <td>{{ converter::en2bn(number_format($total_sale))}}</td>
                        </tr>
<!--                         <tr>
                            <td>2</td>
                            <td>মোট বিকৃত পন্ন্যের ক্রয় খরচ</td>
                            <td>{{ converter::en2bn($total_purchase)}}</td>
                        </tr> -->
                        <tr>
                            <td>৩</td>
                            <td>{{$profit_loss<0?"লস":"লাভ"}}</td>
                            <td>{{$profit_loss<0?converter::en2bn(number_format(abs($profit_loss))):converter::en2bn(number_format($profit_loss))}}</td>
                        </tr>
                        <tr>
                            <td>৪</td>
                            <td>মোট খরচ</td>
                            <td>{{ converter::en2bn(number_format($total_expense))}}</td>
                        </tr>
                    </tbody>
                </table>
               
            </div>
        </div>
        <htmlpagefooter name="page-footer">
            <p style="font-family: sans-serif; text-align:right; font-size:13px;">Developed By <a href="http://innovationit.com.bd/" style="text-decoration:none">Innovation IT</a></p>
        </htmlpagefooter>
    </body>
</html>
