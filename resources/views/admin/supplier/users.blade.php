@extends("layouts.admin")
@section("title","Dashboard")
@section("content")
<!-- Start container-fluid -->
<div class="container-fluid">
    {{-- row start --}}
    <div class="row">
        <div class="col-9">
            <div>
                <h4 class="header-title mb-3">কর্মীগণের তালিকা</h4>
            </div>
        </div>
        <div class="col-3">
            @if (permission_check('employee', 'create'))
            <button type="button" class="btn btn-sm btn btn-success btn-bordered-success float-right AddEmployee"> <i
                class="ti-plus"></i>
            নতুন যোগ করুন</button>
            @endif
            
        </div>
    </div>
    <!-- end row -->

    {{-- start table row --}}
    <div class="row">

        <div class="col-md-12">


            <div class="">
                <table
                    class="table table-bordered data-table table table-hover mails m-0 table table-actions-bar table-centered users_table"
                    id="">

                    <thead>

                        <tr>

                            <th>নং</th>
                            <th>জেলা</th>
                            <th>উপজেলা</th>
                            <th>পারমিশন</th>
                            <th>নাম</th>
                            <th>ইউজারনেম</th>
                            <th>মোবাইল</th>
                            <th>ই-মেইল</th>
                            <th>ঠিকানা</th>
                            <th width="100px">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    </tbody>

                </table>
            </div>
            {{-- </div> --}}
        </div>
    </div>
    <!-- end row -->

</div>
<!-- end container-fluid -->

<div id="AddEmployeeModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">কর্মীর তথ্য</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form class="form-validation" id="AddEmployeeForm" action="javascript:void(0)" method="post"
                enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="form-group row">

                        <label for="upazila_id" class="col-md-2 form-control-label">জেলা</label>
                        <div class="col-md-4">
                            {{-- required parsley-type="upazila_id" --}}
                            <select type='text' class="form-control" id="districtId" name="district_id"
                                onchange="get_location(this.value, 3, 'upazilaId')">
                                <option value="">সিলেক্ট</option>

                                @foreach($locations as $item)
                                <option value="{{ $item->id }}"
                                    {{ (old('district_id') == $item->id) ? 'selected="selected"' : '' }}>
                                    {{ $item->en_name }}</option>
                                @endforeach

                            </select>

                            <span class="text-danger" id="upazilaId_error"></span>

                        </div>

                        <label for="union_id" class="col-md-2 form-control-label">উপজেলা</label>
                        <div class="col-md-4">
                            <select type='text' class="form-control" id="upazilaId" name="upazilaId">
                                <option value="">সিলেক্ট</option>
                            </select>

                            <span class="text-danger" id="upazilaId_error"></span>
                        </div>

                    </div>

                    <div class="form-group row">

                        <label for="name" class="col-md-2 form-control-label">নাম<span
                                class="text-danger">*</span></label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="name" id="name" placeholder="নাম প্রদান করুন">
                            <span class="text-danger" id="name_error"></span>
                            {{-- required parsley-type="name" --}}
                        </div>

                        <label for="mobile" class="col-md-2 form-control-label">মোবাইল<span
                                class="text-danger">*</span></label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="mobile" id="mobile"
                                placeholder="মোবাইল নাম্বার প্রদান করুন">
                            <span class="text-danger" id="mobile_error"></span>
                        </div>

                    </div>
                    <div class="form-group row">

                        <label for="inputEmail3" class="col-md-2 form-control-label">ই-মেইল</label>
                        <div class="col-md-4">
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="ই-মেইল প্রদান করুন">
                        </div>

                        <label for="nid" class="col-md-2 form-control-label">জন্ম নিবন্ধন/জাতীয় পরিচয়পত্র নং<span
                                class="text-danger">*</span></label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="nid" id="nid"
                                placeholder="জন্ম নিবন্ধন/জাতীয় পরিচয়পত্র নং">
                            <span class="text-danger" id="nid_error" required></span>
                        </div>

                    </div>

                    <div class="form-group row">
                        <label for="role_id" class="col-md-2 form-control-label">পারমিশন<span
                                class="text-danger">*</span></label>

                        <div class="col-md-4">
                            <select class="form-control" name="role_id" id="role_id">
                                <option value="">নির্বাচন করুণ</option>

                                @foreach($acl_data as $value)
                                <option value="{{ $value->id }}">{{ $value->role_name }}</option>
                                @endforeach

                            </select>
                            <span class="text-danger" id="role_id_error"></span>
                        </div>

                        <label for="address" class="col-md-2 form-control-label">ঠিকানা<span
                                class="text-danger">*</span></label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="address" id="address"
                                placeholder="ঠিকানা প্রদান করুন">
                            <span class="text-danger" id="address_error"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="userName" class="col-md-2 form-control-label">ইউজারনেম<span
                                class="text-danger">*</span></label>

                        <div class="col-md-4">
                            <input type="text" name="username" id="userName" placeholder="ইউজারনেম প্রদান করুন"
                                class="form-control">
                            <span class="text-danger" id="username_error"></span>
                        </div>

                        <label for="passWord" class="col-md-2 form-control-label">পাসওয়ার্ড<span
                                class="text-danger">*</span></label>

                        <div class="col-md-4">
                            <input type="password" name="password" id="passWord" placeholder="Password"
                                class="form-control">
                            <span class="text-danger" id="password_error"></span>
                        </div>

                    </div>

                </div>
                <div class="modal-footer text-center" style="text-align: center;">
                    <input type="hidden" name="row_id" id="row_id">
                    <input type="hidden" name="user_id" id="user_id">

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
    
    edit_permission = {{ permission_check('employee', 'edit') ? 1 : 0 }};
    delete_permission = {{ permission_check('employee', 'delete') ? 1 : 0 }};

    $(function() {

        var users_table = $('.users_table').DataTable({

            scrollCollapse: true
            , autoWidth: true
            , responsive: true
            , serverSide: true
            , processing: true,

            ajax: {

                url: "{{ route('supplier.employe') }}",

                data: function(e) {

                    e.user_type = $('#filter_user_type').val()

                }

            },


            columns: [

                {
                    data: 'DT_RowIndex'
                    ,name: 'DT_RowIndex'
                },
                 {
                    data: 'district_name'
                    , name: 'district_name'
                },

                {
                    data: 'upazila_name'
                    , name: 'upazila_name'
                }
                
                , {
                    data: 'role_name'
                }
                , {
                    data: 'name'
                    , name: 'name'
                }
                , {
                    data: 'username'
                    , name: 'username'
                }
                , {
                    data: 'mobile'
                    , name: 'mobile'
                }
                , {
                    data: 'email'
                    , name: 'email'
                }
                , {
                    data: 'address'
                    , name: 'address'
                },

                {
                    data: 'id'
                    , name: 'id'
                    , render: function(data, type, row, meta) {

                        var action = edit_permission ? "<a href='javascript:void(0)' class='edit btn btn-primary btn-sm EditUser' data-row='" + meta.row + "' >সম্পাদন</a>" : '';
                        
                        action += delete_permission ? "<a href='javascript:void(0)' class='btn btn-danger btn-sm DeleteUser' data-row='" + meta.row + "'>বাতিল</a>" : '';

                        return action;

                    }
                },

            ]

        });

    });

</script>

<script type="text/javascript" src="{{ asset('public/admin/assets/js/admin.js') }}"></script>
@endsection