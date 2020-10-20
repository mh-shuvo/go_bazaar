@extends("layouts.admin")
@section("title","ই-কমার্স সেটআপ")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-8">
                <div>
                    <h4 class="header-title mb-3">ই-কমার্স সেটআপ</h4>
                </div>
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-primary btn-block" id="AddNewSetup">নতুন ই-কমার্স সেটআপ করুন</button>
            </div>
        </div>
        <!-- end row -->

            <div class="row">

            <div class="col-sm-12">

                <div class="card-box">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-inline row">
                                <div class="col-sm-3 offset-sm-4">
                                    <div class="form-group">
                                        <select class="form-control" id="filter_district" name="filter_district">
                                            <option value="">জেলা</option>
                                            @foreach($locations as $item)
                                            <option value="{{$item->id}}">{{$item->en_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-block btn-primary" onclick="filter_result()">সার্চ</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="">
                        <table class="setup_table table table-bordered contact-table table table-hover mails m-0 table table-actions-bar table-centered">

                            <thead>

                                <tr>

                                    <th>নং</th>
                                    <th>জেলা</th>
                                    <th>নাম</th>
                                    <th>লোগো</th>
                                    <th>ডোমেইন</th>
                                    <th>তারিখ</th>
                                    <th>একশন</th>

                                </tr>

                            </thead>

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end container-fluid -->
     <div id="AddNewSetupModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">ই-কমার্স সেটআপ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="form-validation" enctype="multipart/form-data" id="AddNewSetupModalForm" action="javascript:void(0)">
                <div class="modal-body">
                        <div class="form-group row">
                            <label for="upazila" class="col-md-4 form-control-label">জেলা<span class="text-danger">*</span>:</label>
                            <div class="col-md-8">
                                <select class="form-control" name="district_id" id="district_id" >
                                    <option value="">জেলা নির্বাচন করুন</option>
                                    @foreach($locations as $item)
                                    <option value="{{$item->id}}">{{$item->en_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 form-control-label">নাম<span class="text-danger">*</span>:</label>
                            <div class="col-md-8">
                                <input type="name" class="form-control" name="name" id="name" placeholder="নাম">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="domain" class="col-md-4 form-control-label">ডোমেইন<span class="text-danger">*</span>:</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                  <input type="text" class="form-control" name="domain" id="domain" placeholder="ডোমেইন">
                                  <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">gobazaar.com.bd</span>
                                  </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                             <label for="logo" class="col-md-4 form-control-label">logo<span class="text-danger">*</span>:</label>
                             <div class="col-md-8">
                                 <input id="logo" class="form-control" type="file" name="logo" />
                                 <img src="{{ asset('public/logo-black.png') }}" class="d-none image_preview"
                                        id="image_preview" style="height:50px; width:80px; border-radious: 5px;">
                             </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id">

                    <button type="submit" class="btn btn-primary waves-effect waves-light">সাবমিট</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">বাতিল</button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal-->
@endsection

@section('js')
<script type="text/javascript">

 $(function () {
    var url = $("meta[name = path]").attr("content");
    var table = $('.setup_table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: {
            url: "{{ route('eccomerce_setup') }}",
             data:function (e) { 
                e.district_id = $("#filter_district").val() || 0
             }
        },

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'district', name: 'district'},
            {data: 'name', name: 'name'},
            {data: 'logo', name: 'logo'},
            {data: 'domain', name: 'domain'},
            {data: 'created_at', name: 'created_at'},
            {name: 'action',data:'action'},
            
        ]

    });

  });
 function filter_result(){
    $('.setup_table').DataTable().draw(true);
 }
 $(document).on('click','#AddNewSetup',function(){
    if($(".field_error").length > 0){
        $(".field_error").css('display','none');
    }
    $("#AddNewSetupModalForm")[0].reset();
    $("#image_preview").addClass("d-none");
    $("#AddNewSetupModal").modal('toggle');
 });
 $(document).on('submit','#AddNewSetupModalForm',function(){
    let parameter = {
        'district_id' : 'required',
        'name' : 'required',
        'domain' : 'required',
    };
    if($('#id').val() == ''){
        parameter.logo = 'required';
    }
    let validate = validation(parameter);
     if(validate == false){
        $.ajax({
            url: "{{route('eccomerce_setup.store')}}",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success:function(res){
                if(res.status == 'success'){
                    toastr.success(res.msg);
                    $('.setup_table').DataTable().draw(true);
                    $("#AddNewSetupModalForm")[0].reset();
                    $("#AddNewSetupModal").modal('toggle');
                }
                if(res.status == 'error'){
                    toastr.error(res.msg);
                }
                if(res.status == 'errors'){
                    let error_data = res.data;
                    if(error_data['district_id']){
                        toastr.error(error_data['district_id'][0]);
                    }
                    if(error_data['name']){
                        toastr.error(error_data['name'][0]);
                    }
                    if(error_data['domain']){
                        toastr.error(error_data['domain'][0]);
                    }
                    if(error_data['logo']){
                        toastr.error(error_data['logo'][0]);
                    }
                }
            }
        });
    }
 });
 $(document).on('keyup','#domain',function(){
    this.value = this.value.toLowerCase()
 })

$(document).on('click','.SetupEdit',function(){
    let row_id = $(this).data('id');
    $.ajax({
        url:'{{route("eccomerce_setup.edit")}}',
        type:'POST',
        data:{
            id: row_id
        },
        success:function(res){
            if(res.status == 'success'){
                toastr.success(res.msg);
                $("#id").val(res.data.id)
                $("#district_id").val(res.data.district_id)
                $("#name").val(res.data.name)
                $("#domain").val(res.data.domain)
                $("#image_preview").addClass("d-none");
                $("#image_preview").attr('src',"{{asset('public/logo')}}/"+res.data.logo);
                $("#image_preview").removeClass("d-none");
                $("#AddNewSetupModal").modal('toggle');
            }
            else{
                 toastr.error(res.msg);
            }
        }
    }) ;
});


$(document).on("click", ".SetupDelete", function () {
  let id = $(this).data("id");
  Swal.fire({
    title: "আপনি কি নিশ্চিত?",
    text: "আপনার নির্বাচিত ই-কমার্স টি ডিলিট করতে কি আপনি নিশ্চিত?",
    type: "warning",
    showCancelButton: !0,
    confirmButtonColor: "#458bc4",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "হ্যা",
    cancelButtonText: "না",
  }).then(function (t) {
    if (t.value) {
      $.ajax({
        url: "{{route('eccomerce_setup.delete')}}",
        type: "POST",
        dataType: "JSON",
        data: {
          id: id,
        },
        success: function (res) {
          if(res.status == 'success'){
                    toastr.success(res.msg);
                    $('.setup_table').DataTable().draw(true);
                }
                if(res.status == 'error'){
                    toastr.error(res.msg);
                }
        },
      });
    }
  });
});



 $(document).on("change", "#logo", function () {
      $("#image_preview").addClass("d-none");
      readURL(this, "#image_preview" );
      $("#image_preview").removeClass("d-none");
});

</script>
@endsection