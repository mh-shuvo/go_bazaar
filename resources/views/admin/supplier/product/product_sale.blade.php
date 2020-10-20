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

    .required{
      color: red;
    }

</style>
    <div class="row">

        <div class="col-12">
            <h4 class="header-title mb-3">পন্য বিক্রি</h4>

            <span id="validation_error"></span>
        </div>
        
        <div class="col-7">

            <div class="row">
                <div class="col-12 form" id="custom-search-input">
                    
                    <div class="input-group">

                        <input class="form-control" id="product" name="product" placeholder="পন্য সার্চ করুন" autocomplete="off"/>
                        
                    </div>

                    <div class="input-group">
                      <span class="required" id="error_msg"></span>
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
                        <td style="text-align: right">ডিসকাউন্ট:</td>
                        <td>
                        <input type='text' class="form-control" name="discount_amount" id='discount_amount' onkeyup="calc_return_amt(total_amount.value, this.value, pay_amount.value)">
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="text-align: right">পেমেন্ট:</td>
                        <td>
                            <input type='text' class="form-control" name="pay_amount" id='pay_amount' onkeyup="calc_return_amt(total_amount.value, discount_amount.value, this.value)">
                        </td>
                        
                    </tr>
                    
                    <tr>
                        <td style="text-align: right">রিটার্ন:</td>
                        <td>
                            <input type='text' readonly class="form-control" name="return_amount" id='return_amount'>
                        </td>
                        
                    </tr>

                    <tr>
                        <td></td>
                        <td style="text-align: right;">
                            <button class="btn btn-warning" onclick="product_sale_save()">সাবমিট</button>
                            {{-- <button class="btn btn-warning" onclick="receipt()">Receipt</button> --}}
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

<script>
$(document).ready(function(){
  ProductFetch();
  let length = 0;
   var route = "{{ url('autocomplete') }}";
    $('#product').typeahead({
        source:  function (term, process) {
          if(!term.includes('$')){
            return $.get(route, { term: term }, function (data) {
                return process(data);
            });
          }
          else{
            let termArray = term.split('$');
            if(termArray.length == 3){
              return $.get(route, { term: termArray[1] }, function (data) {
                  length = data.length;
                  if(length == 1){
                     producInSession(data[0],1);
                      $('#product').val('');
                  }

                  if(data.length == 0){
                    $('#product').val('');
                    $("#error_msg").html('Out of stock');
                  } else {
                    $("#error_msg").html('');
                  }

                  return process(data);
              });
            }
          }
        },
         autoSelect: true,
         afterSelect:function(data){
            producInSession(data,1);
             $('#product').val('');
        },
        minLength:3,
        displayText:function(data){
            return data.name+'|'+data.buying_price+'|'+data.current_stock;
        },
    });

});



function ProductFetch(){
    $.ajax({
        url:"{{route('product_fetch')}}",
        type: "POST",
        dataType: "JSON",
        success:function(res){
            CartRender(Object.entries(res.data));
            Calculation(Object.entries(res.data));
        }
    });
}

function CartRender(products){
    $("#myTable tbody").html("");
        for (var i = 0; i <= products.length - 1; i++) {
          let item = products[i][1];
                var markup = "<tr id='table_row"+item.id+"'><td>" + item.product_name + "</td><td>" + item.rate + "</td> <td class='qty' width='130'><div class='input-group'><span class='input-group-btn'><button id='minus_sign"+item.id+"' onclick='pos_quantity_update(-1, "+item.id+", "+item.rate+")'";
                if(item.quantity<2){
                    markup+= "disabled='disabled'";
                }
                markup+=" class='btn btn-theme-round btn-number' type='button' data-qty ='"+item.quantity+"'>-</button></span><input name='product_quantity' onkeyup='pos_quantity_update(this.value, "+item.id+", "+item.rate+", 3)' id='quantity"+item.id+"' type='text' max='10' min='1' value='"+item.quantity+"' class='form-control border-form-control form-control-sm input-number'  style='margin-left: 3px; margin-right: 3px;' data-qty ='"+item.quantity+"'><span class='input-group-btn'><button onclick='pos_quantity_update( 1, "+item.id+", "+item.rate+")' class='btn btn-theme-round btn-number' type='button'  data-qty ='"+item.quantity+"'>+</button></span></div></td><td><span id='product_rate"+item.id+"' data-current_stock='"+item.current_stock+"' data-inventory_id='"+item.inventory_id+"'>" + (item.quantity * item.rate) + "</span></td><td><span id='DeleteButton' onclick='table_row_remove("+item.id+")' class='btn btn-sm btn-danger'><i class='mdi mdi-close-circle-outline'></i></span><input type='hidden' id='amount"+item.id+"' name='amount' value='"+item.rate+"' /><input type='hidden' id='product_id' name='product_id' value='"+item.id+"' /><input type='hidden' id='inventory_id' name='inventory_id' value='"+item.inventory_id+"' /><input type='hidden' id='rate' name='rate' value='"+item.rate+"' /><input type='hidden' id='buying_price' name='buying_price' value='"+item.buying_price+"' /><input type='hidden' id='current_stock' name='current_stock' value='"+item.current_stock+"' /></td></tr>";

                  $("#myTable tbody").append(markup);
                }

}

function producInSession(product,type=1){
    if(product.current_stock > 0 ){
         $.ajax({
            url:"{{route('sale_product_add')}}",
            type: "POST",
            dataType: "JSON",
            data: {product_id: product.product_id,type:type,inventory_id:product.id,buying_price:product.buying_price},
            success:function(res){
                $("#product").val('');
                CartRender(Object.entries(res.data));
                Calculation(Object.entries(res.data));
            }
        });
    }
    else{
        Swal.fire('আউট অফ স্টক','এই পন্যটি পর্যাপ্ত পরিমাণ স্টকে নেই','error'); 
    }
}

function Calculation(products){
  let total = 0;
     for (var i = 0; i <= products.length - 1; i++) {
        let item = products[i][1];
        total+=(item.rate*item.quantity);
    }
  $('#total').html(total);
  $('#total_amount').val(total);
}


function pos_quantity_update(quantity, id, rate, ext = null) {
  var input_value = parseInt($("#quantity" + id).val());
  var old_qty = $("#quantity" + id).data("qty");

  var param_val = parseInt(quantity);
  var total_qnty = 0;

  if (ext > 0) {
    if (param_val < input_value) {
      total_qnty = input_value - 1;
    } else {
      total_qnty = input_value + 1;
    }
  } else {
    total_qnty = parseInt(input_value + param_val);
  }
  let current_stock = $("#product_rate" + id).data("current_stock");

  if (total_qnty > current_stock) {
    $("#quantity" + id).val(old_qty);
    Swal.fire("আউট অফ স্টক", "এই পন্যটি পর্যাপ্ত পরিমাণ স্টকে নেই", "error");
  } else {
    $("#quantity" + id).val(total_qnty);

    if (total_qnty < 2) {
      $("#minus_sign" + id).prop("disabled", true);
    } else {
      $("#minus_sign" + id).prop("disabled", false);
    }

    $("#product_rate" + id).html(rate * total_qnty);
    $("#amount" + id).val(rate * total_qnty);

    var total = 0;

    //session sale product quantity update
    $.ajax({
      url: url + "/sale_product_list_update",
      type: "POST",
      dataType: "JSON",
      data: {
        product_id: id,
        quantity: total_qnty,
      },
      success: function (response) {
        CartRender(Object.entries(response.data));
        Calculation(Object.entries(response.data));
      },
    });
  }
}

//sale product row delete
function table_row_remove(id) {
  $("#table_row" + id).remove();

  var total = 0;

  //produt remove from product sale list
  $.ajax({
    type: "POST",
    url: url + "/remove_cart_product",
    dataType: "JSON",
    data: {
      product_id: id,
    },
    success: function (response) {
     CartRender(Object.entries(response.data));
     Calculation(Object.entries(response.data));
    },
  });
}

// calculate return amount
// implemented by Md. Shoriful Islam
function calc_return_amt(net_amt, discount_amt, payment_amt){
  
  if(parseInt(payment_amt) >= parseInt(net_amt)){
    net_amt = parseInt(net_amt);

    discount_amt = parseInt(discount_amt);

    if(!isNaN(discount_amt)){
      net_amt -= discount_amt;
    }

    var return_amount = payment_amt - net_amt;
    $("#return_amount").val(return_amount);

  } else {
    $("#return_amount").val('');
  }

}

//product sale save
function product_sale_save() {

  $("#validation_error").html("");
  $("#validation_error").slideDown(500);

  var sale_date = $("#sale_date").val();
  var customer_name = $("#customer_name").val();
  var customer_mobile = $("#customer_mobile").val();
  var total_amount = $("#total_amount").val();
  var pay_amount = $("#pay_amount").val() || 0;
  var discount_amount = $("#discount_amount").val() || 0;
  var net_amount = total_amount - discount_amount;

  var product_id_array = [];

  $("#myTable tbody")
    .find('input[name="product_id"]')
    .each(function () {
      product_id_array.push($(this).val());
    });

  //product list validation
  if (product_id_array.length <= 0) {
    $("#validation_error").html(
      '<div class="alert alert-danger" id="danger-alert"><button type="button" class="close" data-dismiss="alert">x</button><strong>Error !</strong>পন্য সিলেক্ট করুন।</div></div>'
    );

    setTimeout(function () {
      $("#validation_error").slideUp(500);
    }, 2000);

    return false;
  }

  //mobile number validation
  if (customer_mobile != "") {
    if (mobile_number_validate(customer_mobile)) {
      $("#mobile_error").html("");
    } else {
      $("#mobile_error").html("সঠিক নাম্বার দিন");

      return false;
    }
  }

  //pay amount validation
  if (pay_amount < net_amount) {
    $("#validation_error").html(
      '<div class="alert alert-danger" id="danger-alert"><button type="button" class="close" data-dismiss="alert">x</button><strong>Error !</strong>পেমেন্ট সঠিক নয়।</div></div>'
    );
  } else {
    $.ajax({
      type: "POST",
      url: url + "/internal_order_confirm",
      dataType: "JSON",
      data: {
        sale_date: sale_date,
        customer_name: customer_name,
        customer_mobile: customer_mobile,
        total_amount: total_amount,
        net_amount: net_amount,
        discount_amount: discount_amount,
      },
      success: function (response) {
        if(response.status == 'success'){
          //value reset
            $("#myTable tbody").html("");
            $("#product").val("");

            var dates = new Date();

            var date_format =
              dates.getFullYear() +
              "-" +
              (dates.getMonth() <= 8
                ? "0" + (dates.getMonth() + 1)
                : dates.getMonth() + 1) +
              "-" +
              dates.getDay();

            $("#sale_date").val(date_format);

            $("#total").html("");
            $("#pay_amount").val("");
            $("#return_amount").val("");
            $("#discount_amount").val("");
            $("#customer_mobile").val("");
            $("#customer_name").val("");

            // Swal.fire("ধন্যবাদ", response.message, "success");

            Swal.fire({
              title: "ধন্যবাদ",
              text: response.message + " আপনি কি এই অর্ডারটি প্রিন্ট করতে চান",
              type: "success",
              showCancelButton: !0,
              confirmButtonColor: "#458bc4",
              cancelButtonColor: "#6c757d",
              confirmButtonText: "হ্যা",
              cancelButtonText: "না",
            }).then(function (t) {
              if (t.value) {
                window.open(url + "/order/receipt/"+response.order_id, "_blank"); 
              }
            });
        }
        else{
          toastr.error(response.msg);
        }
      },
    });
  }

  //validation error hide
  setTimeout(function () {
    $("#validation_error").slideUp(500);
  }, 2000);
}

function receipt(){
window.open(url + "/order/receipt/20070011", "_blank"); 
}

//bd phone number validation
function mobile_number_validate(mobile) {
  var bd_rgx = /\+?(88)?0?1[56789][0-9]{8}\b/;

  if (mobile.match(bd_rgx)) {
    return true;
  } else {
    return false;
  }
}
</script>
@endsection