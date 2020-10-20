@extends("layouts.admin")
@section("title","Dashboard")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}


        <div class="row">

            <div class="col-9">
                <div>
                    <h4 class="header-title mb-3">সরবরাহকারীর তালিকা</h4>
                </div>
            </div>
            <div class="col-3">

                 <a href="{{ route('supplier_add') }}" > <button type="button" class="btn btn-sm btn btn-success btn-bordered-success float-right" > <i class="ti-plus"></i> নতুন যোগ করুন</button></a>
            </div>
        </div>
        <!-- end row -->

        {{-- start table row --}}
        <div class="row">

            <div class="col-sm-12">

                @if ($message = Session::get('success'))

                    <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
                      <strong>{{ $message }}</strong>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>

                @endif

                <div class="card-box">

                    <div class="row">
                        <div class="col-sm-2 offset-sm-2">
                            <div class="form-group">
                                <label class="control-label">জেলা</label>
                                 <select type="text" name="district_id" id="district_id" class="form-control" onchange="get_location(this.value, 3, 'upazila_id')" >
                                    <option value="">সিলেক্ট</option>
                                    @foreach($data as $item)
                                    <option value="{{ $item->id }}">{{ $item->en_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">উপজেলা</label>
                                 <select type="text" name="upazila_id" id="upazila_id" class="form-control"> <option value="">সিলেক্ট</option></select>
                            </div>  
                        </div>

                        <div class="col-sm-2">
                           <div class="form-group">
                               <label class="control-label">স্ট্যাটাস</label>
                               <select class="form-control" id="status" name="status" >
                                <option value="">সিলেক্ট</option>
                                <option value="0">পেনডিং</option>
                                <option value="1">এপ্রুভড</option>
                                <option value="2">ইনএকটিভ</option>
                            </select>
                           </div>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-teal btn-block" style="margin-top: 32px;" onclick="supplier_filter()"> <i class="fa fa-search"></i> সার্চ</button>
                        </div>
                    </div><br>

                    <div class="">
                        <table class="table table-bordered data-table table table-hover mails m-0 table table-actions-bar table-centered supplier_table" id="supplier_type_table">

                            <thead>

                                <tr>

                                    <th>নং</th>
                                    <th>জেলা</th>
                                    <th>উপজেলা</th>
                                    <th>নাম</th>
                                    <th>দোকানের নাম</th>
                                    <th>ইউজারনেম</th>
                                    <th>মোবাইল</th>
                                    <th>ই-মেইল</th>
                                    <th>ঠিকানা</th>
                                    <th>স্ট্যাটাস</th>
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

@endsection

@section('js')
<script type="text/javascript">

  $(function () {


    var supplier_table = $('.supplier_table').DataTable({

       scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: {

          url: "{{ route('suppliers') }}",

          data: function (e) {

                e.upazila_id = $('#upazila_id').val(),
                e.district_id = $('#district_id').val()
                e.status = $('#status').val()

            }

        },


        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'district_name', name: 'district_name'},
            {data: 'upazila_name', name: 'upazila_name'},
            {data: 'name', name: 'name'},
            {data: 'shop_name', name: 'shop_name'},
            {data: 'username', name: 'username'},
            {data: 'mobile', name: 'mobile'},
            {data: 'email', name: 'email'},
            {data: 'address', name: 'address'},

            {data: 'status', name: 'status', render:function(data, type, row, meta){

                if(row.status == 0){
                    return '<span class="badge badge-primary">পেনডিং</span>';
                }else if(row.status == 1){
                    return '<span class="badge badge-teal">অনুমোদিত</span>';
                }else{
                    return '<span class="badge badge-danger">ইনএকটিভ</span>';
                }

            }},

            { data: 'id', name: 'id', render:function(data, type, row, meta){

                let html =  "<a href='"+url+"/admin/supplier_edit/"+row.id+"' class='edit btn btn-primary btn-sm' >এডিট</a> <a href='javascript:void(0)' class='edit btn btn-danger btn-sm' onclick='supplier_delete("+meta.row+")' >ডিলিট</a>";

                if(row.status == 0){
                    html += "<a href='javascript:void(0)' class='btn btn-teal btn-sm supplierStatusChange' data-status='1' data-id='"+row.id+"'>এপ্রুভড</a>";
                    html += "<a href='javascript:void(0)' class='btn btn-warning btn-sm supplierStatusChange' data-status='2' data-id='"+row.id+"'>ইনএকটিভ </a>";
                }
                if(row.status == 1){
                     html += "<a href='javascript:void(0)' class='btn btn-warning btn-sm supplierStatusChange' data-status='2' data-id='"+row.id+"'>ইনএকটিভ </a>";
                        html += "<a href='./impersonate/"+row.user_id+"' class='btn btn-info btn-sm'>লগইন করুন</a>";
                }
                if(row.status == 2){
                    html += "<a href='javascript:void(0)' class='btn btn-teal btn-sm supplierStatusChange' data-status='1' data-id='"+row.id+"'>একটিভ </a>";
                }


                return html;

            }},

        ]

    });

  });

//custom filtering
function supplier_filter(){

    $(".supplier_table").DataTable().draw(true);

}
$.ajaxSetup({
    headers:{
        "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
    }
})

$(document).on('click','.supplierStatusChange',function(){
    let status = $(this).data('status');
    let supplier_id = $(this).data('id');

    $.ajax({
        url:"{{route('supplier.status_change')}}",
        method: "POST",
        dataType: "JSON",
        data:{
            status: status,
            id: supplier_id
        },
        success:function(res){
            Swal.fire(res.status,res.msg,res.status);
            $(".supplier_table").DataTable().draw(true);
        }
    });

});

$("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});

</script>

<script type="text/javascript" src="{{ asset('public/admin/assets/js/admin.js') }}"></script>
@endsection