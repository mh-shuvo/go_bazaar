@extends("layouts.admin")
@section("title","Client List")
@section("content")
<!-- Start container-fluid -->
<div class="container-fluid">
    <div class="row">
        <div class="col-9">
            <div>
                <h4 class="header-title mb-3">কাষ্টমারের তালিকা</h4>
            </div>
        </div>
        <div class="col-3">

            @if (permission_check("customer", "create"))
            <a href="javascript:void(0)" class="btn btn-primary AddClient">কাষ্টমার যুক্ত করুণ</a>
            @endif

        </div>
        <div class="col-12">
        </div>
    </div>
    <!-- end row -->

    {{-- start table row --}}
    <div class="row">

        <div class="col-sm-12">

            <div class="card-box">

                <div class="row">
                    <div class="col-sm-2"></div>
                    <label class="col-sm-1">জেলা</label>
                    <select type="text" name="district_id" id="district_id" class="form-control col-sm-2"
                        onchange="get_location(this.value, 3, 'upazila_id')">
                        <option value="">সিলেক্ট</option>
                        @foreach($data as $item)
                        <option value="{{ $item->id }}">{{ $item->en_name }}</option>
                        @endforeach
                    </select>

                    <label class="col-sm-1">উপজেলা</label>
                    <select type="text" name="upazila_id" id="upazila_id" class="form-control col-sm-2">
                        <option value="">সিলেক্ট</option>

                    </select>&nbsp;&nbsp;&nbsp;

                    <button class="btn btn-sm btn-primary col-sm-1" onclick="client_filter()">সার্চ</button>
                </div><br>

                <div class="">
                    <table
                        class="table table-bordered data-table table table-hover mails m-0 table table-actions-bar table-centered client_table"
                        id="client_table">

                        <thead>

                            <tr>

                                <th>নং</th>
                                <th>জেলা</th>
                                <th>উপজেলা</th>
                                <th>নাম</th>
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
<div id="AddClientModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">কাষ্টমার যুক্ত করুন</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form class="AddClientForm" id="AddClientForm" action="javascript:void(0)" enctype="multipart/form-data"
                method="POST">
                <input type="hidden" id="id" name="id">
                <div class="alert d-none" id="msg_div">
                    <span id="res_message"></span>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">নাম:</label>
                        <div class="col-md-9">
                            <input name="name" id="name" type="text" class="form-control" placeholder="নাম">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">মোবাইল:</label>
                        <div class="col-md-9">
                            <input name="mobile" id="mobile" type="text" class="form-control" placeholder="মোবাইল">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">পাসওয়ার্ড:</label>
                        <div class="col-md-9">
                            <input name="password" id="password_" type="password" class="form-control"
                                placeholder="********">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">কনফার্ম পাসওয়ার্ড:</label>
                        <div class="col-md-9">
                            <input name="confirm_password" id="confirm_password_" type="password" class="form-control"
                                placeholder="********">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect waves-light"
                        id="submit_btn">সাবমিট</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">বাতিল</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection

@section('js')
<script type="text/javascript">
    var delete_permission = 0;

delete_permission = {{ permission_check("customer", "delete") ? 1 : 0 }};

  $(function () {


    var client_table = $('.client_table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: {

          url: "{{ route('clients') }}",

          data: function (e) {

                e.upazila_id = $('#upazila_id').val(),
                e.district_id = $('#district_id').val(),
                e.supplier_id = "{{Auth::user()->record_id}}"

            }

        },


        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'district_name', name: 'district_name'},
            {data: 'upazila_name', name: 'upazila_name'},
            {data: 'name', name: 'name'},
            {data: 'mobile', name: 'mobile'},
            {data: 'email', name: 'email'},
            {data: 'address', name: 'address'},

            { data: 'id', name: 'id', render:function(data, type, row, meta){

                return delete_permission ? "<a href='javascript:void(0)' class='edit btn btn-danger btn-sm' onclick='customer_delete("+meta.row+")' >ডিলিট</a>" : '';

            }},

        ]

    });

  });

//custom filtering
function client_filter(){

    $(".client_table").DataTable().draw(true);

}

//customer delete
function customer_delete(row_index){

    var row_data =   $(".client_table").DataTable().row(row_index).data();

     Swal.fire({
      type:'warning',
      title: 'আপনি কি ডিলিট করতে চান ?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'হ্যাঁ',
      cancelButtonText: 'না',
    }).then((result) => {
      if (result.value) {

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });

        $.ajax({
                url: url + '/admin/client_delete',
                type: "POST",
                dataType: "JSON",
                data: {
                    id :row_data.id,
                    user_id :row_data.user_id,

                },
                success: function(response) {

                    $(".client_table").DataTable().draw(true);

                     var text = (response.status == 'success') ? "ধন্যবাদ!" : "দুঃখিত!";

                     Swal.fire(
                       text,
                       response.message,
                       'success'
                    )
                }
            });
      }
    })
}

$(document).on('click','.AddClient',function(){
    document.getElementById('AddClientForm').reset();
    $("#AddClientModal").modal('toggle');
});
$(document).on('submit','.AddClientForm',function(){
    let parameter = {
        'name' : 'required',
        'mobile' : 'required',
        'password_' : 'required',
        'confirm_password_' : 'required'
    };
    var validate = validation(parameter);
    if (validate == false) {
        $.ajax({
            url: "{{route('supplier.client.store')}}",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
             if(response.status == 'success'){
                Swal.fire('সফল',response.message,response.status);
                document.getElementById('AddClientForm').reset();
                $(".client_table").DataTable().draw(true);
                 $("#AddClientModal").modal('hide');
             }
             else{
                Swal.fire('দুঃখিত',response.message,response.status);
             }
            }
        });
    }else{
        return false;
    }
});

</script>

@endsection