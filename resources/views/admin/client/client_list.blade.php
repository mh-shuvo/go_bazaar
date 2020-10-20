@extends("layouts.admin")
@section("title","Dashboard")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">কাষ্টমারের তালিকা</h4>
                </div>
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
                        <select type="text" name="district_id" id="district_id" class="form-control col-sm-2" onchange="get_location(this.value, 3, 'upazila_id')" >
                            <option value="">সিলেক্ট</option>
                            @foreach($data as $item)
                            <option value="{{ $item->id }}">{{ $item->en_name }}</option>
                            @endforeach
                        </select>

                        <label class="col-sm-1">উপজেলা</label>
                        <select type="text" name="upazila_id" id="upazila_id" class="form-control col-sm-2">
                            <option value="">সিলেক্ট</option>

                        </select>&nbsp;&nbsp;&nbsp;

                        <button class="btn btn-sm btn-primary col-sm-1" onclick="client_filter()" >সার্চ</button>
                    </div><br>

                    <div class="">
                        <table class="table table-bordered data-table table table-hover mails m-0 table table-actions-bar table-centered client_table" id="client_table">

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

@endsection

@section('js')
<script type="text/javascript">

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
                e.district_id = $('#district_id').val()

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

                return "<a href='javascript:void(0)' class='edit btn btn-danger btn-sm' onclick='customer_delete("+meta.row+")' >ডিলিট</a>"

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



</script>

<script type="text/javascript" src="{{ asset('public/admin/assets/js/admin.js') }}"></script>
@endsection