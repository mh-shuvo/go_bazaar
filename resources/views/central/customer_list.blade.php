@extends("layouts.admin")
@section("title","কাস্টমার রিপোর্ট")
@section("content")
 <!-- Start container-fluid -->
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div>
                                <h4 class="header-title mb-3">কাস্টমার রিপোর্ট </h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            @if(empty(Auth::user()->upazila_id) && !empty(Auth::user()->district_id))
                            <div class="form-group">
                                <label for="" class="control-label">জেলা</label>
                                <select name="district_id" id="district_id" class="form-control" onchange="get_location(this.value, 3, 'upazila_id')" disabled >
                                    <option value="">জেলা নির্বাচন করুন</option>
                                    @foreach($districts as $value)
                                    <option value="{{$value->id}}" {{Auth::user()->district_id == $value->id ? 'Selected':''}}>{{$value->en_name}}</option>
                                    @endforeach

                                </select>
                            </div>
                            @endif
                        </div>

                        @if(empty(Auth::user()->upazila_id) && !empty(Auth::user()->district_id))
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="" class="control-label">উপজেলা</label>
                                <select name="upazila_id" id="upazila_id" class="form-control">
                                    <option value="">উপজেলা নির্বাচন করুন</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <button class="btn btn-primary btn-sm btn-block SearchCustomer" style="margin-top: 33px;"> <i class="fa fa-search"></i> খুজুন</button>
                        </div>
                        @endif
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-12">
                            <div>
                                <div class="card-box widget-inline">
                                    <div class="row">
                                        <div class="col-sm-12">
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
                    </div>
                    <!--end row -->
                </div>
                <!-- end container-fluid -->
@endsection

@section('js')
<script>
  $(function () {
    let district_id = '{{Auth::user()->district_id}}';
    get_location(district_id, 3, 'upazila_id');
var client_table = $('.client_table').DataTable({

    scrollCollapse: true,
    autoWidth: false,
    responsive: true,
    serverSide: true,
    processing: true,

    ajax: {

      url: "{{ route('central.customer_list') }}",

      data: function (e) {

            e.upazila_id = $('#upazila_id').val(),
            e.union_id = $('#union_id').val()

        }

    },


    columns: [

        {data: 'DT_RowIndex', name: 'DT_RowIndex'},

        {data: 'district_name', name: 'district_name'},
        {data: 'upazila_name', name: 'upazila_name'},
        {data: 'name', name: 'name'},
        {data: 'mobile', name: 'mobile'},
        {data: 'email', name: 'email'},
        {data: 'address', name: 'address'}

    ]

});

});

//reload table. this function only for searching
$(document).on('click','.SearchCustomer',function(){
    $(".client_table").DataTable().draw(true);
});

</script>
@endsection