<!DOCTYPE html>
<html>
    <head>
        <title>বিক্রয় কর্মী রিপোর্ট</title>
        
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
        <div class="container">
			<div style="width: 100%;">
            	<div style="float:left;width: 15%;">
            		<img src="{{asset('public/logo/narsingdi_logo_black.png')}}" style="width: 100px; height: 50px;">
            	</div>
				 
            	<div style="text-align:center;font-weight: bold;float:left; width: 60%;">
	            	<center>
	            		<p style="font-size: 18px;font-family: sans-serif; font-weghit:bold; padding:0;margin:0">{{$shop}}</p>
	                	<p style="font-size: 18px; font-weghit:bold;padding:0;margin:0">বিক্রয় কর্মী রিপোর্ট</p>
	                	<p style="font-size: 18px;font-weghit:bold;padding:0;margin:0">{{$dateText}}</p>
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
            <div class="row">
                <p style="width: 100%; padding: 5px;">
                    <span style="width: 10%; margin-right: 5px; font-size: 18px;">নামঃ </span>
                    <span style="width: auto; font-family: sans-serif; font-size: 15px;">{{$employe->name}}</span>
                </p>
                <p style="width: 100%;">
                    <span style="width: 10%; margin-right: 5px; font-size: 18px;">মোবাইলঃ </span>
                    <span style="width: auto; font-family: sans-serif; font-size: 15px;">{{$employe->mobile}}</span>
                </p>
            </div>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    @php $total_amount=0; $total_discount = 0; $total_net=0; $dateText; @endphp
                    @if(count($data) != 0)
                    <table>
                        <thead>
                            <tr>
                                <th>অর্ডার নং</th>
                                <th>অর্ডার তারিখ</th>
                                <th>কাস্টমারের নাম</th>
                                <th>অরিজিন</th>
                                <th>মোট টাকা</th>
                                <th>ডিসকাউন্ট</th>
                                <th>নীট টাকা</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $order) @php $total_amount+=$order->total_amount; $total_discount+=$order->discount; $total_net+=$order->net_amount; @endphp
                            <tr>
                                <td>{{converter::en2bn($order->order_id)}}</td>
                                <td>{{converter::en2bn($order->created_at)}}</td>
                                <td style="font-family:sans-serif;">{{$order->client_name}}</td>
                                <td>
                                    @php
                                        if ($order->origin == 1) {
                                            echo '<span class="badge badge-teal">ওয়েবসাইট</span>';
                                        } else {
                                            echo '<span class="badge badge-teal">পস</span>';
                                        }
                                    @endphp
                                </td>
                                <td style="text-align:right;">{{converter::en2bn(number_format($order->total_amount,2),2)}}</td>
                                <td style="text-align:right;">{{converter::en2bn(number_format($order->discount,2),2)}}</td>
                                <td style="text-align:right;">{{converter::en2bn(number_format($order->net_amount,2))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3"></th>
                                <th style="text-align:right;">মোট</th>
                                <th style="text-align:right;">{{converter::en2bn(number_format($total_amount,2),2)}}</th>
                                <th style="text-align:right;">{{converter::en2bn(number_format($total_discount,2),2)}}</th>
                                <th style="text-align:right;">{{converter::en2bn(number_format($total_net,2),2)}}</th>
                            </tr>
                        </tfoot>
                    </table>
                    @else
                    <h5 style="text-align: center; color: red;">দুঃখিত! কোন অর্ডার খুজে পাওয়া যায়নি</h5>
                    @endif
                </div>
            </div>
        </div>
	<htmlpagefooter name="page-footer">
            <p style="font-family: sans-serif; text-align:right; font-size:13px;">Developed By <a href="http://innovationit.com.bd/" style="text-decoration:none">Innovation IT</a></p>
        </htmlpagefooter>
    </body>
</html>
