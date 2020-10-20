@extends("layouts.admin")
@section("title","ইউজার তালিকা")
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
                 <button type="button" class="btn btn-sm btn-info float-right waves-effect waves-light AddNewUser"> <i class="ti-plus"></i> নতুন ইউজার</button>
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
                <form class="form-validation" id="UserForm">
                <div class="modal-body">
                        <div class="form-group row">
                            <label for="upazila" class="col-md-4 form-control-label">জেলা</label>
                            <div class="col-md-7">
                                <select class="form-control" name="district_id" id="district_id" onchange="get_location(this.value, 3, 'upazila_id')" >
                                    <option>সিলেক্ট</option>

                                    @foreach($district_list as $item)
                                        <option value="{{ $item->id }}" >{{ $item->en_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="union" class="col-md-4 form-control-label">উপজেলা</label>
                            <div class="col-md-7">
                                 <select class="form-control" name="upazila_id" id="upazila_id">
                                        <option>Select</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="post" class="col-md-4 form-control-label">পদবী</label>
                            <div class="col-md-7">
                                 <input type="text" id="post" class="form-control" placeholder="পদবী লিখুন" name="post">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 form-control-label">নাম<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="name" class="form-control" name="name" id="name" placeholder="নাম">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-md-4 form-control-label">ইউজারনেম<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="username" id="user_name" placeholder="ইউজারনেম">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 form-control-label">পাসওয়ার্ড<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="password" class="form-control" name="password" id="user_password" placeholder="পাসওয়ার্ড">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-md-4 form-control-label">ই-মেইল<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="email" class="form-control" name="email" id="email" placeholder="ই-মেইল">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hori-pass1" class="col-md-4 form-control-label">মোবাইল<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input id="phone" name="phone" type="text" placeholder="মোবাইল" class="form-control">
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id">

                    <button type="submit" id="" class="btn btn-primary waves-effect waves-light">সাবমিট</button>
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


    var table = $('.data-table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: "{{route('admin.user_list')}}",

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'district_name', name: 'district_name'},

            {data: 'upazila_name', name: 'upazila_name'},

            {data: 'name', name: 'name'},

            {data: 'username', name: 'username'},

            {data: 'email', name: 'email'},

            { data: 'id', name: 'id', render:function(data, type, row, meta){

                return "<a href='javascript:void(0)' class='edit btn btn-primary btn-sm UserEdit' data-id='"+row.user_id+"'>সম্পাদন</a> <a href='javascript:void(0)' class='btn btn-danger btn-sm UserDelete' data-id='"+row.user_id+"'>ডিলিট</a>"

            }},

        ]

    });
  });

  $(document).on('click','.AddNewUser',function(){
    $("#UserForm")[0].reset();
    $("#id").val();
    $("#user_add_modal").modal('toggle');
  });
$(document).on('submit','#UserForm',function(e){
    e.preventDefault();
    var parameter = {
        'district_id':'required',
        'name':'required',
        'email':'required',
        'phone':'required',
        'post':'required',
        'user_name':'required',
    };
    let validate = validation(parameter);
    if(validate == false){
        $.ajax({
            url:"{{route('admin.user.store')}}",
            type:"POST",
            processData:false,
            contentType:false,
            data: new FormData(this),
            success:function(res){
                if(res.status == 'errors'){
                    let errors = res.data;

                    if(errors['district_id']){
                            toastr.error(errors['district_id'][0]);
                    }
                    if(errors['name']){
                            toastr.error(errors['name'][0]);
                    }
                    if(errors['email']){
                            toastr.error(errors['email'][0]);
                    }
                    if(errors['phone']){
                            toastr.error(errors['phone'][0]);
                    }
                    if(errors['post']){
                            toastr.error(errors['post'][0]);
                    }
                    if(errors['username']){
                            toastr.error(errors['username'][0]);
                    }
                }
                if(res.status == 'error'){
                    toastr.error(res.msg);
                }
                if(res.status == 'success'){
                    toastr.success(res.msg);
                    $('.data-table').DataTable().draw(true);
                    $("#UserForm")[0].reset();
                    $("#id").val();
                    $("#user_add_modal").modal('toggle');

                }
            },
        });
    }
    else{
        return;
    }
});
$(document).on('click','.UserEdit',function(){
    let id = $(this).data('id');
    $.ajax({
        url:"{{route('admin.user.edit')}}",
        type:"POST",
        data:{id:id},
        success:function(res){
            if(res.status == 'success'){
                $("#id").val(res.data.user_id);
                $("#name").val(res.data.name);
                $("#email").val(res.data.email);
                $("#phone").val(res.data.phone);
                $("#user_name").val(res.data.username);
                $("#post").val(res.data.post);
                $("#district_id").val(res.data.district_id);

                get_location(res.data.district_id, 3, 'upazila_id');

                setTimeout(function(){ 
                    $("#upazila_id").val(res.data.upazila_id);
                 }, 1000);

                $("#user_add_modal").modal('toggle');
            }
            else{
                toastr.error(res.msg);
            }
        }
    });
});
$(document).on('click','.UserDelete',function(){
    let id = $(this).data('id');
    Swal.fire({
    title: "আপনি কি নিশ্চিত?",
    text: "",
    type: "warning",
    showCancelButton: !0,
    confirmButtonColor: "#458bc4",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "হ্যা",
    cancelButtonText: "না",
  }).then(function (t) {
    if (t.value) {
        $.ajax({
        url:"{{route('admin.user.delete')}}",
        type:"POST",
        data:{id:id},
        success:function(res){
            if(res.status == 'success'){
                toastr.success(res.msg);
                $('.data-table').DataTable().draw(true);
            }
            else{
                toastr.error(res.msg);
            }
        }
    });
    }
  });
});
//get all_location
function get_location(parent_id, type, target_id) {
$.ajax({

    url: url + "/admin/get_location",
    type: "POST",
    dataType: "JSON",
    data: {
        parent_id: parent_id,
        type: type,
    },
    success: function (response) {

        if (parent_id != '') {

            if (response.status == 'success') {

                var list = "<option value=''>সিলেক্ট করুন</option>";

                response.data.forEach(function (item) {

                    list += "<option value='" + item.id + "'>" + item.bn_name + "</option>";

                });

                $("#" + target_id).html(list);

            } else {

                $("#" + target_id).html("<option value=''>Not Found</option>");
            }

        } else {

            $("#" + target_id).html("<option value=''>সিলেক্ট</option>");
        }

    }

});

}
</script>

@endsection