@extends("layouts.admin")
@section("title","Dashboard")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-9">
                <div>
                    <h4 class="header-title mb-3">ক্যাটেগরি ও সাব-ক্যাটেগরি তালিকা</h4>
                </div>
            </div>
            <div class="col-3">
                <button type="button" class="btn btn-sm btn btn-success btn-bordered-success float-right waves-effect waves-light mb-3" data-toggle="modal"  onclick="add_category()" > <i class="ti-plus"></i> নতুন যোগ করুন</button>
            </div>
        </div>
        <!-- end row -->

        {{-- start table row --}}
        <div class="row">

            <div class="col-sm-12">

                <div class="card-box">
                    <div class="">
                        <table class="table table-bordered data-table table table-hover mails m-0 table table-actions-bar table-centered category_table" style="width: 100%;">

                            <thead>

                                <tr>

                                    <th>নং</th>

                                    <th>আইডি</th>

                                    <th>ক্যাটেগরি</th>

                                    <th>সাব-ক্যাটেগরি</th>
                                    <th>সিরিয়াল</th>
                                    <th>স্ট্যাটাস</th>
                                    <th>ফিচারড</th>

                                    <th width="100px">Action</th>

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

    <!-- user add modal content -->
    <div id="category_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">ক্যাটেগরি ও সাব-ক্যাটেগরি</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="form-validation" enctype="multipart/form-data" id="category_form" action="javascript:void(0)">
                <div class="modal-body">
                        <div class="form-group row">
                            <label for="upazila" class="col-md-4 form-control-label">ক্যাটেগরি</label>
                            <div class="col-md-7">
                                <select class="form-control"  name="category" id="category" >
                                    <option value="">সিলেক্ট</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 form-control-label">নাম<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="name" required parsley-type="name" class="form-control" name="name" id="name" placeholder="নাম">

                                <span class="text-danger" id="name_error"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                             <label for="icon" class="col-md-4 form-control-label">আইকন নির্বাচন করুন</label>
                             <div class="col-md-7">
                                 <input id="icon" class="form-control" type="file" name="icon" />
                             </div>
                        </div>

                        <div class="form-group row">
                            <label for="sorting" class="col-md-4 form-control-label">সিরিয়াল</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="sorting" id="sorting" placeholder="সিরিয়াল">

                                <span class="text-danger" id="is_show_error"></span>
                            </div>
                            <label for="is_show" class="col-md-3 form-control-label">প্রদর্শিত হবে ?</label>
                            <div class="col-md-1">
                                <input type="checkbox" parsley-type="is_show" class="form-control" name="is_show" id="is_show" value="1">

                                <span class="text-danger" id="is_show_error"></span>
                            </div>
                            
                        </div>

                         <div class="form-group row">
                            
                            <label for="is_feature" class="col-md-4 form-control-label">ফিচারড</label>
                            <div class="col-md-1">
                                <input type="checkbox"  class="form-control" name="is_feature" id="is_feature" value="1">

                                <span class="text-danger" id="is_feature_error"></span>
                            </div>
                            
                        </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="row_id" id="row_id">

                    <button type="submit" id="category_save_button" class="btn btn-primary waves-effect waves-light">সাবমিট</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">বাতিল</button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('js')
<script type="text/javascript">

  $(function () {


    var category_table = $('.category_table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: "{{ route('category_list') }}",

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'id', name: 'id'},

            {data: 'category', name: 'category'},

            {data: 'sub_category', name: 'sub_category'},
            {data: 'sorting', name: 'sorting'},
            
            { data: 'is_show', name: 'is_show', render:function(data, type, row, meta){

                if(row.is_show == 1) {

                    return '<span class="badge badge-teal">Show</span>';
                }else{
                    return '<span class="badge badge-danger badge-pill">Hide</span>';
                }

            }},

             { data: 'is_feature', name: 'is_feature', render:function(data, type, row, meta){

                if(row.is_feature == 1) {

                    return '<span class="badge badge-teal">ফিচারড</span>';
                }else{
                    return '<span class="badge badge-danger badge-pill">নন-ফিচারড</span>';
                }

            }},


            { data: 'id', name: 'id', render:function(data, type, row, meta){

                return "<a href='javascript:void(0)' class='edit btn btn-primary btn-sm' onclick='category_edit("+meta.row+")' >Edit</a> <a href='javascript:void(0)' class='edit btn btn-danger btn-sm' onclick='category_delete("+meta.row+")' >Delete</a>"

            }},

        ]

    });



  });



</script>

<script type="text/javascript" src="{{ asset('public/admin/assets/js/admin.js') }}"></script>
@endsection