@extends("layouts.admin")
@section("title","পন্যের তালিকা")
@section("content")
<div class="container-fluid">
<style type="text/css">
    
    .list-group{

        max-height: 250px;
        margin-bottom: 10px;
        overflow:scroll;
        -webkit-overflow-scrolling: touch;
    }

    .qty .btn {
        background: #5a6268 none repeat scroll 0 0;
        border-radius: 2px !important;
        color: #fff;
        font-size: 23px;
        height: 29px;
        line-height: 15px;
        padding: 0;
        text-align: center !important;
        vertical-align: baseline;
        width: 27px;
    }
</style>
    <div class="row">

        <div class="col-12">
            <h4 class="header-title mb-3">পন্য বিক্রি</h4>

            <span id="validation_error"></span>
        </div>
        
        <div class="col-7">

            <div class="row">
                <div class="col-12 form">
                    
                    <div class="input-group">

                        <input class="form-control" id="product" name="product" placeholder="পন্য সার্চ করুন" autocomplete="off"/>
                        <span class="input-group-append">
                            <button type="button" id="" class="btn waves-effect waves-light btn-primary" onclick="sale_product_add()">সার্চ</button>
                        </span>
                        
                    </div>

                    <div id="product_list" class="overflow-auto" style="overflow: hidden;"></div>
                           
                        {{ csrf_field() }}

                    <span id="response_alert" style="margin-top: 5px;"></span>

                    <div class="row" style="margin-top: 50px;">
                        <div class="col-12">
                            <div class="card-box">
                                <div class="table-responsive">
                                    <table class="table cart_summary" id="myTable">
                                        <thead>
                                            <tr>
                                                <th>পন্য </th>
                                                <th>মূল্য </th>
                                                <th>পরিমান</th>
                                                <th> মোট </th>
                                                <th><i class="mdi mdi-delete-forever"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
        
                                        </tbody>

                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-5">
            <div>
                <div class="form-group">
                    <label for="date">তারিখ</label>
                    <input type="text" class="form-control datepicker" id="sale_date" name="sale_date" placeholder="">
                </div>
                <div class="form-group">
                    <label for="name">নাম</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="">
                </div>
                <div class="form-group">
                    <label for="mobile">মোবাইল</label>
                    <input type="text" class="form-control" name="customer_mobile" id="customer_mobile" placeholder="">
                    <span id="mobile_error" class="text-danger"></span>
                </div>
            </div>

            <div class="" style="margin-top: 50px;">
                <table class="table table-borderless">
                    <tr>
                        <td style="text-align: right">মোট:</td>
                        <td>৳ <span id="total"></span><input type="hidden" name="total_amount" id="total_amount"></td>
                    </tr>
                    <tr>
                        <td style="text-align: right">বকেয়া:</td>
                        <td id="due">৳ ০০</td>
                    </tr>
                    
                    <tr>
                        <td style="text-align: right">পেমেন্ট:</td>
                        <td>
                            <input type='text' class="form-control" name="pay_amount" id='pay_amount'>
                        </td>
                        
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: right;">
                            <button class="btn btn-warning" onclick="product_sale_save()">সাবমিট</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
       
    </div>
    
    <!-- end row -->
</div>

@endsection

@section('js')
<script src="{{ asset('public/admin/assets/js/pages/product.js') }}"></script>

<script>
$(document).ready(function(){

 $('#product').keyup(function(){

        var query = $(this).val();

        if(query != ''){

            var _token = $('input[name="_token"]').val();
            
            $.ajax({
                url:"{{ route('product_fetch') }}",
                method:"POST",
                data:{query:query, _token:_token},
                success:function(data){

                    $('#product_list').fadeIn();  
                    $('#product_list').html(data);
                }
            });
        }
    });

    $(document).on('click', 'li', function(){

        $('#product').val($(this).text());  
        $('#product_list').fadeOut();

    }); 



});
</script>
@endsection

{{-- <tr>
                                                <td>
                                                   <span id="product_name">pepsi</span> 
                                                </td>
        
                                                <td>
                                                    <span id="product_price">৳ ২৫</span> 
                                                </td>
        
                                                <td class="qty" width="130">
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <button id="minus_sign1" onclick="quantity_update('1', -1, '2')" disabled="disabled" class="btn btn-theme-round btn-number" type="button">-</button>
                                                        </span>
        
                                                        <input  name="product_quantity[]" onkeyup="quantity_update('1', this.value, '1')" id="quantity1" type="text" max="10" min="1" value="1" class="form-control border-form-control form-control-sm input-number" required="" style="margin-left: 3px; margin-right: 3px;">
        
                                                        <span class="input-group-btn">
                                                            <button onclick="quantity_update('1', 1, '2')" class="btn btn-theme-round btn-number" type="button">+</button>
                                                        </span>
                                                    </div>
        
                                                    <span style="color: red" id="order_error_msg1"></span>
        
                                                </td>
        
                                                <td width="100px">
                                                    ৳ <span id="total_price">
                                                        ২৫
                                                    </span>
                                                    <input type="hidden" name="total_product_price[]" id="totalProductPrice1" class="totalProductPrice" value="25">
                                                </td>
        
                                                <td class="action">
        
                                                    <input type="hidden" id="product_id1" name="product_id" value="1">
                                                    
                                                    <input type="hidden" id="rate1" name="rate" value="25">
                                                    
                                                    <input type="hidden" name="stockquentity" id="stockQuentity1" value="498">
        
                                                    <a class="btn btn-sm btn-danger" data-original-title="Remove" href="http://localhost/go_bazaar/product/remove/1" title="" data-placement="top" data-toggle="tooltip"><i class="mdi mdi-close-circle-outline"></i></a>
        
                                                </td>
                                            </tr> --}}



