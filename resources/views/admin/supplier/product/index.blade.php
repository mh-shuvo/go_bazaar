@extends("layouts.admin")
@section("title","পন্যের তালিকা")
@section("content")
<div class="container-fluid">

    <div class="row">
        <div class="col-7">
            <div>
                <h4 class="header-title mb-3">পন্যসমূহের তালিকা</h4>
            </div>
        </div>
        <div class="col-5">

            @if (permission_check('product', 'create'))
            <a href="javascript:void(0)" class="btn btn-primary AddProduct">পন্য যুক্ত করুণ</a>
            @endif

        </div>
        <div class="col-12">
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
                    <table
                        class="table table-bordered product_list_table table table-hover mails m-0 table table-actions-bar table-centered">

                        <thead>

                            <tr>

                                <th>নং</th>

                                <th>আইডি</th>

                                <th>ছবি</th>

                                <th>ক্যাটাগরি</th>

                                <th>সাব ক্যাটাগরি</th>

                                <th>পন্যের নাম</th>

                                {{-- <th>পন্যের বিবরণ</th> --}}

                                <th>তারিখ</th>

                                <th>অ্যাকশন</th>

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
<div id="AddProductModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">পন্য যুক্ত করুন</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form class="AddProductForm" id="AddProductForm" action="javascript:void(0)" enctype="multipart/form-data"
                method="POST">
                <input type="hidden" id="id" name="id">
                <div class="alert d-none" id="msg_div">
                    <span id="res_message"></span>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">পন্যের ক্যাটাগরি:</label>
                        <div class="col-md-9">
                            <select class="form-control" id="category" name="category">
                                <option value="">পন্যের ধরন নির্বাচন করুণ</option>
                                @foreach (App\Category::where('type','1')->get() as $item)
                                <option value="{{ $item->id }}" @if(isset($data) && $data->category_id == $item->id)
                                    selected @endif>{{ $item->name }}</option>
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
                    <!-- <div class="form-group row">
                            <label class="col-md-3 col-form-label">পন্যের একক:</label>
                            <div class="col-md-9">
                                <select class="form-control" id="unit" name="unit" >
                                    <option value="">পন্যের একক নির্বাচন করুণ</option>
                                    @foreach (App\Unit::all() as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">পন্যের নাম:</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="name" id="name"
                                placeholder="পন্যের নাম লিখুন">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">পন্যের বিবরণ:</label>
                        <div class="col-md-9">
                            <textarea class="form-control" rows="6" name="description" id="description"
                                placeholder="পন্যের বিবরণ"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">পন্যের ছবি:</label>
                        <div class="col-md-9">

                            <div class="row" id="picture_input1">
                                <div class="col-sm-6">
                                    <input class="form-control-file picture" type="file" name="picture[]"
                                        data-pi_no="1">
                                </div>
                                <div class="col-md-4">
                                    <img class="d-none image_preview"
                                        id="image_preview1" style="height:50px; width:80px; border-radious: 5px;">
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-sm btn-primary" id="AddPictureInput"> <i
                                            class="fa fa-plus"></i> </button>
                                </div>
                            </div>

                            <div class="picture_inputs"></div>

                        </div>
                    </div>
                    <div class="row product_pictures"></div>
                </div>
                <div class="modal-footer">
                    {{--  <button type="reset" class="btn btn-secondary waves-effect" onclick="return resetSpecialElement()">রিসেট</button> --}}
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
<div id="AddBulkProductModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">পন্য যুক্ত করুন</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form class="AddBulkProductForm" id="AddBulkProductForm" action="javascript:void(0)" enctype="multipart/form-data"
                method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">CSV ফাইল নির্বাচন করুন:</label>
                        <input class="form-control-file product_csv_file" type="file" name="product_csv_file" accept=".csv,.xlsx">
                    </div>
                </div>
                <div class="modal-footer">
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
<script src="{{ asset('public/admin/assets/js/pages/product.js') }}"></script>
@endsection