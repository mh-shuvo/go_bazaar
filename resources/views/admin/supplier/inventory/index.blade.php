@extends("layouts.admin")
@section("title","স্টকের তালিকা")
@section("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-7">
            <div>
                <h4 class="header-title mb-3">স্টকের তালিকা</h4>
            </div>
        </div>


        <div class="col-3 text-right">
            <a href="{{route('supplier.bulk_barcode')}}" class="btn btn-sm btn-primary">বারকোড প্রিন্ট করুণ</a>
        </div>

        <div class="col-2">

            @if (permission_check('stock', 'create'))
            <a href="javascript:void(0)" class="btn btn-sm btn-info AddStock">স্টক যুক্ত করুণ</a>
            @endif

        </div>
        <div class="col-12">
            @if ($message = Session::get('success'))

            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>

                <strong>{{ $message }}</strong>
            </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">ক্যাটাগরি</label>
                            <select class="form-control" id="filter_category" name="filter_category">
                                <option value="">পন্যের ধরন নির্বাচন করুণ</option>
                                @foreach (App\Category::where('type','1')->get() as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">সাব ক্যাটাগরি</label>
                            <select class="form-control" id="filter_sub_category" name="filter_sub_category">
                                <option value="">সাব ক্যাটাগরি নির্বাচন করুন</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">পন্য</label>
                            <select class="form-control filter_product" id="filter_product" name="filter_product">
                                <option value="">পন্য নির্বাচন করুন</option>
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary btn-block FilterResult" style="margin-top: 30px;"><i
                                class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="">
                    <table class="table table-bordered inventory-table">
                        <thead>
                            <tr>
                                <th>নং</th>

                                <th>ক্যাটাগরি</th>

                                <th>সাব-ক্যাটাগরি</th>

                                <th>পন্য</th>

                                <th>ক্রয় মূল্য</th>

                                <th>বিক্রয় মূল্য</th>

                                <th>পরিমাণ</th>

                                <th>স্ট্যাটাস</th>

                                <th>তারিখ</th>

                                <th width="100px">অ্যাকশন</th>
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
<div id="AddStockModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="AddStockModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">স্টক যুক্ত করুন</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form class="AddStockForm" id="AddStockForm" action="javascript:void(0)" enctype="multipart/form-data"
                method="POST">
                <input type="hidden" id="rate_id" name="rate_id" />
                <input type="hidden" id="inventory_id" name="inventory_id" />
                <div class="alert d-none" id="msg_div">
                    <span id="res_message"></span>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">পন্যের ক্যাটাগরি:</label>
                        <div class="col-md-9">
                            <select class="form-control" id="category" name="category">
                                <option value="">পন্যের ক্যাটাগরি নির্বাচন করুণ</option>
                                @foreach (App\Category::where('type','1')->get() as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">পন্যের সাব ক্যাটাগরি:</label>
                        <div class="col-md-9">
                            <select class="form-control" id="sub_category" name="sub_category">
                                <option value="">পন্যের সাব ক্যাটাগরি নির্বাচন করুণ</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">পন্য:</label>
                        <div class="col-md-9">
                            <select class="form-control" id="product" name="product">
                                <option value="">পন্যের নির্বাচন করুণ</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">ক্রয় মুল্য:</label>
                        <div class="col-md-9">
                            <input type="text" id="buying_price" name="buying_price" class="form-control"
                                placeholder="পন্যের ক্রয় মুল্য লিখুন" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">বিক্রয় মুল্য:</label>
                        <div class="col-md-9">
                            <input type="text" id="saling_price" name="saling_price" class="form-control"
                                placeholder="পন্যের বিক্রয় মুল্য লিখুন" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">পন্যের পরিমাণ:</label>
                        <div class="col-md-9">
                            <input type="text" id="quantity" name="quantity" class="form-control"
                                placeholder="পন্যের পরিমাণ লিখুন" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-teal waves-effect printBarcode">প্রিন্ট বারকোড</button> -->
                    <button type="submit" class="btn btn-primary waves-effect waves-light"
                        id="submit_btn">সাবমিট</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">বাতিল</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div id="PrintBarcodeModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="AddStockModalLabel"
    aria-hidden="true" onsubmit="$('#PrintBarcodeModal').modal('toggle');">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">বারকোড প্রিন্ট করুন</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form class="PrintBarcodeForm" id="PrintBarcodeForm" action="{{route('supplier.inventory.barcode')}}"
                method="POST" target="_blank">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">পরিমাণ:</label>
                        <div class="col-md-9">
                            <input type="text" id="qty" name="qty" class="form-control"
                                placeholder="বারকোডের পরিমাণ লিখুন" required="" />
                            <input type="hidden" name="product_id" id="product_id" value="">
                            <input type="hidden" name="inventory_id" id="inventory_id" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect waves-light"
                        id="submit_btn">সাবমিট</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">বাতিল</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('public/admin/assets/js/pages/stock.js') }}"></script>
<script type="text/javascript">
    var edit_permission = 0, delete_permission = 0;

    edit_permission = {{ permission_check('stock', 'edit') ? 1 : 0 }};
    delete_permission = {{ permission_check('stock', 'delete') ? 1 : 0 }};

    $(function () {
    var stock_table = $('.inventory-table').DataTable({
		"lengthMenu": [[10, 25, 50,100,500,1000,5000,-1], [10, 25, 50,100,500,1000,5000,"All"]],
        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: {
            url: url+"/stock/data",
            data:function (e) {

                e.category_id = $('#filter_category').val() || 0,
                e.sub_category_id = $('#filter_sub_category').val() || 0,
                e.product_id = $('.filter_product').val() || 0

            }
        },

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'category', name: 'category'},

            {data: 'sub_category', name: 'sub_category'},

            {data: 'name', name: 'name'},

            {data: 'buying_price', name: 'buying_price'},

            {data: 'saling_price', name: 'saling_price'},

            {data: 'credit', name: 'credit'},

            {name: 'is_sold',orderable:false,searchable:false,
                render:function(datat,type,row){
                    let html = '';
                    if(row.is_sold == 1 || row.credit == 0){
                        html+='<label class="label label-danger">স্টক আউট</label>';
                    }else{
                        html+='<label class="label label-danger">পর্যাপ্ত আছে</label>';
                    }
                    return html;
                }
            },

            {data: 'created_at', name: 'created_at'},

            {name: 'action', orderable: false, searchable: false,
            render:function(data,type,row){
                    var action = '<a href="javascript:void(0)" data-id = "'+row.id+'" data-inventory_id="'+row.inventory_id+'" class="PrintBarcode btn btn-teal btn-sm"><i class="fa fa-barcode"></i></a>'; 
                    
                    action += edit_permission ? '<a href="javascript:void(0)" data-id = "'+row.id+'" data-rate_id="'+row.rate_id+'" data-inventory_id="'+row.inventory_id+'" class="StockEdit btn btn-primary btn-sm">সম্পাদন</a>' : '';
                    
                    action += delete_permission ? '<a href="javascript:void(0)" data-inventory_id="'+row.inventory_id+'" class="StockDelete btn btn-danger btn-sm">বাতিল</a>' : '';

                    return action;
            }},

        ]

    });
    });
</script>
@endsection