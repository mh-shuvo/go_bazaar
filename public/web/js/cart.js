var url = $('meta[name = path]').attr("content");
var csrf = $('mata[name = csrf-token]').attr("content");

//number convert
function number_convert(num) {

    var numbers = {
        0: '০',
        1: '১',
        2: '২',
        3: '৩',
        4: '৪',
        5: '৫',
        6: '৬',
        7: '৭',
        8: '৮',
        9: '৯'
    };

    var input = num.toString();

    var output = [];

    for (var i = 0; i < input.length; ++i) {
        if (numbers.hasOwnProperty(input[i])) {
            output.push(numbers[input[i]]);
        } else {
            output.push(input[i]);
        }
    }

    return output.join('');

}


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function addCart(product_id, call_from = 1, rowId = '') {


    //cart badge show
    $('small').addClass('cart-value');
    $('.cart-value').show();

    let target = $("#target").val();

    $.ajax({
        type: 'POST',
        url: target + '/add_cart_product',
        dataType: "JSON",
        data: { product_id: product_id },
        success: function (response) {

            // hide add_to_cart
            // show plus_minus_block
            $("#add_to_cart_btn" + rowId).hide();
            $("#plus_minus_block" + rowId).show();
            $("#quantity" + rowId).val('1');

            swal({
                title: "পণ্যটি কার্ট এ যোগ হয়েছে",
                // text: response.message,
                type: response.status,
                showCancelButton: false,
                showConfirmButton: true,
                confirmButtonText: 'ওকে',
                closeOnConfirm: true,
                allowEscapeKey: false
            }, function (isOk) {
                if (isOk) {
                    $("#cartSidebar").click();
                    $("#sideCartBtn").show();
                }
            });

            cartProductAdd(response.data);
        }
    });
}

function cartProductAdd(cartList) {
    let imgPath = $("#imgPath").val();
    var data = '';

    var sub_total = 0;
    var discount = 0;
    var net_amount = 0;
    var c_net_amount = 0;

    $('#cart_body').html(data);

    $.each(cartList, function (key, value) {
        var offer_rate = 0;
        var total_offer_price=0

        data += "<div class='cart-list-product'><a style='cursor:pointer' class='float-right remove-cart' onclick='CartProductRemove(" + value.id + ")'><i class='mdi mdi-close'></i></a> <img class='img-fluid' src='" + imgPath + value.picture + "'> <h5><a>" + value.product_name+ "</a></h5><h5><a> " + value.shop_name + " </a></h5><h6><a>" + value.supplier_address + " </a></h6><p class='offer-price mb-0'>";

        if(value.offer_id !=null){
            if(value.offer_type == 1){
                offer_rate = parseFloat(value.rate) - parseFloat(value.offer_amount);
            }
            else{
                offer_rate = (parseFloat(value.rate) - ((parseFloat(value.rate) * parseFloat(value.offer_amount))/100));
            }
             data+="৳ " + number_convert(offer_rate)+"<span class='regular-price'>৳"+number_convert(value.rate)+"</span>";
        }else{
            data+="৳ " + number_convert(value.rate);
            offer_rate = parseFloat(value.rate) ;
        }
        data+= " </p> </div>";

        sub_total += (parseFloat(value.rate) * parseFloat(value.quantity));
        net_amount+= (offer_rate * parseFloat(value.quantity));
        // console.log(sub_total,net_amount);
    });

    discount += sub_total - net_amount;

    c_net_amount = net_amount;

    $("#cart_value").html('');

    $("#cart_product_total_sidebar").html(number_convert(cartList.length));
    $("#cart_product_total").html(number_convert(cartList.length));

    $('#cart_body').html(data);

    $('#sub_total').html(number_convert(sub_total.toFixed(2)));
    $('#discount').html(number_convert(discount.toFixed(2)));
    $('#net_amount').html(number_convert(net_amount.toFixed(2)));
    $('#c_net_amount').html(number_convert(c_net_amount.toFixed(2)));

    if (sub_total > 0) {
        $("#cart_footer").attr("style", "display:block;");
    } else {
        $("#cart_footer").attr("style", "display:none;");
    }

}

function CartProductRemove(id) {
    let target = $("#target").val();
    var product_id = id;

    $.ajax({
        type: 'POST',
        url: target + '/remove_cart_product',
        dataType: "JSON",
        data: { product_id: product_id },
        success: function (response) {

            if (response.total == 0) {

                $('.cart-value').hide();

            } else {
                $('.cart-value').show();

            }

            cartProductAdd(response.data);
        }
    });
}

function quantity_minus(row_id) {

    var quantity = parseInt($('#quantity' + row_id).val());
    var rate = parseInt($('#rate' + row_id).val());
    var stockQuentity = parseInt($('#stockQuentity' + row_id).val());

    if (quantity > stockQuentity) {
        $("#order_error_msg" + row_id).html("Out of stock.");
        $('#quantity' + row_id).val('');

        return false;
    } else {
        $("#order_error_msg" + row_id).html("");
    }

    quantity -= 1;

    $('#quantity' + row_id).val(quantity);

    if (quantity == 1) {
        $("#minus_sign" + row_id).attr("disabled", "disabled");
    } else {

        $("#minus_sign" + row_id).removeAttr("disabled");

        var totalPrice = parseInt(quantity * rate);

        $('#totalPrice' + row_id).html(totalPrice.toFixed(2));
        $('#totalNetPrice' + row_id).html(totalPrice.toFixed(2));

        $('#totalProductPrice' + row_id).val(totalPrice.toFixed(2));

        totalSummation();

    }

};

function totalSummation() {

    var sum = 0;

    $(".totalProductPrice").each(function () {
        sum += parseInt($(this).val());
    });

    //english to bangla number convert
    sum = number_convert(sum.toFixed(2));

    $("#totalSum").html(sum);
    $("#totalAmount").html(sum);
    $("#total").val(sum);
    $("#netAmount").html(sum);


}

// quantity update
function quantity_update(row_id, qty, type) {
    if (type == 1) {   // onchange
        total_qty = qty
    } else {
        var total_qty = parseInt($('#quantity' + row_id).val());
        total_qty += parseInt(qty);
    }

    var rate = parseInt($('#rate' + row_id).val());
    var stockQuentity = parseInt($('#stockQuentity' + row_id).val());

    if (total_qty < 1) {
        total_qty = 1;
    }

    // console.log(total_qty);
    // console.log(rate);
    // console.log(stockQuentity);
    // return false;

    if (total_qty > stockQuentity) {
        $("#order_error_msg" + row_id).html("Out of stock.");
        // $('#quantity'+row_id).val('');

        return false;
    } else {
        $("#order_error_msg" + row_id).html("");
    }

    if (total_qty == 1) {
        $("#minus_sign" + row_id).attr("disabled", "disabled");
    } else {
        $("#minus_sign" + row_id).removeAttr("disabled");
    }

    $("#quantity" + row_id).val(total_qty);

    var rate = $("#rate" + row_id).val();
    var total_amount = (rate * total_qty).toFixed(2);

    $('#totalProductPrice' + row_id).val(total_amount);

    //english to bangla number convert
    total_amount = number_convert(total_amount);

    $('#totalPrice' + row_id).html(total_amount);
    $('#totalNetPrice' + row_id).html(total_amount);


    // set session data
    var product_id = $("#product_id" + row_id).val();
    let target = $("#target").val();

    $.ajax({
        url: target + "/update_cart",
        type: "POST",
        dataType: "JSON",
        data: { product_id: product_id, quantity: total_qty },
        success: function (response) {
            console.log(response);
        }
    });

    totalSummation();
}
function CartProductQtyUpdate(row_id, qty, type) {
    if (type == 1) {   // onchange
        total_qty = qty
    } else {
        var total_qty = parseInt($('#quantity' + row_id).val());
        total_qty += parseInt(qty);
    }

    if (total_qty < 1) {
        total_qty = 1;
    }

    if (total_qty == 1) {
        $("#minus_sign" + row_id).attr("disabled", "disabled");
    } else {
        $("#minus_sign" + row_id).removeAttr("disabled");
    }

    $("#quantity" + row_id).val(total_qty);


    // set session data
    var product_id = $("#product_id" + row_id).val();
    $.ajax({
        url: url + "/update_cart",
        type: "POST",
        dataType: "JSON",
        data: { product_id: product_id, quantity: total_qty },
        success: function (response) {
            $("#cartSidebar").click();
            $("#sideCartBtn").show();
            cartProductAdd(response.data);
        }
    });

}
//get union 
function get_location(parent_id, type, target_id) {

    $.ajax({

        url: url + "/product/get_location",
        type: "POST",
        dataType: "JSON",
        data: {
            parent_id: parent_id,
            type: type,
        },
        success: function (response) {

            if (response.status == 'success') {

                var list = "<option value=''>উপজেলা নির্বাচন করুন</option>";

                response.data.forEach(function (item) {

                    list += "<option value='" + item.id + "'>" + item.en_name + "</option>";

                });

                $("#" + target_id).html(list);

            } else {

                $("#" + target_id).html("<option value=''>Not Found</option>");
            }

        }

    });

}

$(document).on('click', '.Addwish', function () {

    let product_id = $(this).data('id');
    let client_id = $(this).data('client');
    let el = $(this);
    if (client_id == '0') {
        $("#authentication_modal").modal('toggle');
    }
    else {

        $.ajax({
            type: 'POST',
            url: url + '/customer/wish_list/store',
            dataType: "JSON",
            data: {
                product_id: product_id,
                client_id: client_id
            },
            success: function (response) {
                if (response.status_code == '200') {
                    toastr.success('পণ্যটি উইস লিষ্ট এ যোগ হয়েছে', 'Success');

                    el.removeClass('mdi-heart-outline');
                    el.removeClass('Addwish');
                    el.addClass('mdi-heart');
                    el.addClass('wish-selected');
                    el.addClass('removeWish2');

                    // swal({
                    //         title: "সফল",
                    //         text: "পণ্যটি উইস লিষ্ট এ যোগ হয়েছে",
                    //         type: response.status,
                    //         showCancelButton: false,
                    //         showConfirmButton: true,
                    //         confirmButtonText: 'ওকে',
                    //         closeOnConfirm: true,
                    //         allowEscapeKey: false
                    //     }, function(isOk){
                    //         if(isOk){
                    //             el.removeClass('mdi-heart-outline');
                    //             el.removeClass('Addwish');
                    //             el.addClass('mdi-heart');
                    //             el.addClass('wish-selected');
                    //             el.addClass('removeWish2');
                    //         }
                    // });
                }
            }
        });
    }
});

$(document).on('click', '.removeWish', function () {
    let id = $(this).data('id');
    let el = $(this);

    $.ajax({
        type: "POST",
        url: url + '/customer/wish_list/remove',
        dataType: "JSON",
        data: {
            'id': id
        },
        success: function (response) {
            if (response.status_code == 200) {
                toastr.success('পণ্যটি উইস লিষ্ট থেকে বাদ দেওয়া হয়েছে', 'Success');
                el.parent('td').parent('tr').remove();
            }
        }

    });
});

$(document).on('click', '.removeWish2', function () {
    let product_id = $(this).data('id');
    let client_id = $(this).data('client');
    let el = $(this);

    $.ajax({
        type: "POST",
        url: url + '/customer/wish_list/remove',
        dataType: "JSON",
        data: {
            'product_id': product_id,
            'client_id': client_id
        },
        success: function (response) {
            if (response.status_code == 200) {
                
                toastr.success('পণ্যটি উইস লিষ্ট থেকে বাদ দেওয়া হয়েছে', 'Success');

                el.removeClass('mdi-heart');
                el.removeClass('wish-selected');
                el.removeClass('removeWish2');

                el.addClass('mdi-heart-outline');
                el.addClass('Addwish');
            }
        }
    });
});