@extends("layouts.admin")
@section("title","Dashboard")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">ইউজার তালিকা</h4>
                </div>
            </div>
        </div>
        <!-- end row -->

        {{-- start table row --}}
        <div class="row">

            <div class="col-sm-12">
                {{-- data-target="#user_add_modal" --}}
                 <button type="button" class="btn btn-sm btn-info float-right waves-effect waves-light" data-toggle="modal"  onclick="add_user()" > <i class="ti-plus"></i> নতুন ইউজার</button>
            </div>

            <div class="col-sm-12">

                <div class="card-box">
                    {{-- <h5 class="mt-0 font-14 mb-3">Users</h5> --}}
                    <div class="">
                        <table class="table table-bordered data-table table table-hover mails m-0 table table-actions-bar table-centered">

                            <thead>

                                <tr>

                                    <th>নং</th>

                                    <th>জেলা</th>

                                    <th>উপজেলা</th>

                                    <th>নাম</th>

                                    <th>ইউজারনেম</th>

                                    <th>ই-মেইল</th>

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
    <div id="user_add_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">ইউজারের তথ্য</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-validation">
                        <div class="form-group row">
                            <label for="upazila" class="col-md-4 form-control-label">জেলা</label>
                            <div class="col-md-7">
                                <select class="form-control"  name="district_id" id="district_id" onchange="get_location(this.value, 3, 'upazila_id')" >
                                    <option>সিলেক্ট</option>

                                    @foreach($upazila_list as $item)
                                        <option value="{{ $item->id }}" >{{ $item->en_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="union" class="col-md-4 form-control-label">উপজেলা</label>
                            <div class="col-md-7">
                                 <select class="form-control"  name="upazila_id" id="upazila_id">
                                        <option>Select</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 form-control-label">নাম<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="name" required parsley-type="name" class="form-control" name="name" id="name" placeholder="নাম">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-md-4 form-control-label">ইউজারনেম<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="text" required parsley-type="username" class="form-control" name="username" id="username" placeholder="ইউজারনেম">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 form-control-label">পাসওয়ার্ড<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="password" required parsley-type="password" class="form-control" name="password" id="password" placeholder="পাসওয়ার্ড">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-md-4 form-control-label">ই-মেইল<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="email" required parsley-type="email" class="form-control" name="email" id="email" placeholder="ই-মেইল">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hori-pass1" class="col-md-4 form-control-label">মোবাইল<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input id="mobile" name="mobile" type="text" placeholder="মোবাইল" required class="form-control">
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="row_id" id="row_id">

                    <button type="button" id="save_button" class="btn btn-primary waves-effect waves-light" onclick="user_store()" >সাবমিট</button>
                    <button type="button" id="update_button" class="btn btn-warning waves-effect waves-light" onclick="user_update()" >আপডেট</button>
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


    var table = $('.data-table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: "{{ route('users.index') }}",

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'district_name', name: 'district_name'},

            {data: 'upazila_name', name: 'upazila_name'},

            {data: 'name', name: 'name'},

            {data: 'username', name: 'username'},

            {data: 'email', name: 'email'},

            // {data: 'action', name: 'action', orderable: false, searchable: false},

            { data: 'id', name: 'id', render:function(data, type, row, meta){

                return "<a href='javascript:void(0)' class='edit btn btn-primary btn-sm' onclick='edit_user("+meta.row+")' >Edit</a> <a href='javascript:void(0)' class='edit btn btn-danger btn-sm' onclick='user_delete("+meta.row+")' >Delete</a>"

            }},

        ]

    });



  });

</script>

<script type="text/javascript" src="{{ asset('public/admin/assets/js/admin.js') }}"></script>
@endsection