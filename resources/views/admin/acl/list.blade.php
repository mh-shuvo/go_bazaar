@extends("layouts.admin")
@section("title","Dashboard")
@section("content")

@if(session("success"))
<script>
    $(document).ready(function() {
        toastr.success('{{session("success")}}', 'Success');
    });

</script>
@endif

@if(session("error"))
<script>
    $(document).ready(function() {
        toastr.error('{{session("error")}}', 'Error');
    });
</script>
@endif

<!-- Start container-fluid -->
<div class="container-fluid">
    {{-- row start --}}
    <div class="row">
        <div class="col-9">
            <div>
                <h4 class="header-title mb-3">ACL List</h4>
            </div>
        </div>

        <div class="col-3">
            
            @if (permission_check('acl', 'create'))
            <a href="{{ route('acl.create') }}">
                <button type="button" class="btn btn-sm btn btn-success btn-bordered-success float-right AddEmployee">
                    <i class="ti-plus"></i> Add New
                </button>
            </a>
            @endif

        </div>

    </div>
    <!-- end row -->

    {{-- start table row --}}
    <div class="row">

        <div class="col-md-12">

            <div class="card-box">

                <br>

                <div class="">
                    <table
                        class="table table-bordered data-table table table-hover mails m-0 table table-actions-bar table-centered"
                        id="acl_report_tbl">

                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Role Name</th>
                                <th>Created On</th>
                                <th>Updated On</th>
                                <th width="100px">Action</th>
                            </tr>
                        </thead>

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

<script type="text/javascript" src="{{ asset('public/admin/assets/js/admin.js') }}"></script>

<script>
    
    var edit_permission = 0, delete_permission = 0;

    edit_permission = {{ permission_check('acl', 'edit') ? 1 : 0 }};
    delete_permission = {{ permission_check('acl', 'delete') ? 1 : 0 }};

    $(document).ready(function() {
        acl_report_list();
    });

</script>

@endsection