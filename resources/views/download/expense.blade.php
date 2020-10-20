<!DOCTYPE html>
<html>
    <head>
        <title>খরচের রিপোর্ট</title>
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
                        <p style="font-size: 18px;font-family: sans-serif;">{{$shop}}</p>
                        <p style="font-size: 18px; font-weight:bold;">খরচের রিপোর্ট</p>
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
                @if(isset($expenses) && count($expenses) != 0)
                <table class="table">
                   <thead>

                                <tr>
                                    <th style="text-align:center">তারিখ</th>
                                    <th style="text-align:center">ক্যাটাগরি</th>
                                    <th style="text-align:center">টাকার পরিমাণ</th>
                                    <th style="text-align:center">নোট</th>

                                </tr>

                            </thead>

                    <tbody>
                        <?php $total=0;?>
                        @foreach($expenses as $expense)
                        <tr>
                            <td>{{converter::en2bn(date('d-m-Y',strtotime($expense->created_at)))}}</td>
                            <td>{{$expense->head_name}}</td>
                            <td style="text-align:right">{{converter::en2bn(number_format($expense->amount))}}</td>
                            <td>{{$expense->note}}</td>
                        </tr>
                        <?php $total+=$expense->amount;?>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" style="text-align:right">মোট:</th>
                            <th style="text-align:right">{{converter::en2bn(number_format($total))}}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
                @else
                <h5 style="text-align: center; color: red;">দুঃখিত! কোন তত্য খুজে পাওয়া যায়নি</h5>
                @endif
            </div>
        </div>
        <htmlpagefooter name="page-footer">
            <p style="font-family: sans-serif; text-align:right; font-size:13px;">Developed By <a href="http://innovationit.com.bd/" style="text-decoration:none">Innovation IT</a></p>
        </htmlpagefooter>
    </body>
</html>
