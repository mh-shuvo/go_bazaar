//for url
var url = $('meta[name = path]').attr("content");
var csrf = $('mata[name = csrf-token]').attr("content");

//bd phone number validation
function valid_mobile_check(mobile) {

    var bd_rgx = /\+?(88)?0?1[3256789][0-9]{8}\b/;

    if (mobile.match(bd_rgx)) {

        return true;

    } else {

        return false;
    }

}

//form validaton
function validation(data) {

    var error_status = false;

    if (data.upazila_id == '') {

        $('#upazilaId_error').html('উপজেলা সিলেক্ট করুন');
        error_status = true;

    } else {

        $('#upazilaId_error').html('');

    }

    if (data.union_id == '') {

        $('#unionId_error').html('ইউনিয়ন সিলেক্ট করুন');
        error_status = true;

    } else {

        $('#unionId_error').html('');

    }

    if (data.name == '') {

        $('#name_error').html('নাম দিন');
        error_status = true;

    } else {

        $('#name_error').html('');

    }

    if (data.mobile == '') {

        $('#mobile_error').html('মোবাইল দিন');
        error_status = true;

    } else {

        if (data.mobile.length == 11) {

            if (valid_mobile_check(data.mobile)) {

                $('#mobile_error').html('');

            } else {

                $('#mobile_error').html('মোবাইল নাম্বার সঠিক নয়');
                error_status = true;
            }

        } else {

            $('#mobile_error').html('মোবাইল নাম্বার সঠিক নয়');
            error_status = true;

        }



    }

    if (data.address == '') {
        $('#address_error').html('ঠিকানা দিন');
        error_status = true;
    } else {
        $('#address_error').html('');

    }

    if (data.username == '') {
        $('#username_error').html('ইউজারনেম দিন');
        error_status = true;
    } else {
        $('#username_error').html('');

    }

    if (typeof (data.password) != "undefined" && data.password == '') {
        $('#password_error').html('পাসওয়ার্ড দিন');
        error_status = true;
    } else {
        $('#password_error').html('');

    }

    if (data.nid == '') {

        $('#nid_error').html('জন্ম নিবন্ধন/জাতীয় পরিচয়পত্র নং দিন');
        error_status = true;

    } else {

        $('#nid_error').html('');


    }

    if (typeof (data.user_type) != "undefined" && data.user_type == '') {

        $('#user_type_error').html('পদবী নির্বাচন করুণ.');
        error_status = true;

    } else {

        $('#user_type_error').html('');


    }

    return error_status;

}


//add locations
function add_location() {

    //show save button
    $('#location_save_button').show();
    //hide update button
    $('#location_update_button').hide();

    $('#name').val('');
    $('#name_error').html('');

    //call get location function
    // get_location('NULL', 1, 'upazila');

    $('#district').prop('selectedIndex', 0);

    $('#location_modal').modal('show');

}

//location store
function location_store() {

    var name = $('#name').val();
    var district_id = $('#district').val();

    var error_status = false;

    if (name == '') {

        $("#name_error").html('নাম প্রদান করুন');
        error_status = true;
    } else {

        $("#name_error").html('');
        error_status = false;
    }

    if (error_status == true) {

        return false;

    } else {

        $.ajaxSetup({

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({

            url: url + "/admin/location_store",
            type: "POST",
            dataType: "JSON",
            data: {
                name: name,
                district_id: district_id,
            },
            success: function (response) {

                //if laravel validation error
                if (response.errors) {

                    if (response.errors.name) {
                        $('#name_error').html(response.errors.name[0]);
                    }

                }

                //if data exist or success
                if (response.status == 'error' || response.status == 'success') {

                    var text = (response.status == 'success') ? "ধন্যবাদ" : "দুঃখিত";

                    $("#location_modal").modal('hide');

                    Swal.fire(
                        text,
                        response.message,
                        'success'
                    )

                }

                $(".location_table").DataTable().draw(true);
            }

        });
    }

}

//edit location
function location_edit(row_index) {

    //error reset
    $('#name_error').html('');

    var row_data = $(".location_table").DataTable().row(row_index).data();

    //hide save button
    $('#location_save_button').hide();
    //show update button
    $('#location_update_button').show();

    //call get location function
    get_location('NULL', 2, 'upazila');

    setTimeout(function () {

        $('#row_id').val(row_data.id);

        if (row_data.type == 2) {

            $('#district').prop('selectedIndex', 0);
            $('#name').val(row_data.district_name);

        } else {

            $('#district').val(row_data.parent_id);
            $('#name').val(row_data.upazila_name);
        }
    }, 1000);


    $('#location_modal').modal('show');

}

//location update
function location_update() {

    var district_id = $('#district').val() || 0;
    var name = $('#name').val();
    var row_id = $('#row_id').val();

    var error_status = false;

    if (name == '') {

        $("#name_error").html('নাম প্রদান করুন');
        error_status = true;
    } else {

        $("#name_error").html('');
        error_status = false;
    }

    if (error_status == true) {

        return false;

    } else {

        $.ajaxSetup({

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({

            url: url + "/admin/location_update",
            type: "POST",
            dataType: "JSON",
            data: {
                row_id: row_id,
                name: name,
                district_id: district_id,
            },
            success: function (response) {

                //if laravel validation error
                if (response.errors) {

                    if (response.errors.name) {
                        $('#name_error').html(response.errors.name[0]);
                    }

                }

                //if data exist or success
                if (response.status == 'error' || response.status == 'success') {

                    var text = (response.status == 'success') ? "ধন্যবাদ" : "দুঃখিত";

                    $("#location_modal").modal('hide');

                    Swal.fire(
                        text,
                        response.message,
                        'success'
                    )

                }

                $(".location_table").DataTable().draw(true);

            }
        });
    }
}

//location delete
function location_delete(row_index) {

    var row_data = $(".location_table").DataTable().row(row_index).data();

    Swal.fire({
        type: 'warning',
        title: 'আপনি কি ডিলিট করতে চান ?',
        // text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'হ্যাঁ',
        cancelButtonText: 'না',
    }).then((result) => {
        if (result.value) {

            $.ajaxSetup({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: url + '/admin/location_delete',
                type: "POST",
                dataType: "JSON",
                data: {
                    id: row_data.id,

                },
                success: function (response) {

                    $(".location_table").DataTable().draw(true);

                    var text = (response.status == 'success') ? "ধন্যবাদ!" : "দুঃখিত!";

                    Swal.fire(
                        text,
                        response.message,
                        'success'
                    )
                }
            });
        }
    })

}

//gat all category
function get_all_category() {

    $.ajaxSetup({

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({

        url: url + "/admin/categores",
        type: "GET",
        dataType: "JSON",
        success: function (response) {

            let category_list = "<option value=''>সিলেক্ট</option>"

            response.forEach(function (item) {

                category_list += "<option value='" + item.id + "'>" + item.name + "</option>";
            })

            $("#category").html(category_list);
        }
    });
}

//add category
function add_category() {
    //show save button
    $('#category_save_button').show();
    //hide update button
    $('#category_update_button').hide();

    $('#name').val('');
    $('#sorting').val('');
    $("#is_show").prop("checked", false);
    $("#is_feature").prop("checked", false);
    $('#name_error').html('');
    $("#row_id").val('');

    //call get category function
    get_all_category();

    $('#category').prop('selectedIndex', 0);

    $('#category_modal').modal('show');

}

//category store
function category_store() {

    var name = $('#name').val();
    var category = $('#category').val();
    var sorting = $('#sorting').val();
    var is_show = ($('#is_show').is(':checked')) ? 1 : 0;
    var is_feature = ($('#is_feature').is(':checked')) ? 1 : 0;

    var error_status = false;

    if (name == '') {

        $("#name_error").html('নাম প্রদান করুন');
        error_status = true;
    } else {

        $("#name_error").html('');
        error_status = false;
    }

    if (error_status == true) {

        return false;

    } else {

        $.ajaxSetup({

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({

            url: url + "/admin/category_store",
            type: "POST",
            dataType: "JSON",
            data: new FormData("#category_form"),
            success: function (response) {

                //if laravel validation error
                if (response.errors) {

                    if (response.errors.name) {
                        $('#name_error').html(response.errors.name[0]);
                    }

                }

                //if data exist or success
                if (response.status == 'error' || response.status == 'success') {

                    var text = (response.status == 'success') ? "ধন্যবাদ" : "দুঃখিত";

                    $("#category_modal").modal('hide');

                    Swal.fire(
                        text,
                        response.message,
                        'success'
                    )

                }

                $(".category_table").DataTable().draw(true);
            }

        });
    }

}

$(document).on('submit', '#category_form', function (e) {
    e.preventDefault();
    var name = $('#name').val();

    var error_status = false;

    if (name == '') {

        $("#name_error").html('নাম প্রদান করুন');
        error_status = true;
    } else {

        $("#name_error").html('');
        error_status = false;
    }

    if (error_status == true) {

        return false;

    } else {

        $.ajaxSetup({

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({

            url: url + "/admin/category_store",
            type: "POST",
            dataType: "JSON",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {

                //if laravel validation error
                if (response.errors) {

                    if (response.errors.name) {
                        $('#name_error').html(response.errors.name[0]);
                    }

                }

                //if data exist or success
                if (response.status == 'error' || response.status == 'success') {

                    var text = (response.status == 'success') ? "ধন্যবাদ" : "দুঃখিত";

                    $("#category_modal").modal('hide');

                    Swal.fire(
                        text,
                        response.message,
                        'success'
                    )

                }

                $(".category_table").DataTable().draw(true);
            }

        });
    }

});

//edit category
function category_edit(row_index) {

    //error reset
    $('#name_error').html('');
    $("#row_id").val('');

    // $('#category').prop('selectedIndex',0);
    var row_data = $(".category_table").DataTable().row(row_index).data();

    //call get category function
    get_all_category();

    setTimeout(function () {

        $('#row_id').val(row_data.id);

        if (row_data.type == 1) {

            $('#category').prop('selectedIndex', 0);
            $('#name').val(row_data.category);

        } else {

            $('#category').val(row_data.parent_id);
            $('#name').val(row_data.sub_category);
        }

        $('#sorting').val(row_data.sorting);

        (row_data.is_show == 1) ? $("#is_show").prop("checked", true) : $("#is_show").prop("checked", false);

        (row_data.is_feature == 1) ? $("#is_feature").prop("checked", true) : $("#is_feature").prop("checked", false);

    }, 1000);


    $('#category_modal').modal('show');

}

//user category_update
function category_update() {

    var category = $('#category').val();
    var name = $('#name').val();
    var sorting = $('#sorting').val();
    var is_show = ($('#is_show').is(':checked')) ? 1 : 0;
    var is_feature = ($('#is_feature').is(':checked')) ? 1 : 0;
    var row_id = $('#row_id').val();

    var error_status = false;

    if (name == '') {

        $("#name_error").html('নাম প্রদান করুন');
        error_status = true;
    } else {

        $("#name_error").html('');
        error_status = false;
    }

    if (error_status == true) {

        return false;

    } else {

        $.ajaxSetup({

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({

            url: url + "/admin/category_update",
            type: "POST",
            dataType: "JSON",
            data: {
                row_id: row_id,
                name: name,
                category: category,
                sorting: sorting,
                is_show: is_show,
                is_feature: is_feature,
            },
            success: function (response) {

                //if laravel validation error
                if (response.errors) {

                    if (response.errors.name) {
                        $('#name_error').html(response.errors.name[0]);
                    }

                }

                //if data exist or success
                if (response.status == 'error' || response.status == 'success') {

                    var text = (response.status == 'success') ? "ধন্যবাদ" : "দুঃখিত";

                    $("#category_modal").modal('hide');

                    Swal.fire(
                        text,
                        response.message,
                        'success'
                    )

                }

                $(".category_table").DataTable().draw(true);

            }
        });
    }
}

//this is for category delete
function category_delete(row_index) {
    var row_data = $(".data-table").DataTable().row(row_index).data();

    Swal.fire({
        type: 'warning',
        title: 'আপনি কি ডিলিট করতে চান ?',
        // text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'হ্যাঁ',
        cancelButtonText: 'না',
    }).then((result) => {
        if (result.value) {

            $.ajaxSetup({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: url + '/admin/category_delete',
                type: "POST",
                dataType: "JSON",
                data: {
                    id: row_data.id,

                },
                success: function (response) {

                    $(".category_table").DataTable().draw(true);

                    var text = (response.status == 'success') ? "ধন্যবাদ!" : "দুঃখিত!";

                    Swal.fire(
                        text,
                        response.message,
                        'success'
                    )
                }
            });
        }
    })

}

//supplier type add modal
function add_supplier_type() {

    //show save button
    $('#supplier_type_save_button').show();
    //hide update button
    $('#supplier_type_update_button').hide();

    $('#name').val('');
    $('#name_error').html('');

    $('#supplier_type_modal').modal('show');

}

//supplier type store
function supplier_type_store() {

    var name = $('#name').val();

    $.ajaxSetup({

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({

        url: url + "/admin/supplier_type_store",
        type: "POST",
        dataType: "JSON",
        data: {
            name: name,
        },
        success: function (response) {

            //if laravel validation error
            if (response.errors) {

                if (response.errors.name) {
                    $('#name_error').html(response.errors.name[0]);
                }

            }

            //if data exist or success
            if (response.status == 'error' || response.status == 'success') {

                var text = (response.status == 'success') ? "ধন্যবাদ" : "দুঃখিত";

                $("#supplier_type_modal").modal('hide');

                Swal.fire(
                    text,
                    response.message,
                    'success'
                )

            }

            if (response.status == 'success') {

                $(".supplier_type_table").DataTable().draw(true);
            }
        }

    });
}

//edit supplier type
function supplier_type_edit(row_index) {

    var row_data = $(".supplier_type_table").DataTable().row(row_index).data();

    //hide save button
    $('#supplier_type_save_button').hide();
    //show update button
    $('#supplier_type_update_button').show();

    $('#name').val(row_data.name);
    $('#row_id').val(row_data.id);
    $('#name_error').html('');

    $('#supplier_type_modal').modal('show');

}

//supplier type update
function supplier_type_update() {

    var name = $('#name').val();
    var row_id = $('#row_id').val();

    $.ajaxSetup({

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({

        url: url + "/admin/supplier_type_update",
        type: "POST",
        dataType: "JSON",
        data: {
            row_id: row_id,
            name: name,
        },
        success: function (response) {

            //if laravel validation error
            if (response.errors) {

                if (response.errors.name) {
                    $('#name_error').html(response.errors.name[0]);
                }

            }

            //if data exist or success
            if (response.status == 'error' || response.status == 'success') {

                var text = (response.status == 'success') ? "ধন্যবাদ" : "দুঃখিত";

                $("#supplier_type_modal").modal('hide');

                Swal.fire(
                    text,
                    response.message,
                    'success'
                )

            }

            $(".supplier_type_table").DataTable().draw(true);
        }


    });
}

//this is for supplier type delete
function supplier_type_delete(row_index) {

    var row_data = $(".supplier_type_table").DataTable().row(row_index).data();

    Swal.fire({
        type: 'warning',
        title: 'আপনি কি ডিলিট করতে চান ?',
        // text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'হ্যাঁ',
        cancelButtonText: 'না',
    }).then((result) => {
        if (result.value) {

            $.ajaxSetup({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: url + '/admin/supplier_type_delete',
                type: "POST",
                dataType: "JSON",
                data: {
                    id: row_data.id,

                },
                success: function (response) {

                    $(".supplier_type_table").DataTable().draw(true);

                    var text = (response.status == 'success') ? "ধন্যবাদ!" : "দুঃখিত!";

                    Swal.fire(
                        text,
                        response.message,
                        'success'
                    )
                }
            });
        }
    })

}

//this is for supplier delete
function supplier_delete(row_index) {

    var row_data = $(".supplier_table").DataTable().row(row_index).data();

    Swal.fire({
        type: 'warning',
        title: 'আপনি কি ডিলিট করতে চান ?',
        // text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'হ্যাঁ',
        cancelButtonText: 'না',
    }).then((result) => {
        if (result.value) {

            $.ajaxSetup({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: url + '/admin/supplier_delete',
                type: "POST",
                dataType: "JSON",
                data: {
                    id: row_data.id,
                    user_id: row_data.user_id,

                },
                success: function (response) {

                    $(".supplier_table").DataTable().draw(true);

                    var text = (response.status == 'success') ? "ধন্যবাদ!" : "দুঃখিত!";

                    Swal.fire(
                        text,
                        response.message,
                        'success'
                    )
                }
            });
        }
    })

}

//deliveryman add modal
function add_deliveryman() {

    //show save button
    $('#deliveryman_save_button').show();
    //hide update button
    $('#deliveryman_update_button').hide();

    //value reset
    $('#upazilaId').prop('selectedIndex', 0);
    $('#districtId').prop('selectedIndex', 0);
    $('#name').val('');
    $('#mobile').val('');
    $('#email').val('');
    $('#nid').val('');
    $('#address').val('');
    $('#userName').val('');
    $('#passWord').val('');

    //error reset
    $('#upazilaId_error').html('');
    $('#districtId_error').html('');
    $('#name_error').html('');
    $('#mobile_error').html('');
    $('#address_error').html('');
    $('#username_error').html('');
    $('#password_error').html('');

    $('#deliveryman_modal').modal('show');

}

//deliveryman store
function deliveryman_store() {

    var upazila_id = $('#upazilaId').val();
    var district_id = $('#districtId').val();
    var name = $('#name').val();
    var mobile = $('#mobile').val();
    var email = $('#email').val();
    var nid = $('#nid').val();
    var address = $('#address').val();
    var username = $('#userName').val();
    var password = $('#passWord').val();

    //validation array
    var validation_data = {
        // upazila_id:upazila_id,
        // union_id:union_id,
        name: name,
        nid: nid,
        mobile: mobile,
        address: address,
        username: username,
        password: password,

    };

    console.log(validation_data);


    var error_status = validation(validation_data);

    if (error_status == false) {

        $.ajaxSetup({

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({

            url: url + "/supplier/deliveryman_store",
            type: "POST",
            dataType: "JSON",
            data: {
                upazila_id: upazila_id,
                district_id: district_id,
                name: name,
                mobile: mobile,
                email: email,
                nid: nid,
                address: address,
                username: username,
                password: password,
            },
            success: function (response) {

                console.log(response);

                //if laravel validation error
                if (response.errors) {

                    // if(response.errors.upazila_id){
                    //     $( '#upazilaId_error' ).html( response.errors.upazila_id[0] );
                    // }else{
                    //     $( '#up' ).html('');
                    // }

                    // if(response.errors.union_id){
                    //     $( '#unionId_error' ).html( response.errors.union_id[0] );
                    // }else{
                    //     $( '#unionId_error' ).html('');
                    // }

                    if (response.errors.name) {
                        $('#name_error').html(response.errors.name[0]);
                    } else {
                        $('#name_error').html('');
                    }

                    if (response.errors.mobile) {
                        $('#mobile_error').html(response.errors.mobile[0]);
                    } else {
                        $('#mobile_error').html('');
                    }

                    if (response.errors.address) {
                        $('#address_error').html(response.errors.address[0]);
                    } else {
                        $('#address_error').html('');
                    }

                    if (response.errors.username) {
                        $('#username_error').html(response.errors.username[0]);
                    } else {
                        $('#username_error').html('');
                    }

                    if (response.errors.password) {
                        $('#password_error').html(response.errors.password[0]);
                    } else {
                        $('#password_error').html('');
                    }

                }

                //if data exist or success
                if (response.status == 'error' || response.status == 'success') {

                    var text = (response.status == 'success') ? "ধন্যবাদ" : "দুঃখিত";

                    $("#deliveryman_modal").modal('hide');

                    Swal.fire(
                        text,
                        response.message,
                        'success'
                    )

                }

                $(".deliveryman_table").DataTable().draw(true);
            }

        });
    }
}

//edit deliveryman
function deliveryman_edit(row_index) {

    var row_data = $(".deliveryman_table").DataTable().row(row_index).data();

    // console.log(row_data);

    //hide save button
    $('#deliveryman_save_button').hide();
    //show update button
    $('#deliveryman_update_button').show();

    //row id
    $('#row_id').val(row_data.id);
    //user id
    $('#user_id').val(row_data.user_id);

    //value assign
    $('#districtId').val(row_data.district_id);

    //get all union for this upazila
    get_location(row_data.district_id, 3, 'upazilaId');

    //after load union list then slected union
    setTimeout(function () {
        $('#upazilaId').val(row_data.upazila_id);
    }, 1500);

    $('#name').val(row_data.name);
    $('#mobile').val(row_data.mobile);
    $('#email').val(row_data.email);
    $('#nid').val(row_data.nid);
    $('#address').val(row_data.address);
    $('#userName').val(row_data.username);
    $('#passWord').val('');

    //error reset
    $('#upazilaId_error').html('');
    $('#districtId_error').html('');
    $('#name_error').html('');
    $('#mobile_error').html('');
    $('#address_error').html('');
    $('#username_error').html('');
    $('#password_error').html('');

    $('#deliveryman_modal').modal('show');

}

//deliveryman type update
function deliveryman_update() {

    var upazila_id = $('#upazilaId').val();
    var district_id = $('#districtId').val();
    var name = $('#name').val();
    var mobile = $('#mobile').val();
    var email = $('#email').val();
    var nid = $('#nid').val();
    var address = $('#address').val();
    var username = $('#userName').val();
    var password = $('#passWord').val();

    var row_id = $('#row_id').val();
    var user_id = $('#user_id').val();

    //validation array
    var validation_data = {
        // upazila_id:upazila_id,
        // union_id:union_id,
        name: name,
        mobile: mobile,
        address: address,
        username: username,
    };

    var error_status = validation(validation_data);

    if (error_status == false) {

        $.ajax({

            url: url + "/supplier/deliveryman_update",
            type: "POST",
            dataType: "JSON",
            data: {
                upazila_id: upazila_id,
                district_id: district_id,
                name: name,
                mobile: mobile,
                email: email,
                nid: nid,
                address: address,
                username: username,
                password: password,
                row_id: row_id,
                user_id: user_id,
            },
            success: function (response) {

                //if laravel validation error
                if (response.errors) {

                    if (response.errors.name) {
                        $('#name_error').html(response.errors.name[0]);
                    } else {
                        $('#name_error').html('');
                    }

                    if (response.errors.mobile) {
                        $('#mobile_error').html(response.errors.mobile[0]);
                    } else {
                        $('#mobile_error').html('');
                    }

                    if (response.errors.address) {
                        $('#address_error').html(response.errors.address[0]);
                    } else {
                        $('#address_error').html('');
                    }

                    if (response.errors.username) {
                        $('#username_error').html(response.errors.username[0]);
                    } else {
                        $('#username_error').html('');
                    }

                    if (response.errors.password) {
                        $('#password_error').html(response.errors.password[0]);
                    } else {
                        $('#password_error').html('');
                    }

                }

                //if data exist or success
                if (response.status == 'error' || response.status == 'success') {

                    var text = (response.status == 'success') ? "ধন্যবাদ" : "দুঃখিত";

                    $("#deliveryman_modal").modal('hide');

                    Swal.fire(
                        text,
                        response.message,
                        'success'
                    )

                }

                $(".deliveryman_table").DataTable().draw(true);
            }


        });
    }
}

//this is for deliveryman delete
function deliveryman_delete(row_index) {

    var row_data = $(".deliveryman_table").DataTable().row(row_index).data();

    Swal.fire({
        type: 'warning',
        title: 'আপনি কি ডিলিট করতে চান ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'হ্যাঁ',
        cancelButtonText: 'না',
    }).then((result) => {
        if (result.value) {

            $.ajaxSetup({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: url + '/supplier/deliveryman_delete',
                type: "POST",
                dataType: "JSON",
                data: {
                    id: row_data.id,
                    user_id: row_data.user_id,

                },
                success: function (response) {

                    $(".deliveryman_table").DataTable().draw(true);

                    var text = (response.status == 'success') ? "ধন্যবাদ!" : "দুঃখিত!";

                    Swal.fire(
                        text,
                        response.message,
                        'success'
                    )
                }
            });
        }
    })

}


$(document).on('click', '.AddEmployee', function () {
    $("#AddEmployeeForm")[0].reset();
    $("#AddEmployeeModal").modal('toggle');
});

$(document).on("submit", "#AddEmployeeForm", function (e) {
    e.preventDefault();
    var upazila_id = $('#upazilaId').val();
    var district_id = $('#districtId').val();
    var name = $('#name').val();
    var mobile = $('#mobile').val();
    var email = $('#email').val();
    var nid = $('#nid').val();
    var role_id = $('#role_id').val();
    var address = $('#address').val();
    var username = $('#userName').val();
    var password = $('#passWord').val();

    //validation array
    var validation_data = {
        upazila_id: upazila_id,
        district_id: district_id,
        name: name,
        nid: nid,
        role_id: role_id,
        mobile: mobile,
        address: address,
        username: username,
        // password:password,

    };

    var error_status = validation(validation_data);

    if (error_status == false) {
        $.ajax({
            url: url + "/supplier/employe/store",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {
                if (typeof (response.errors) != 'undefined' && response.errors.name) {
                    $('#name_error').html(response.errors.name[0]);
                } else {
                    $('#name_error').html('');
                }

                if (typeof (response.errors) != 'undefined' && response.errors.mobile) {
                    $('#mobile_error').html(response.errors.mobile[0]);
                } else {
                    $('#mobile_error').html('');
                }

                if (typeof (response.errors) != 'undefined' && response.errors.address) {
                    $('#address_error').html(response.errors.address[0]);
                } else {
                    $('#address_error').html('');
                }

                if (typeof (response.errors) != 'undefined' && response.errors.username) {
                    $('#username_error').html(response.errors.username[0]);
                } else {
                    $('#username_error').html('');
                }

                if (typeof (response.errors) != 'undefined' && response.errors.password) {
                    $('#password_error').html(response.errors.password[0]);
                } else {
                    $('#password_error').html('');
                }

                if (typeof (response.errors) != 'undefined' && response.errors.user_type) {
                    $('#user_type_error').html(response.errors.user_type[0]);
                } else {
                    $('#user_type_error').html('');
                }

                //if data exist or success
                if (response.status == 'error' || response.status == 'success') {

                    var text = (response.status == 'success') ? "ধন্যবাদ" : "দুঃখিত";

                    $("#AddEmployeeModal").modal('toggle');

                    Swal.fire(
                        text,
                        response.message,
                        'success'
                    )
                    $(".users_table").DataTable().draw(true);

                }
            }
        });
    }
});

$(document).on('click', '.EditUser', function () {
    let row_index = $(this).data('row');
    var row_data = $(".users_table").DataTable().row(row_index).data();

    // console.log(row_data);

    //row id
    $('#row_id').val(row_data.id);
    //user id
    $('#user_id').val(row_data.user_id);

    //value assign
    $('#districtId').val(row_data.district_id);

    //get all union for this upazila
    get_location(row_data.district_id, 3, 'upazilaId');

    //after load union list then slected union
    setTimeout(function () {
        $('#upazilaId').val(row_data.upazila_id);
    }, 1500);

    $('#name').val(row_data.name);
    $('#mobile').val(row_data.mobile);
    $('#email').val(row_data.email);
    $('#nid').val(row_data.nid);
    $('#role_id').val(row_data.role_id);
    $('#address').val(row_data.address);
    $('#userName').val(row_data.username);
    $('#passWord').val('');

    //error reset
    $('#upazilaId_error').html('');
    $('#districtId_error').html('');
    $('#name_error').html('');
    $('#mobile_error').html('');
    $('#address_error').html('');
    $('#username_error').html('');
    $('#password_error').html('');
    $("#AddEmployeeModal").modal('toggle');
});

$(document).on('click', '.DeleteUser', function () {
    let row_index = $(this).data('row');
    var row_data = $(".users_table").DataTable().row(row_index).data();
    //employe id
    let id = row_data.id;

    Swal.fire({
        title: "আপনি কি নিশ্চিত?",
        text: row_data.name + " কে ডিলিট করতে কি আপনি নিশ্চিত?",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#458bc4",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "হ্যা",
        cancelButtonText: "না",
    }).then(function (t) {
        if (t.value) {
            $.ajax({
                url: url + "/supplier/employe/delete",
                type: "POST",
                dataType: "JSON",
                data: {
                    id: id
                },
                success: function (responseText) {
                    Swal.fire("সফল!", responseText.msg, "success")
                    $(".users_table").DataTable().draw(true);
                }
            });
        }
    });

});
//custom filtering
function users_filter() {
    $(".users_table").DataTable().draw(true);
}


// ACL reporting

var acl_tbl;

function acl_report_list() {
    acl_tbl = $('#acl_report_tbl').DataTable({
        "dom": 'lBfrtip',
        "processing": true,
        "serverSide": true,
        // "lengthMenu": [25, 50, 75, 100],
        "ajax": {
            "url": "acl/list",
            "type": "GET",
            "data": {}
        },
        "columns": [
            {
                "data": null,
                render: function () {
                    return acl_tbl.page.info().start + acl_tbl.column(0).nodes().length;
                }
            },
            { "data": "role_name" },
            { "data": "created_at" },
            { "data": "updated_at" },
            {
                "data": null,
                render: function (data, type, row) {
                    var action = edit_permission ? '<a href="acl/edit/' + data.id + '"><button class="btn btn-warning btn-xs"><i class="fa fa-edit"></i> Edit</button></a>' : '';
                    
                    action += delete_permission ? '<a href="javascript:void(0);"> <button class="btn btn-danger btn-xs" onclick="delete_acl(' + data.id + ')" ><i class="fas fa-trash"></i> Delete</button> </a>' : '';

                    return action;
                }
            }
        ],
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
        "order": [
            [1, 'desc']
        ]
    });
}

// acl delete function
function delete_acl(acl_id) {
    swal.fire({
        title: "Confirmation",
        text: "Are you want to delete ?",
        type: "warning",
        showConfirmButton: true,
        confirmButtonClass: "btn-success",
        confirmButtonText: "Yes",
        closeOnConfirm: false,
        showCancelButton: true,
        cancelButtonText: "No"
    }).then(function (t) {
        if (t.value) {
            $.ajax({
                url: "acl/delete",
                type: "POST",
                dataType: "JSON",
                data: {
                    acl_id: acl_id
                },
                success: function (response) {
                    swal.fire({
                        title: "Response",
                        text: response.message,
                        type: response.status,
                        showCancelButton: false,
                        showConfirmButton: true,
                        closeOnConfirm: true,
                        allowEscapeKey: false
                    });

                    acl_tbl.ajax.reload();
                }
            });
        }
    });
}