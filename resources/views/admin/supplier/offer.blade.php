@extends("layouts.admin")
@section("title","অফার ব্যবস্থাপনা")
@section("content")
<!-- Start container-fluid -->
<div class="container-fluid">
    {{-- row start --}}
    <div class="row">
        <div class="col-9">
            <div>
                <h4 class="header-title mb-3">অফারের তালিকা</h4>
            </div>
        </div>
        
        <div class="col-3">
            
            @if (permission_check('offer', 'create'))
            <button type="button" class="btn btn-sm btn btn-success btn-bordered-success float-right AddOffer"> <i class="ti-plus"></i> নতুন যোগ করুন</button>
            @endif
            
        </div>

    </div>
    <!-- end row -->

    {{-- start table row --}}
    <div class="row">

        <div class="col-md-12">

            <div class="card-box">

                <div class="row">
                <div class="">
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
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label">পন্য</label>
                                        <select class="form-control filter_status" id="filter_status" name="filter_status">
                                            <option value="">অফার স্ট্যাটাস নির্বাচন করুণ</option>
                                            <option value="1">একটিভ</option>
                                            <option value="0">ইন-একটিভ</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-primary btn-block FilterOfferResult" style="margin-top: 30px;"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <div class="">
                            <table class="table table-bordered offer-table">
                                <thead>
                                    <tr>
                                        <th>নং</th>

                                        <th>ক্যাটাগরি</th>

                                        <th>সাব-ক্যাটাগরি</th>

                                        <th>পন্য</th>

                                        <th>ধরণ</th>

                                        <th>অফারের পরিমাণ</th>

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
        </div>
    </div>
    <!-- end row -->

</div>
<!-- end container-fluid -->

<div id="AddOfferModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="AddOfferModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">অফার যোগ করুন</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form class="form-validation" id="AddOfferForm" action="javascript:void(0)" method="post" enctype="multipart/form-data">
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
                            <span class="text-danger" id="category_error"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">পন্যের সাব ক্যাটাগরি:</label>
                        <div class="col-md-9">
                            <select class="form-control" id="sub_category" name="sub_category">
                                <option value="">পন্যের সাব ক্যাটাগরি নির্বাচন করুণ</option>
                            </select>
                            <span class="text-danger" id="sub_category_error"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">পন্য:</label>
                        <div class="col-md-9">
                            <select class="form-control" id="product" name="product">
                                <option value="">পন্যের নির্বাচন করুণ</option>
                            </select>
                            <span class="text-danger" id="product_error"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">অফার এর ধরণ:</label>
                        <div class="col-md-9">
                            <select class="form-control" id="offer_type" name="offer_type">
                                <option value="">অফার এর ধরন নির্বাচন করুণ</option>
                                <option value="1">৳</option>
                                <option value="2">%</option>
                            </select>
                            <span class="text-danger" id="offer_type_error"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">অফার এর পরিমাণ:</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="offer_amount" name="offer_amount" placeholder="অফার এর পরিমাণ" />
                            <span class="text-danger" id="offer_amount_error"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">অফার স্ট্যাটাস:</label>
                        <div class="col-md-9">
                            <select class="form-control" id="offer_status" name="offer_status">
                                <option value="">অফার স্ট্যাটাস নির্বাচন করুণ</option>
                                <option value="1">একটিভ</option>
                                <option value="0">ইন-একটিভ</option>
                            </select>
                            <span class="text-danger" id="offer_status_error"></span>
                        </div>
                    </div>
            </div>
            <div class="modal-footer text-center" style="text-align: center;">
                <input type="hidden" name="offer_id" id="offer_id">

                <button type="submit" class="btn btn-primary waves-effect waves-light">সাবমিট</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">বাতিল</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

@endsection

@section('js')
<script type="text/javascript">

var edit_permission = 0, delete_permission = 0;

edit_permission = {{ permission_check('offer', 'edit') ? 1 : 0 }};
delete_permission = {{ permission_check('offer', 'delete') ? 1 : 0 }};

$(function() {
    var stock_table = $('.offer-table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: {
            url: url + "/supplier/offer/data",
            data: function(e) {

                e.category_id = $('#filter_category').val() || 0,
                e.sub_category_id = $('#filter_sub_category').val() || 0,
                e.product_id = $('.filter_product').val() || 0,
                e.offer_status = $('.filter_status').val() || ''

            }
        },

        columns: [

            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },

            {
                data: 'category',
                name: 'category'
            },

            {
                data: 'sub_category',
                name: 'sub_category'
            },

            {
                data: 'name',
                name: 'name'
            },

            {
                name: 'offer_type', orderable:false, searchable:false,
                render:function(data,type,row){
                    let html = '';
                    if(row.offer_type == 1){
                        html = '<label class="badge badge-info">৳</label>';
                    }
                    else{
                        html = '<label class="badge badge-teal">%</label>';
                    }
                    return html;
                }
            },

            {
                data: 'offer_amount',
                name: 'offer_amount'
            },

            {
                name: 'offer_status', orderable:false, searchable:false,
                render:function(data,type,row){
                    let html = '';
                    if(row.offer_status == 1){
                        html = '<label class="badge badge-success">একটিভ</label>';
                    }
                    else{
                        html = '<label class="badge badge-danger">ইন-একটিভ</label>';
                    }
                    return html;
                }
            },


            {
                data: 'created_at',
                name: 'created_at'
            },

            {
                name: 'action',
                orderable: false,
                searchable: false,
                render: function(data, type, row,meta) {
                    var action = edit_permission ? '<a href="javascript:void(0)" data-row = "' + meta.row + '" class="OfferEdit btn btn-primary btn-sm">সম্পাদন</a>' : '';
                    
                    action += delete_permission ? '<a href="javascript:void(0)" class="OfferDelete btn btn-danger btn-sm" data-row = "' + meta.row + '">বাতিল</a>' : '';

                    return action;
                }
            },

        ]

    });
});
</script>

<script src="{{ asset('public/admin/assets/js/pages/stock.js') }}"></script>
@endsection
