@extends("layouts.admin")
@section("title","Order")
@section("content")
<!-- Start container-fluid -->
<div class="container-fluid">
    {{-- row start --}}
    <div class="row">
        <div class="col-12">
            <div>
                <h4 class="header-title mb-3">অর্ডার সমূহ</h4>
                <!-- <div class="nb-spinner"></div> -->
            </div>
        </div>
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">অর্ডার স্ট্যাটাস</label>
                            <select class="form-control status" id="status" name="status">
                                <option value="">সিলেক্ট</option>
                                <option value="1">অপেক্ষমান</option>
                                <option value="2">কনফার্ম</option>
                                <option value="3">বাতিল</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">অর্ডার অরিজিন</label>
                            <select class="form-control origin" id="origin" name="origin">
                                <option value="">সিলেক্ট</option>
                                <option value="1">ওয়েবসাইট</option>
                                <option value="2">পস</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">তারিখ(হতে)</label>
                            <input class="form-control datepicker" type="text" id="date_from">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">তারিখ(পর্যন্ত)</label>
                            <input class="form-control datepicker" type="text" id="date_to">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary btn-block FilterResult" style="margin-top: 30px;"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- start table row --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="">
                    <table class="table table-bordered order-table">
                        <thead>
                            <tr>
                                <th>নং</th>

                                <th>ক্রেতার নাম</th>

                                <th>মোবাইল</th>

                                <th>অর্ডার নং</th>

                                <th>অর্ডার মূল্য</th>

                                <th>স্ট্যাটাস</th>

                                <th>অরিজিন</th>

                                <th>একশন</th>
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
<!-- end container-fluid -->

<div id="deliveryModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deliveryModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deliveryModal">ডেলিভারি ম্যান সিলেক্ট করুন</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <div class="alert d-none" id="msg_div">
                <span id="res_message"></span>
            </div>

            <div class="modal-body">
                {{-- <form> --}}
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <select name="delivery_man" id="delivery_man" class="form-control">
                                    <option value=''>Select</option>
                                </select>

                                <span id="delivery_man_error" style="color: red;"></span>

                                <input type="hidden" name="order_id" id="order_id" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="form-group" style="text-align: center;">
                                <button class="btn btn-primary" onclick="updateDeliveryMan()">Save</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>

                {{-- </form> --}}
            </div>

        </div>
    </div>
</div>
<div id="OrderConfirmModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirm_order_title">অর্ডার কনফার্ম করুন | <span id="title_order_id"></span> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form class="OrderConfirmForm" id="OrderConfirmForm" action="javascript:void(0)" method="POST">
                <input type="hidden" id="order_id" name="order_id">
                <input type="hidden" id="client_mobile" name="client_mobile">

                <div class="modal-body">
                        <table class="table table-bordered"> </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect waves-light" id="submit_btn">কনফার্ম</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">বাতিল</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection

@section('js')
<script type="text/javascript">
    $(function() {
        var orderr_table = $('.order-table').DataTable({

            scrollCollapse: true
            , autoWidth: true
            , responsive: true
            , serverSide: true
            , processing: true,

            ajax: {
                url: "{{ route('supplier.order.data') }}"
                , data: function(e) {

                    e.status = $('.status').val() || 0,
                    e.origin = $('.origin').val() || 0,
                    e.from_date = $('#date_from').val() || 0,
                    e.to_date = $('#date_to').val() || 0
                }
            },

            columns: [

                {
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
                },

                {
                    data: 'client_name'
                    , name: 'client_name'
                },

                {
                    data: 'client_mobile'
                    , name: 'client_mobile'
                },

                {
                    data: 'order_id'
                    , name: 'order_id'
                },

                {
                    data: 'order_amount'
                    , name: 'order_amount'
                },

                {
                    name: 'status'
                    , orderable: false
                    , searchable: false
                    , render: function(data, type, row) {
                        let html = '';
                        if (row.status == 1) { // pending
                            html = '<button class="btn btn-teal btn-sm orderConfirmButton" data-order_id="' + row.order_id + '" data-client_mobile="' + row.client_mobile + '" data-status="4"><i class="fa fa-check"></i>কনফার্ম</button><button class="btn btn-danger btn-sm orderStatusChange" data-client_mobile="' + row.client_mobile + '" data-order_id="' + row.order_id + '" data-status="3"><i class="fas fa-trash-alt"></i> রিজেক্ট</button>';
                        } else if (row.status == 2) { // confirm
                            html = '<span class="badge badge-teal">Confirmed</span>';
                        } else if (row.status == 3) { // rejected
                            html = '<span class="badge badge-danger">Rejected</span>';
                        } else if (row.status == 4) { // pending for delivery
                            html = '<button class="btn btn-teal btn-sm" onclick="delivery_process(' + row.order_id + ')"><i class="fa fa-check"></i>ডেলিভারি</button><button class="btn btn-danger btn-sm orderStatusChange" data-client_mobile="' + row.client_mobile + '" data-order_id="' + row.order_id + '" data-status="3"><i class="fas fa-trash-alt"></i> রিজেক্ট</button>';
                        } else if (row.status == 5){
                            html = '<span class="badge badge-primary">Delivery Processing</span><button class="btn btn-teal btn-sm" onclick="delivery_process(' + row.order_id + ')"><i class="fa fa-check"></i>ডেলিভারি</button><button class="btn btn-danger btn-sm orderStatusChange" data-client_mobile="' + row.client_mobile + '" data-order_id="' + row.order_id + '" data-status="3"><i class="fas fa-trash-alt"></i> রিজেক্ট</button>';
                        } else {
                            html = 'Unknown';
                        }

                        return html;
                    }
                },

                {
                    name: 'origin'
                    , orderable: false
                    , searchable: false
                    , render: function(data, type, row) {
                        let html = '';
                        if (row.origin == 1) {
                            html = '<span class="badge badge-teal">ওয়েবসাইট</span>';
                        } else {
                            html = '<span class="badge badge-teal">পস</span>';
                        }
                        return html;
                    }
                },

                {
                    data: 'action'
                    , name: 'action'
                    , orderable: false
                    , searchable: false
                },

            ]

        });
    });

    function delivery_process(order_id) {
        var selectOption = '<option value="">Select</option>';

        $("#order_id").val(order_id);
        $("#delivery_man").html(selectOption);
        $("#delivery_man_error").html('');

        $.ajax({
            url: "{{ route('deliveryman.list') }}",
            method: "GET",
            dataType: "JSON",
            data: {order_id: order_id},
            success: function(response){
                response.data.delivery_list.forEach(function(item){
                    console.log(response.data.delivery_man_id);
                    selectOption += '<option value="'+item.id+'" '+(response.data.delivery_man_id == item.id ? "selected" : "")+' >'+item.name+' ('+item.mobile+')</option>';
                });

                $("#delivery_man").html(selectOption);

                if(response.data.delivery_man_id){
                    
                    $("#delviery_man").val(response.data.delivery_man_id);
                }

            }
        });

        $("#deliveryModal").modal("toggle");
    }

    function updateDeliveryMan(){
        var order_id = $("#order_id").val();
        var delivery_man_id = $("#delivery_man").val();

        var error_status = false;

        if(delivery_man_id == ''){
            error_status = true;

            $("#delivery_man_error").html("Please select delivery man.");
        }

        if(error_status == false){
            $.ajax({
                url: "{{ route('deliveryman.order.assign') }}",
                method: "POST",
                dataType: "JSON",
                data: {order_id: order_id, delivery_man_id: delivery_man_id},
                success: function(response){
                    $("#deliveryModal").modal("toggle");
                    
                    if(response.status == 'success'){
                        toastr.success(response.message, "Success");
                    } else {
                        toastr.error(response.message, "Error");
                    }
                }
            });
        }

    }

    $(document).on('click', '.FilterResult', function() {
        $(".order-table").DataTable().draw(true);
    });

    $(document).on("click", ".orderStatusChange", function() {
        let order_id = $(this).data('order_id');
        let client_mobile = $(this).data('client_mobile');
        let status = $(this).data('status');

        var text = (status == 4) ? 'অর্ডারটি কনফার্ম হয়েছে। ডেলিভারীর জন্য অপেক্ষমান আছে।' : 'অর্ডারটি বাতিল হয়েছে';

        $.ajax({
            url: "{{ route('supplier.order.status_change') }}"
            , method: "GET"
            , dataType: 'JSON'
            , data: {
                order_id: order_id
                , client_mobile: client_mobile
                , status: status
            }
            , success: function(res) {
                if (res.status == 'success') {
                    toastr.success(text);
                    $(".order-table").DataTable().draw(true);
                }
            }
        });
    });

$(document).on('click',".orderConfirmButton",function(){
    let order_id = $(this).data('order_id');
    $("#title_order_id").html(order_id);
    $(".OrderConfirmForm #order_id").val(order_id);
    $.ajax({
                url: "{{ route('supplier.order.getProductStockVariation') }}",
                method: "POST",
                dataType: "JSON",
                data: {order_id: order_id},
                success: function(res){
                   
                    if(res.status == 'success'){
                        htmlRenderToOrderCofirmModal(res.data);    // render html data to order confirm modal                   
                    }
                    else{
                        toastr.error("এই সম্পর্কিত কোন তথ্য খুজে পাওয়া যায়নি", "কোন কিছু ভুল হয়েছে");
                    }
                }
            });
});

$(document).on("submit","#OrderConfirmForm",function(){
     $.ajax({
            url: "{{route('supplier.order.web.confirm')}}",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (res) {
               toastr.success(res.message);
               $("#OrderConfirmModal").modal('toggle');
               $(".order-table").DataTable().draw(true);
            }
        });
});

function htmlRenderToOrderCofirmModal(data){
    let row = '';
        data.forEach(function(item){
            row+='<tr>'
                 +'<td>'+item.product_name+'</td>'
                  +'<td>'
                   +'<select class="form-control" name="inventory_id[]" required><option>পন্য নির্বাচন করুন</option>';
                item.stocks.forEach(function(stock){
                    row+='<option value="'+stock.id+'">'+stock.name+'('+stock.buying_price+')</option>';
                });
            row+='</select>'
                    +'</td>'
                    +'<input type="hidden" name="product_id[]" value="'+item.product_id+'">'
                +'</tr>';
        });
     $("#OrderConfirmModal table").html(row);
     $("#OrderConfirmModal").modal('toggle');
}

</script>

@endsection
