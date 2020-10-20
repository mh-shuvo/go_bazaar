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
            
            <a href="{{ route('admin.acl.create') }}">
                <button type="button" class="btn btn-sm btn btn-success btn-bordered-success float-right AddEmployee">
                    <i class="ti-plus"></i> Add New
                </button>
            </a>

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

<!-- <script type="text/javascript" src="{{ asset('public/admin/assets/js/admin.js') }}"></script> -->

<script>
    $(document).ready(function() {
        acl_report_list();
    });

    // ACL reporting

var acl_tbl;

function acl_report_list() {
    acl_tbl = $('#acl_report_tbl').DataTable({
        "dom": 'lBfrtip',
        "processing": true,
        "serverSide": true,
        // "lengthMenu": [25, 50, 75, 100],
        "ajax": {
            "url": "acl/list",
            "type": "GET",
            "data": {}
        },
        "columns": [
            {
                "data": null,
                render: function () {
                    return acl_tbl.page.info().start + acl_tbl.column(0).nodes().length;
                }
            },
            { "data": "role_name" },
            { "data": "created_at" },
            { "data": "updated_at" },
            {
                "data": null,
                render: function (data, type, row) {
                    var action = '<a href="acl/edit/' + data.id + '"><button class="btn btn-warning btn-xs"><i class="fa fa-edit"></i> Edit</button></a>';
                    
                    action += '<a href="javascript:void(0);"> <button class="btn btn-danger btn-xs" onclick="delete_acl(' + data.id + ')" ><i class="fas fa-trash"></i> Delete</button> </a>';

                    return action;
                }
            }
        ],
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
        "order": [
            [1, 'desc']
        ]
    });
}

// acl delete function
function delete_acl(acl_id) {
    swal.fire({
        title: "Confirmation",
        text: "Are you want to delete ?",
        type: "warning",
        showConfirmButton: true,
        confirmButtonClass: "btn-success",
        confirmButtonText: "Yes",
        closeOnConfirm: false,
        showCancelButton: true,
        cancelButtonText: "No"
    }).then(function (t) {
        if (t.value) {
            $.ajax({
                url: "acl/delete",
                type: "POST",
                dataType: "JSON",
                data: {
                    acl_id: acl_id
                },
                success: function (response) {
                    swal.fire({
                        title: "Response",
                        text: response.message,
                        type: response.status,
                        showCancelButton: false,
                        showConfirmButton: true,
                        closeOnConfirm: true,
                        allowEscapeKey: false
                    });

                    acl_tbl.ajax.reload();
                }
            });
        }
    });
}

</script>

@endsection