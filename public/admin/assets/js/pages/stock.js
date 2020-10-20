var url = $('meta[name = path]').attr("content");
$(document).on('click', '.AddStock', function () {
    document.getElementById("AddStockForm").reset()

    //error reset
    $('#category_error').html('');
    $('#sub_category_error').html('');
    $('#product_error').html('');
    $('#buying_price_error').html('');
    $('#saling_price_error').html('');
    $('#price_error').html('');
    $('#quantity_error').html('');

    $("#sub_category").html("<option value=''>পন্যের সাব ক্যাটাগরি নির্বাচন করুণ</option>");
    $("#product").html("<option value=''>পন্য নির্বাচন করুণ</option>");
    $("#rate_id").val('');
    $("#inventory_id").val('');
    $("#AddStockModal").modal('toggle');
});
$(document).on('click', '.PrintBarcode', function () {
    let product_id = $(this).data('id');
    let inventory_id = $(this).data('inventory_id');

    $("#PrintBarcodeModal #product_id").val(product_id)
    $("#PrintBarcodeModal #inventory_id").val(inventory_id)
    $("#PrintBarcodeModal").modal('toggle');

});

$(document).on('click', '.FilterResult', function () {

    // let parameter = {
    //     "filter_category": "required",
    //     "filter_sub_category" : "required",
    // }
    // let validate = validation(parameter);

    // if (validate == false) {
    //     $(".inventory-table").DataTable().draw(true);
    // }
    // else {
    //     return false;
    // }
    $(".inventory-table").DataTable().draw(true);
});
$(document).on('submit', '.AddStockForm', function (e) {
    e.preventDefault();
    var parameter = {
        'category': 'required',
        'sub_category': 'required',
        'product': 'required',
        'buying_price': 'required',
        'saling_price': 'required',
        'quantity': 'required'
    };
    var validate = validation(parameter);
    if (validate == false) {
        $('#submit_btn').html('আপনার স্টকটি জমা হচ্ছে....');
        $.ajax({
            url: url + '/stock/store',
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {

                $('#submit_btn').html('সাবমিট');
                if (response.status == 'success') {
                    Swal.fire({
                        title: response.msg,
                        text: "আপনি কি এই পন্যের বারকোড প্রিন্ট করতে চান?",
                        type: "warning",
                        showCancelButton: !0,
                        confirmButtonColor: "#458bc4",
                        cancelButtonColor: "#6c757d",
                        confirmButtonText: "হ্যা",
                        cancelButtonText: "না",
                    }).then(function (t) {
                        if (t.value) {
                            $("#AddStockModal").modal('toggle');
                            $("#PrintBarcodeModal #product_id").val(response.product_id)
                            $("#PrintBarcodeModal #inventory_id").val(response.inventory_id)
                            $("#PrintBarcodeModal").modal('toggle');
                        }
                    })
                }
                else {
                    Swal.fire("কোন কিছু ভূল হয়েছে", response.msg, "error")
                }

                document.getElementById("AddStockForm").reset();
                $(".inventory-table").DataTable().draw(true);

            }
        });
    }
    else {
        return false;
    }
});
$(document).on('click', '.StockEdit', function () {

    //error reset
    $('#category_error').html('');
    $('#sub_category_error').html('');
    $('#product_error').html('');
    $('#price_error').html('');
    $('#price_error').html('');
    $('#quantity_error').html('');

    let product_id = $(this).data('id');
    let rate_id = $(this).data('rate_id');
    let inventory_id = $(this).data('inventory_id');

    $.ajax({
        url: url + "/stock/edit",
        type: "POST",
        dataType: "JSON",
        data: {
            product_id: product_id,
            rate_id: rate_id,
            inventory_id: inventory_id
        },
        success: function (res) {
            $("#rate_id").val(rate_id);
            $("#inventory_id").val(inventory_id);
            $("#category").val(res.product.category_id);
            getSubCategory(res.product.category_id, res.product.sub_category_id);
            getProduct(res.product.category_id, res.product.sub_category_id, res.product.id);
            $("#saling_price").val(res.rate.rate);
            $("#buying_price").val(res.inventory.buying_price);
            $("#quantity").val(res.inventory.credit);
            $("#AddStockModal").modal("toggle");
        }
    });
});

$(document).on('click', '.WasteStockEdit', function () {

    //error reset
    $('#category_error').html('');
    $('#sub_category_error').html('');
    $('#product_error').html('');
    $('#quantity_error').html('');

    let product_id = $(this).data('id');
    let rate_id = $(this).data('rate_id');
    let inventory_id = $(this).data('inventory_id');

    $.ajax({
        url: url + "/waste/edit",
        type: "POST",
        dataType: "JSON",
        data: {
            product_id: product_id,
            inventory_id: inventory_id
        },
        success: function (res) {
            $("#inventory_id").val(inventory_id);
            $("#category").val(res.product.category_id);
            getSubCategory(res.product.category_id, res.product.sub_category_id);
            getProduct(res.product.category_id, res.product.sub_category_id, res.product.id);
            $("#quantity").val(res.inventory.debit);
            $("#AddWasteStockModal").modal("toggle");
        }
    });
});

$(document).on('click', '.StockDelete', function () {
    let id = $(this).data('inventory_id');
    Swal.fire({
        title: "আপনি কি নিশ্চিত?",
        text: "পন্যের স্টক বাতিল করতে কি আপনি নিশ্চিত?",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#458bc4",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "হ্যা",
        cancelButtonText: "না",
    }).then(function (t) {
        if (t.value) {
            $.ajax({
                url: url + "/stock/delete",
                type: "GET",
                dataType: "JSON",
                data: {
                    id: id
                },
                success: function (responseText) {
                    Swal.fire("সফল!", responseText.msg, "success")
                    $(".inventory-table").DataTable().draw(true);
                }
            });
        }
    })

});

$(document).on('click', '.WasteStockDelete', function () {
    let id = $(this).data('inventory_id');
    Swal.fire({
        title: "আপনি কি নিশ্চিত?",
        text: "পন্যের স্টক বাতিল করতে কি আপনি নিশ্চিত?",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#458bc4",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "হ্যা",
        cancelButtonText: "না",
    }).then(function (t) {
        if (t.value) {
            $.ajax({
                url: url + "/waste/delete",
                type: "GET",
                dataType: "JSON",
                data: {
                    id: id
                },
                success: function (responseText) {
                    Swal.fire("সফল!", responseText.msg, "success")
                    $(".waste-inventory-table").DataTable().draw(true);
                }
            });
        }
    })

});

$(document).on('click', '.AddWasteStock', function () {
    document.getElementById("AddWasteStockForm").reset()

    //error reset
    $('#category_error').html('');
    $('#sub_category_error').html('');
    $('#quantity_error').html('');

    $("#sub_category").html("<option value=''>পন্যের সাব ক্যাটাগরি নির্বাচন করুণ</option>");
    $("#product").html("<option value=''>পন্য নির্বাচন করুণ</option>");
    $("#inventory_id").val('');
    $("#AddWasteStockModal").modal('toggle');
});


$(document).on('submit', '.AddWasteStockForm', function (e) {
    e.preventDefault();
    var parameter = {
        'category': 'required',
        'sub_category': 'required',
        'product': 'required',
        'quantity': 'required'
    };
    var validate = validation(parameter);
    if (validate == false) {
        $('#submit_btn').html('আপনার স্টকটি জমা হচ্ছে....');
        $.ajax({
            url: url + '/waste/store',
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == 'success') {
                    $('#msg_div').addClass('alert-success');
                }
                else {
                    $('#msg_div').addClass('alert-danger');
                }
                $('#submit_btn').html('সাবমিট');
                $('#res_message').show();
                $('#res_message').html(response.msg);
                $('#msg_div').removeClass('d-none');

                document.getElementById("AddWasteStockForm").reset();
                $(".waste-inventory-table").DataTable().draw(true);
                setTimeout(function () {
                    $('#res_message').hide();
                    $('#msg_div').hide();
                }, 3000);
            }
        });
    }
    else {
        return false;
    }
});

$(document).on('click', '.FilterWasteResult', function () {

    let parameter = {
        "filter_category": "required",
        // "filter_sub_category" : "required",
    }
    let validate = validation(parameter);

    if (validate == false) {
        $(".waste-inventory-table").DataTable().draw(true);
    }
    else {
        return false;
    }


});


$(document).on('change', '#sub_category', function () {
    var category_id = parseInt($("#category").val()) || 0;
    var sub_category_id = parseInt($(this).val()) || 0;
    document.getElementById("product").innerHTML = "<option value=''>পন্য নির্বাচন করুণ</option>";
    if (category_id != 0 && sub_category_id != 0) {
        getProduct(category_id, sub_category_id);
    }
});

$(document).on('change', '#category', function () {
    var category_id = parseInt($(this).val()) || 0;
    document.getElementById("sub_category").innerHTML = "<option value=''>পন্যের সাব ক্যাটাগরি নির্বাচন করুণ</option>";
    if (category_id != 0) {
        getSubCategory(category_id);
    }
});

$(document).on('change', '#filter_category', function () {
    var category_id = parseInt($(this).val()) || 0;
    document.getElementById("filter_sub_category").innerHTML = "<option value=''>নির্বাচন করুন</option>";
    if (category_id != 0) {
        getSubCategory(category_id, null, 'filter_sub_category');
    }
});

$(document).on('change', '#filter_sub_category', function () {
    var category_id = parseInt($("#filter_category").val()) || 0;
    var sub_category_id = parseInt($(this).val()) || 0;
    document.getElementById("filter_product").innerHTML = "<option value=''>নির্বাচন করুন</option>";
    if (category_id != 0 && sub_category_id != 0) {
        getProduct(category_id, sub_category_id, null, "filter_product");
    }
});




$(document).on('click', '.AddOffer', function () {
    document.getElementById("AddOfferForm").reset();
    OfferFormErrorReset();
    $("#AddOfferModal").modal('toggle');
});


$(document).on('submit', '#AddOfferForm', function (e) {
    e.preventDefault();
    let category = $("#category").val();
    let sub_category = $("#sub_category").val();
    let product = $("#product").val();
    let offer_type = $("#offer_type").val();
    let offer_amount = $("#offer_amount").val();
    let offer_status = $("#offer_status").val();
    var parameter = {
        category: category,
        sub_category: sub_category,
        product: product,
        offer_type: offer_type,
        offer_amount: offer_amount,
        offer_status: offer_status
    };
    var validate = OfferFormValidation(parameter);
    if (validate == false) {
        $.ajax({
            url: url + '/supplier/offer/store',
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);

                if (response.errors) {
                    OfferFormErrorSet(response.errors);
                }

                if (response.status == 'success') {
                    toastr.success(response.message);
                    $("#AddOfferModal").modal('toggle');
                    $(".offer-table").DataTable().draw(true);
                }

                if (response.status == 'error') {
                    toastr.error(response.message);
                }

                document.getElementById("AddOfferForm").reset();
                OfferFormErrorReset();
                $(".offer-table").DataTable().draw(true);

            }
        });
    }
    else {
        return false;
    }
});

$(document).on('click', '.OfferEdit', function () {
    let row_index = $(this).data('row');
    let data = $(".offer-table").DataTable().row(row_index).data();
    $("#category").val(data.category_id);
    getSubCategory(data.category_id, data.sub_category_id);
    getProduct(data.category_id, data.sub_category_id, data.product_id);
    $('#offer_type').val(data.offer_type);
    $('#offer_amount').val(data.offer_amount);
    $('#offer_status').val(data.offer_status);
    $("#offer_id").val(data.id);
    $("#AddOfferModal").modal('toggle');
});

$(document).on('click', '.OfferDelete', function () {
    let row_index = $(this).data('row');
    let data = $(".offer-table").DataTable().row(row_index).data();
    Swal.fire({
        title: "আপনি কি নিশ্চিত?",
        text: "অফারটি বাতিল করতে কি আপনি নিশ্চিত?",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#458bc4",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "হ্যা",
        cancelButtonText: "না",
    }).then(function (t) {
        if (t.value) {
            $.ajax({
                url: url + "/supplier/offer/delete",
                type: "POST",
                dataType: "JSON",
                data: {
                    id: data.id
                },
                success: function (response) {
                    if (response.status == 'success') {
                        toastr.success(response.message);
                        $(".offer-table").DataTable().draw(true);
                    }

                    if (response.status == 'error') {
                        toastr.error(response.message);
                    }
                    $(".offer-table").DataTable().draw(true);
                }
            });
        }
    })
});

$(document).on('click', '.FilterOfferResult', function () {

    let parameter = {
        "filter_category": "required",
        // "filter_sub_category" : "required",
    }
    let validate = validation(parameter);

    if (validate == false) {
        $(".offer-table").DataTable().draw(true);
    }
    else {
        return false;
    }


});

//form validaton
function OfferFormValidation(data) {

    var error_status = false;

    if (data.category == '') {

        $('#category_error').html('পন্যের ক্যাটাগরি নির্বাচন করুণ');
        error_status = true;

    } else {

        $('#category_error').html('');

    }
    if (data.sub_category == '') {

        $('#sub_category_error').html('পন্যের সাব ক্যাটাগরি নির্বাচন করুণ');
        error_status = true;

    } else {

        $('#sub_category_error').html('');

    }
    if (data.product == '') {

        $('#product_error').html('পন্য নির্বাচন করুণ');
        error_status = true;

    } else {

        $('#product_error').html('');

    }
    if (data.offer_type == '') {

        $('#offer_type_error').html('অফার এর ধরন নির্বাচন করুণ');
        error_status = true;

    } else {

        $('#offer_type_error').html('');

    }
    if (data.offer_amount == '') {

        $('#offer_amount_error').html('অফার এর পরিমাণ দিন');
        error_status = true;

    } else {

        $('#offer_amount_error').html('');

    }

    if (data.offer_status == '') {

        $('#offer_status_error').html('অফার স্ট্যাটাস নির্বাচন করুণ');
        error_status = true;

    } else {

        $('#offer_status_error').html('');

    }

    return error_status;

}

function OfferFormErrorSet(data) {
    $('#category_error').html(data.category);
    $('#sub_category_error').html(data.sub_category);
    $('#product_error').html(data.product);
    $('#offer_type_error').html(data.offer_type);
    $('#offer_amount_error').html(data.offer_amount);
    $('#offer_status_error').html(data.offer_status);
}
function OfferFormErrorReset() {
    $('#category_error').html('');
    $('#sub_category_error').html('');
    $('#product_error').html('');
    $('#offer_type_error').html('');
    $('#offer_amount_error').html('');
    $('#offer_status_error').html('');
}