@extends("layouts.admin")
@section("title","Dashboard")
@section("content")
<!-- Start container-fluid -->
<div class="container-fluid">
    {{-- row start --}}
    <div class="row">
        <div class="col-9">
            <div>
                <h4 class="header-title mb-3">ডেলিভারি ম্যান তালিকা</h4>
            </div>
        </div>
        <div class="col-3">
            
            @if (permission_check('delivery', 'create'))
            <a href="javascript:void(0)">
                <button type="button" class="btn btn-sm btn btn-success btn-bordered-success float-right deliveryman"
                    onclick="add_deliveryman()"> <i class="ti-plus"></i> নতুন যোগ করুন</button>
            </a>
            @endif

        </div>
    </div>
    <!-- end row -->

    {{-- start table row --}}
    <div class="row">

        <div class="col-md-12">

            <div class="card-box">

                <div class="row">
                    <div class="col-md-12">
                        <form class="form-inline">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="upazila_id">উপজেলা</label>
                                    <select name="upazila_id" id="upazila_id" class="form-control"
                                        onchange="get_location(this.value, 2, 'union_id')">
                                        <option value="">সিলেক্ট</option>
                                        @foreach($data as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="union_id">ইউনিয়ন</label>
                                    <select name="union_id" id="union_id" class="form-control">
                                        <option value="">সিলেক্ট</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <button type="button" class="btn btn-sm btn-primary"
                                        onclick="deliveryman_filter()">সার্চ</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br>

                <div class="">
                    <table class="table table-bordered deliveryman_table">

                        <thead>

                            <tr>

                                <th>নং</th>
                                <th>উপজেলা</th>
                                <th>ইউনিয়ন</th>
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
            </div>
        </div>
    </div>
    <!-- end row -->

</div>
<!-- end container-fluid -->

<div id="deliveryman_modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">ডেলিভারি ম্যান তথ্য</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <form class="form-validation" action="javascript:void(0)" method="post" enctype="multipart/form-data">

                    <div class="form-group row">

                        <label for="upazila_id" class="col-md-2 form-control-label">উপজেলা</label>
                        <div class="col-md-4">
                            {{-- required parsley-type="upazila_id" --}}
                            <select type='text' class="form-control" id="upazilaId" name="upazila_id"
                                onchange="get_location(this.value, 2, 'unionId')">
                                <option value="">সিলেক্ট</option>

                                @foreach($data as $item)
                                <option value="{{ $item->id }}"
                                    {{ (old('upazila_id') == $item->id) ? 'selected="selected"' : '' }}>
                                    {{ $item->name }}</option>
                                @endforeach

                            </select>

                            <span class="text-danger" id="upazilaId_error"></span>

                        </div>

                        <label for="union_id" class="col-md-2 form-control-label">ইউনিয়ন</label>
                        <div class="col-md-4">
                            <select type='text' class="form-control" id="unionId" name="union_id">
                                <option value="">সিলেক্ট</option>
                            </select>

                            <span class="text-danger" id="unionId_error"></span>
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
                        <label for="address" class="col-md-2 form-control-label">ঠিকানা<span
                                class="text-danger">*</span></label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="address" id="address"
                                placeholder="ঠিকানা প্রদান করুন">
                            <span class="text-danger" id="address_error"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="userName" class="col-md-2 form-control-label">ইউজারনেম<span
                                class="text-danger">*</span></label>

                        <div class="col-md-4">
                            <input type="text" name="userName" id="userName" placeholder="ইউজারনেম প্রদান করুন"
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


                </form>

            </div>
            <div class="modal-footer text-center" style="text-align: center;">
                <input type="hidden" name="row_id" id="row_id">
                <input type="hidden" name="user_id" id="user_id">

                <button type="button" id="deliveryman_save_button" class="btn btn-primary waves-effect waves-light"
                    onclick="deliveryman_store()">সাবমিট</button>
                <button type="button" id="deliveryman_update_button" class="btn btn-warning waves-effect waves-light"
                    onclick="deliveryman_update()">আপডেট</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">বাতিল</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

@section('js')
<script type="text/javascript">

var edit_permission = 0, delete_permission = 0;

edit_permission = {{ permission_check('delivery', 'edit') ? 1 : 0 }};
delete_permission = {{ permission_check('delivery', 'delete') ? 1 : 0 }};

$(function() {
        var deliveryman_table = $('.deliveryman_table').DataTable({

            scrollCollapse: true
            , autoWidth: false
            , responsive: true
            , serverSide: true
            , processing: true,

            ajax: {

                url: "{{ route('deliverymans') }}",

                data: function(e) {

                    e.upazila_id = $('#upazila_id').val()
                        , e.union_id = $('#union_id').val()

                }

            },


            columns: [

                {
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
                },

                {
                    data: 'upazila_name'
                    , name: 'upazila_name'
                }
                , {
                    data: 'union_name'
                    , name: 'union_name'
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

                        var action = edit_permission ? "<a href='javascript:void(0)' class='edit btn btn-primary btn-sm' onclick='deliveryman_edit(" + meta.row + ")' >সম্পাদন</a>" : '';
                        
                        action += delete_permission ? "<a href='javascript:void(0)' class='edit btn btn-danger btn-sm' onclick='deliveryman_delete(" + meta.row + ")' >বাতিল</a>" : '';

                        return action;

                    }
                },

            ]

        });

    });

    //custom filtering
    function deliveryman_filter() {

        $(".deliveryman_table").DataTable().draw(true);

    }

</script>

<script type="text/javascript" src="{{ asset('public/admin/assets/js/admin.js') }}"></script>
@endsection