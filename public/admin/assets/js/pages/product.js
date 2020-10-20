var url = $("meta[name = path]").attr("content");

$(function () {
  var table = $(".product_list_table").DataTable({
    scrollCollapse: true,
    autoWidth: false,
    responsive: true,
    serverSide: true,
    processing: true,

    ajax: {
      url: url + "/product/data",
      data: function (e) {
        (e.category_id = $("#filter_category").val() || 0),
          (e.sub_category_id = $("#filter_sub_category").val() || 0);
      },
    },

    columns: [
      {
        data: "DT_RowIndex",
        name: "DT_RowIndex",
      },

      {
        data: "id",
        name: "id",
      },

      {
        name: "picture",
        orderable: false,
        render: function (data, type, row) {
          let html = "";
          if (row.picture != null) {
            html =
              '<img src="public/upload/product/' +
              row.picture +
              '" style="height:50px; width:50px;" alt="' +
              row.name +
              '">';
          } else {
            html =
              '<img src="public/upload/product/default.jpg" style="height:50px; width:50px;" alt="' +
              row.name +
              '">';
          }
          return html;
        },
      },

      {
        data: "category",
        name: "category",
      },

      {
        data: "sub_category",
        name: "sub_category",
      },

      {
        data: "name",
        name: "name",
      },

      // {data: 'description', name: 'description'},

      {
        data: "created_at",
        name: "created_at",
      },

      {
        data: "action",
        name: "action",
        orderable: false,
        searchable: false,
      },
    ],
  });
});

$(document).on("click", ".FilterResult", function () {
  let parameter = {
    filter_category: "required",
    // "filter_sub_category" : "required",
  };
  let validate = validation(parameter);

  if (validate == false) {
    $(".product_list_table").DataTable().draw(true);
  } else {
    return false;
  }
});

$(document).on("click", ".AddProduct", function () {
  document.getElementById("AddProductForm").reset();

  //error reset
  $("#category_error").html("");
  $("#sub_category_error").html("");
  $("#unit_error").html("");
  $("#name_error").html("");

  $(".image_preview").addClass("d-none");
  $("#sub_category").html(
    "<option value=''>পন্যের সাব ক্যাটাগরি নির্বাচন করুণ</option>"
  );
  $("#id").val("");
  $(".product_pictures").html("");
  $("#description").html("");
  $("#AddProductModal").modal("toggle");
});

$(document).on("change", "#category", function () {
  var category_id = parseInt($(this).val()) || 0;
  document.getElementById("sub_category").innerHTML =
    "<option value=''>পন্যের সাব ক্যাটাগরি নির্বাচন করুণ</option>";
  if (category_id != 0) {
    getSubCategory(category_id);
  }
});

$(document).on("change", ".picture", function () {
  let pi_no = $(this).data("pi_no");
  $("#image_preview" + pi_no).addClass("d-none");
  readURL(this, "#image_preview" + pi_no);
  $("#image_preview" + pi_no).removeClass("d-none");
});

$(document).on("click", "#AddPictureInput", function () {
  let len = $(".picture").length;
  let pi_no = len + 1;

  let html = '<div class="row mt-1" id="picture_input' + pi_no + '">';
  html += '<div class="col-sm-6">';
  html +=
    '<input class="form-control-file picture" type="file" name="picture[]" data-pi_no="' +
    pi_no +
    '">';
  html += "</div>";
  html += '<div class="col-md-4">';
  html +=
    '<img src="" class="d-none image_preview" id="image_preview' +
    pi_no +
    '" style="height:50px; width:80px; border-radious: 5px;">';
  html += "</div>";
  html += '<div class="col-sm-2">';
  html +=
    '<button type="button" class="btn btn-sm btn-danger" id="RemovePictureInput" data-pi_no="' +
    pi_no +
    '"> X </button>';
  html += "</div>";
  html += "</div>";
  $(".picture_inputs").append(html);
});
$(document).on("click", "#RemovePictureInput", function () {
  let pi_no = $(this).data("pi_no");
  $("#picture_input" + pi_no).remove();
});
$(document).on("submit", ".AddProductForm", function (e) {
  e.preventDefault();
  var formData = new FormData(this);
  var parameter = {
    category: "required",
    sub_category: "required",
    name: "required",
  };
  var validate = validation(parameter);
  if (validate == false) {
    $("#submit_btn").html("আপনার পন্যটি জমা হচ্ছে....");
    $.ajax({
      url: url + "/product/store",
      type: "POST",
      data: new FormData(this),
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.status == "success") {
          $("#msg_div").addClass("alert-success");
        } else {
          $("#msg_div").addClass("alert-danger");
        }
        $("#submit_btn").html("জমা");
        $("#res_message").show();
        $("#res_message").html(response.msg);
        $("#msg_div").removeClass("d-none");

        document.getElementById("AddProductForm").reset();
        $(".image_preview").addClass("d-none");
        $(".product_pictures").html("");
        $(".picture_inputs").html("");
        $("#description").html("");
        $(".products-table").DataTable().draw(true);
        // $("#AddProductModal").modal("hide");
        setTimeout(function () {
          $("#res_message").hide();
          $("#msg_div").hide();
        }, 5000);
      },
    });
  } else {
    return false;
  }
});
$(document).on("click", ".productEdit", function () {
  //error reset
  $("#category_error").html("");
  $("#sub_category_error").html("");
  // $('#unit_error').html('');
  $("#name_error").html("");
  $(".image_preview").addClass("d-none");
  $(".picture").val("");

  let id = $(this).data("id");
  $.ajax({
    url: url + "/product/edit",
    data: {
      id: id,
    },
    success: function (res) {
      $("#id").val(res.id);
      $("#category").val(res.category_id);
      getSubCategory(res.category_id, res.sub_category_id);
      // $("#unit").val(res.unit_id);
      $("#name").val(res.name);
      $("#description").html(res.description);
      let pictures = res.picture;
      let image;
      if(pictures == null){
        images = [];
      }
      else{
        images = pictures.split("##");
      }
      images.pop();
      $(".product_pictures").html("");
      let i = 1;
      images.forEach(function (item) {
        let html = '<div class="col-sm-2" id="picture' + i + '">';
        html +=
          '<img src="' +
          url +
          "/public/upload/product/" +
          item +
          '" style="height:50px; width:80px; border-radious: 5px;">';
        html +=
          '<button type="button" class="btn btn-danger btn-sm mt-2 RemoveProductImage" data-product_id="' +
          res.id +
          '" data-image_name="' +
          item +
          '" data-div_id="' +
          i +
          '">Remove</button>';
        html += "</div>";
        $(".product_pictures").append(html);
        i++;
      });

      $("#AddProductModal").modal("toggle");
    },
  });
});

$(document).on("click", ".RemoveProductImage", function () {
  let image = $(this).data("image_name");
  let product = $(this).data("product_id");
  let div_id = $(this).data("div_id");

  $.ajax({
    url: url + "/product/image/delete",
    type: "POST",
    dataType: "JSON",
    data: {
      id: product,
      image: image,
    },
    success: function (responseText) {
      if (responseText.status == "success") {
        $("#picture" + div_id).remove();
      }
    },
  });
});
$(document).on("click", ".productDelete", function () {
  let id = $(this).data("id");
  Swal.fire({
    title: "আপনি কি নিশ্চিত?",
    text: "পন্যটি ডিলিট করতে কি আপনি নিশ্চিত?",
    type: "warning",
    showCancelButton: !0,
    confirmButtonColor: "#458bc4",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "হ্যা",
    cancelButtonText: "না",
  }).then(function (t) {
    if (t.value) {
      $.ajax({
        url: url + "/product/delete",
        type: "GET",
        dataType: "JSON",
        data: {
          id: id,
        },
        success: function (responseText) {
          Swal.fire("সফল!", responseText.msg, "success");
          $(".products-table").DataTable().draw(true);
        },
      });
    }
  });
});

function resetSpecialElement() {
  $("#image_preview").addClass("d-none");
  $("#id").val("");
  $("#description").html("");
  $("#sub_category").html(
    "<option value=''>পন্যের সাব ক্যাটাগরি নির্বাচন করুণ</option>"
  );
  return true;
}

$(document).on('click','.AddBulkProduct',function(){
  $("#AddBulkProductModal").modal('toggle');
});
$(document).on('submit','#AddBulkProductForm',function(){
  let csv = $("#AddBulkProductForm .product_csv_file").val() || null;
 
  if(csv == null){
      toastr.error("CSV ফাইল নির্বাচন করুন");
      return;
  }
  else{
    openloader();
    $.ajax({
      url: url + "/product/bulk/store",
      type: "POST",
      data: new FormData(this),
      processData: false,
      contentType: false,
      success: function (response) {
        closeloader();
        document.getElementById("AddProductForm").reset();
        $(".products-table").DataTable().draw(true);
        toastr.success(response.msg)
        $("#AddBulkProductModal").modal('toggle');
      },
    });
  }



});

//sale product add on list
function sale_product_add() {
  $("#myTable tbody").html("");

  $("#response_alert").fadeTo(2000, 500).slideDown(500);

  //get product id from product
  var product_id = $("#product").val().split("-");

  var id = product_id[1];

  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  $.ajax({
    type: "POST",
    url: url + "/sale_product_add",
    dataType: "JSON",
    data: {
      product_id: id,
    },
    success: function (response) {
      console.log(response);

      $exist_product_id = $("#product_id").val();

      if (response.status == "success") {
        $("#response_alert").html(
          '<div class="alert alert-success" id="success-alert"><button type="button" class="close" data-dismiss="alert">x</button><strong>' +
            response.status +
            " </strong> " +
            response.message +
            "</div>"
        );
      } else {
        $("#response_alert").html(
          '<div class="alert alert-danger" id="success-alert"><button type="button" class="close" data-dismiss="alert">x</button><strong>' +
            response.status +
            " </strong> " +
            response.message +
            "</div>"
        );
      }

      response.data.forEach(function (item) {
        var markup =
          "<tr id='table_row" +
          item.id +
          "'><td>" +
          item.product_name +
          "</td><td>" +
          item.rate +
          "</td> <td class='qty' width='130'><div class='input-group'><span class='input-group-btn'><button id='minus_sign" +
          item.id +
          "' onclick='quantity_update(-1, " +
          item.id +
          ", " +
          item.rate +
          ")' disabled='disabled' class='btn btn-theme-round btn-number' type='button'>-</button></span><input  name='product_quantity' onkeyup='quantity_update(this.value, " +
          item.id +
          ", " +
          item.rate +
          ", 3)' id='quantity" +
          item.id +
          "' type='text' max='10' min='1' value='" +
          item.quantity +
          "' class='form-control border-form-control form-control-sm input-number'  style='margin-left: 3px; margin-right: 3px;'><span class='input-group-btn'><button onclick='quantity_update( 1, " +
          item.id +
          ", " +
          item.rate +
          ")' class='btn btn-theme-round btn-number' type='button'>+</button></span></div></td><td><span id='product_rate" +
          item.id +
          "'>" +
          1 * item.rate +
          "</span></td><td><span id='DeleteButton' onclick='table_row_remove(" +
          item.id +
          ")' class='btn btn-sm btn-danger'><i class='mdi mdi-close-circle-outline'></i></span><input type='hidden' id='amount" +
          item.id +
          "' name='amount' value='" +
          item.rate +
          "' /><input type='hidden' id='product_id' name='product_id' value='" +
          item.id +
          "' /><input type='hidden' id='rate' name='rate' value='" +
          item.rate +
          "' /></td></tr>";

        $("#myTable tbody").append(markup);
      });

      var total = 0;

      //total value assign
      $("#myTable tbody")
        .find('input[name="amount"]')
        .each(function () {
          total += parseInt($(this).val());
        });

      $("#total").html(total);
      $("#total_amount").val(total);

      //alert remove
      setTimeout(function () {
        $("#response_alert").slideUp(500);
      }, 2000);
    },
  });
}

//product quantity update
function quantity_update(quantity, id, rate, ext = null) {
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

  $.ajax({
    url: url + "/getStockByProductId",
    type: "POST",
    dataType: "JSON",
    data: {
      product_id: id,
    },
    success: function (response) {
      current_stock = response.stock;
      if (total_qnty > current_stock) {
        $("#quantity" + id).val(old_qty);
        Swal.fire(
          "আউট অফ স্টক",
          "এই পন্যটি পর্যাপ্ত পরিমাণ স্টকে নেই",
          "error"
        );
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
            CartRender(response.data);
            Calculation(response.data);
          },
        });
      }
    },
  });
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
        CartRender(response.data);
        Calculation(response.data);
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
      CartRender(response.data);
      Calculation(response.data);
    },
  });
}

//product sale save
function product_sale_save() {
  var dates = new Date();

  var date_format =
    dates.getFullYear() +
    "-" +
    (dates.getMonth() <= 8
      ? "0" + (dates.getMonth() + 1)
      : dates.getMonth() + 1) +
    "-" +
    (dates.getDate() <= 8 ? "0" + dates.getDate() : dates.getDate());

  // console.log(date_format);

  $("#sale_date").val(date_format);

  // return;

  $("#validation_error").html("");
  $("#validation_error").slideDown(500);

  var sale_date = $("#sale_date").val();
  var customer_name = $("#customer_name").val();
  var customer_mobile = $("#customer_mobile").val();
  var total_amount = $("#total_amount").val();
  var pay_amount = $("#pay_amount").val();

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
  if (total_amount != pay_amount) {
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
      },
      success: function (response) {
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
            // window.location.href = url + "/order/details/" + response.order_id;
            window.open(url + "/order/details/" + response.order_id, "_blank");
          }
        });
      },
    });
  }

  //validation error hide
  setTimeout(function () {
    $("#validation_error").slideUp(500);
  }, 2000);
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

$(document).on("change", "#filter_category", function () {
  var category_id = parseInt($(this).val()) || 0;
  document.getElementById("filter_sub_category").innerHTML =
    "<option value=''>পন্যের সাব ক্যাটাগরি নির্বাচন করুণ</option>";
  if (category_id != 0) {
    getSubCategory(category_id, null, "filter_sub_category");
  }
});
