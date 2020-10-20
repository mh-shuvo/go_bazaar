@extends("layouts.admin")
@section("title","ব্যয় বিভাগ তালিকা")
@section("content")
<!-- Start container-fluid -->
<div class="container-fluid">
    {{-- row start --}}
    <div class="row">
        <div class="col-9">
            <div>
                <h4 class="header-title mb-3">ব্যয় ক্যাটাগরির তালিকা</h4>
            </div>
        </div>
        <div class="col-3">
            @if (permission_check('expanse', 'category', 'create'))
            <button type="button" class="btn btn-sm btn-teal btn-bordered-success float-right  mb-3 AddAccountHead"> <i
                    class="ti-plus"></i> নতুন যোগ করুন</button>
            @endif
        </div>
    </div>
    <!-- end row -->

    {{-- start table row --}}
    <div class="row">

        <div class="col-sm-12">

            <div class="card-box">
                <div class="">
                    <table
                        class="table table-bordered data-table table table-hover mails m-0 table table-actions-bar table-centered category_table"
                        style="width: 100%;">

                        <thead>

                            <tr>

                                <th>নং</th>
                                <th>নাম</th>
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
<div id="AddAccountHeadModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">ব্যয় ক্যাটেগরি</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form class="form-validation" id="AddAccountHeadForm" action="javascript:void(0)">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="form-control-label">নাম<span class="text-danger">*</span></label>
                        <input type="name" class="form-control" name="name" id="name" placeholder="ক্যাটেগরি নাম লিখুন">

                        <span class="text-danger" id="name_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id">

                    <button type="submit" id="category_save_button"
                        class="btn btn-primary waves-effect waves-light">সাবমিট</button>
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

var edit_permission = 0, delete_permission = 0;

edit_permission = {{ permission_check('expanse', 'category', 'edit') ? 1 : 0 }};
delete_permission = {{ permission_check('expanse', 'category', 'delete') ? 1 : 0 }};

$(function () {

    var category_table = $('.category_table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: "{{ route('expense.account_head') }}",

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'name', name: 'name'},
            

            { data: 'id', name: 'id', render:function(data, type, row, meta){

                var action = edit_permission ? "<a href='javascript:void(0)' class='btn btn-primary btn-sm EditAccountHead' data-row='"+meta.row+"'>সম্পাদন</a>" : ""; 
                
                action += delete_permission ? "<a href='javascript:void(0)' class='btn btn-danger btn-sm DeleteAccountHead' data-row='"+meta.row+"'>বাতিল</a>" : "";

                return action;

            }},

        ]

    });



  });



</script>

<script type="text/javascript" src="{{ asset('public/admin/assets/js/expense.js') }}"></script>
@endsection