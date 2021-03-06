@extends("layouts.admin")
@section("title","Dashboard")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">সরবরাহকারীর ধরন</h4>
                </div>
            </div>
        </div>
        <!-- end row -->

        {{-- start table row --}}
        <div class="row">

            <div class="col-sm-12">

                 <button type="button" class="btn-sm btn btn-success btn-bordered-success float-right waves-effect waves-light" data-toggle="modal"  onclick="add_supplier_type()" > <i class="ti-plus"></i> নতুন যোগ করুন</button>
            </div>

            <div class="col-sm-12">

                <div class="card-box">
                    {{-- <h5 class="mt-0 font-14 mb-3">Users</h5> --}}
                    <div class="">
                        <table class="table table-bordered data-table table table-hover mails m-0 table table-actions-bar table-centered supplier_type_table" id="supplier_type_table">

                            <thead>

                                <tr>

                                    <th>নং</th>

                                    <th>ধরন</th>

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
    <div id="supplier_type_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">সরবরাহকারীর ধরন</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-validation">

                        <div class="form-group row">
                            <label for="name" class="col-md-4 form-control-label">ধরন<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="name" required parsley-type="name" class="form-control" name="name" id="name" placeholder="সরবরাহকারীর ধরন প্রদান করুন">
                                <span id="name_error" class="text-danger"></span>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="row_id" id="row_id">

                    <button type="button" id="supplier_type_save_button" class="btn btn-primary waves-effect waves-light" onclick="supplier_type_store()" >সাবমিট</button>
                    <button type="button" id="supplier_type_update_button" class="btn btn-warning waves-effect waves-light" onclick="supplier_type_update()" >আপডেট</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">বাতিল</button>
                </div>
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


    var supplier_type_table = $('.supplier_type_table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: "{{ route('supplier_type') }}",

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'name', name: 'name'},

            { data: 'id', name: 'id', render:function(data, type, row, meta){

                return "<a href='javascript:void(0)' class='edit btn btn-primary btn-sm' onclick='supplier_type_edit("+meta.row+")' >এডিট</a> <a href='javascript:void(0)' class='edit btn btn-danger btn-sm' onclick='supplier_type_delete("+meta.row+")' >ডিলিট</a>"

            }},

        ]

    });



  });



</script>

<script type="text/javascript" src="{{ asset('public/admin/assets/js/admin.js') }}"></script>
@endsection