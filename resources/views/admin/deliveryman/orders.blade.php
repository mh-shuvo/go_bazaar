@extends("layouts.admin")
@section("title","Dashboard")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">অর্ডার সমূহ </h4>
                </div>
            </div>
        </div>
        <!-- end row -->

        {{-- start table row --}}
        <div class="row">

            <div class="col-sm-12">

                <div class="card-box">
                    {{-- <h5 class="mt-0 font-14 mb-3">Users</h5> --}}

                    <div class="row">
                        <div class="col-sm-2"></div>
                        <label class="col-sm-2">অর্ডার স্ট্যাটাস</label>
                        <select type="text" name="status" id="status" class="form-control col-sm-3">
                            <option value="">সিলেক্ট</option>
                            <option value="0">অপেক্ষমান</option>
                            <option value="1">কনফার্ম</option>
                            <option value="2">বাতিল</option>
                        </select>&nbsp;&nbsp;

                        <button class="btn btn-sm btn-primary col-sm-1" onclick="order_filter()" >সার্চ</button>
                    </div>

                    <div class="">
                        <table class="orderlist_table table table-bordered data-table table table-hover mails m-0 table table-actions-bar table-centered">

                            <thead>
                                <tr>
                                    <th>নং</th>
                                    <th>অর্ডার আইডি</th>
                                    <th>সরবরাহকারী</th>
                                    <th>ত্রেতা</th>
                                    <th>মোবাইল</th>
                                    <th>সর্বমোট</th>
                                    <th>স্ট্যাটাস</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div>
    <!-- end container-fluid -->

@endsection

@section('js')
<script type="text/javascript">

  $(function () {


    var orderlist_table = $('.orderlist_table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: {

          url: "{{ route('delivery.orders.data') }}",

          data: function (e) {

                e.status = $('#status').val() || ''

            }

        },

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'order_id_field', name: 'order_id_field'},

            {data: 'supplier_name', name: 'supplier_name'},

            {data: 'name', name: 'name'},

            {data: 'mobile', name: 'mobile'},

            {data: 'total_amount', name: 'total_amount'},

            {data: 'status', name: 'status', render:function(data, type, row, meta){

                if(row.status == 0){
                    return '<span class="badge badge-primary">অপেক্ষমান</span>';
                }else if(row.status == 1){
                    return '<span class="badge badge-teal">কনফার্ম</span>';
                }else{
                    return '<span class="badge badge-danger">বাতিল</span>';
                }

            }},

            {data: 'action', name: 'action', orderable: false, searchable: false,render:function(data,type,row){
                      let html = '';
                        if (row.status == 0) { // pending
                            html = '<button class="btn btn-teal btn-sm orderStatusChange" data-order_id="' + row.order_id + '" data-supplier_id="'+row.supplier_id+'" data-status="1"><i class="fa fa-check"></i>কনফার্ম</button><button class="btn btn-danger btn-sm orderStatusChange" data-supplier_id="'+row.supplier_id+'" data-order_id="' + row.order_id + '" data-status="2"><i class="fas fa-trash-alt"></i> রিজেক্ট</button>';
                        } else if (row.status == 1) { // confirm
                           html = '<span class="badge badge-teal">কনফার্মড</span>';
                        } else if (row.status == 3) { // rejected
                            html = '<span class="badge badge-danger">রিজেক্টড</span>';
                        }else {
                            html = '';
                        }

                        return html;
            }},

        ]

    });

  });

//custom filtering
function order_filter(){
    $(".orderlist_table").DataTable().draw(true);
}

$(document).on("click", ".orderStatusChange", function() {
     let order_id = $(this).data('order_id');
     let supplier_id = $(this).data('supplier_id');
     let status = $(this).data('status');
     var text = (status == 1) ? 'অর্ডারটি কনফার্ম হয়েছে।' : 'অর্ডারটি বাতিল হয়েছে';

          Swal.fire({
            title: "আপনি কি নিশ্চিত?",
            text: "",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#458bc4",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "হ্যা",
            cancelButtonText: "না",
        }).then(function (t) {
            if (t.value) {
                $.ajax({
                    url: "{{ route('delivery.order.status_change') }}"
                    , method: "POST"
                    , dataType: 'JSON'
                    , data: {
                        order_id: order_id , 
                        supplier_id: supplier_id, 
                        status: status
                    }
                    , success: function(res) {
                        if (res.status == 'success') {
                            Swal.fire(text, '', "success")
                            $(".orderlist_table").DataTable().draw(true);
                        }
                    }
                });    
            }
        });
    });

</script>

@endsection