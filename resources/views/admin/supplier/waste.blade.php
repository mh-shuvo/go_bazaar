@extends("layouts.admin")
@section("title","নষ্ট পন্য তালিকা")
@section("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-10">
            <div>
                <h4 class="header-title mb-3">নষ্ট পন্য তালিকা</h4>
            </div>
        </div>
        <div class="col-2">

            @if (permission_check('wastage', 'create'))
            <a href="javascript:void(0)" class="btn btn-sm btn-info AddWasteStock">নষ্ট পন্য যুক্ত করুণ</a>
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
                        <button class="btn btn-primary btn-block FilterWasteResult" style="margin-top: 30px;"><i
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
                    <table
                        class="table table-bordered waste-inventory-table table table-hover mails m-0 table table-actions-bar table-centered">
                        <thead>
                            <tr>
                                <th>নং</th>

                                <th>ক্যাটাগরি</th>

                                <th>সাব-ক্যাটাগরি</th>

                                <th>পন্য</th>

                                <th>পরিমাণ</th>

                                <th>তারিখ</th>

                                <th>অ্যাকশন</th>
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
<div id="AddWasteStockModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="AddStockModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">নষ্ট পন্য যুক্ত করুন</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form class="AddWasteStockForm" id="AddWasteStockForm" action="javascript:void(0)"
                enctype="multipart/form-data" method="POST">
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
                        <label class="col-md-3 col-form-label">পন্যের পরিমাণ:</label>
                        <div class="col-md-9">
                            <input type="text" id="quantity" name="quantity" class="form-control"
                                placeholder="পন্যের পরিমাণ লিখুন" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="reset" class="btn btn-secondary waves-effect" onclick="return resetSpecialElement()">রিসেট</button> --}}
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

@endsection

@section('js')
<script src="{{ asset('public/admin/assets/js/pages/stock.js') }}"></script>
<script type="text/javascript">
    var edit_permission = 0, delete_permission = 0;

    edit_permission = {{ permission_check('wastage', 'edit') ? 1 : 0 }};
    delete_permission = {{ permission_check('wastage', 'delete') ? 1 : 0 }};

    $(function () {
    var waste_table = $('.waste-inventory-table').DataTable({

      scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: {
            url: url+"/waste/data",
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

            {data: 'debit', name: 'debit'},

            {data: 'created_at', name: 'created_at'},

            {name: 'action', orderable: false, searchable: false,
            render:function(data, type, row){
                    var action = edit_permission ? '<a href="javascript:void(0)" data-id = "'+row.id+'" data-rate_id="'+row.rate_id+'" data-inventory_id="'+row.inventory_id+'" class="WasteStockEdit btn btn-primary btn-sm">সম্পাদন</a>' : '';
                    
                    action += delete_permission ? '<a href="javascript:void(0)" data-inventory_id="'+row.inventory_id+'" class="WasteStockDelete btn btn-danger btn-sm">বাতিল</a>' : '';

                    return action;
            }},

        ]

    });

  });
</script>
@endsection