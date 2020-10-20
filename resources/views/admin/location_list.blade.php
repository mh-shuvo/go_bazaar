@extends("layouts.admin")
@section("title","Dashboard")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-8">
                <div>
                    <h4 class="header-titles mb-3">উপজেলা, ইউনিয়ন তালিকা</h4>
                </div>
            </div>
            <div class="col-4">

                 <button type="button" class="btn btn-success btn-bordered-success float-right waves-effect waves-light" data-toggle="modal"  onclick="add_location()" > <i class="ti-plus"></i> নতুন যোগ করুন</button>
            </div>
        </div>
        <!-- end row -->

        {{-- start table row --}}
        <div class="row">

            <div class="col-sm-12">

                <div class="card-box">
                    <div class="row">
                        <div class="col-sm-2"></div>
                        <label class="col-sm-1">উপজেলা</label>
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

                        <button class="btn btn-sm btn-primary col-sm-1" onclick="location_filter()" >সার্চ</button>
                    </div><br>

                    <div class="">
                        <table class="table table-bordered data-table table table-hover mails m-0 table table-actions-bar table-centered location_table">

                            <thead>

                                <tr>

                                    <th>নং</th>

                                    <th>জেলা</th>

                                    <th>উপজেলা</th>

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

    <!-- location modal content -->
    <div id="location_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">লোকেশন</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-validation">
                        <div class="form-group row">
                            <label for="upazila_id" class="col-md-4 form-control-label">উপজেলা</label>
                            <div class="col-md-7">
                                <select class="form-control"  name="district" id="district" >
                                    <option value="">সিলেক্ট</option>

                                     @foreach($data as $item)
                                        <option value="{{ $item->id }}">{{ $item->en_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 form-control-label">নাম<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="name" required parsley-type="name" class="form-control" name="name" id="name" placeholder="নাম">

                                <span class="text-danger" id="name_error"></span>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="row_id" id="row_id">

                    <button type="button" id="location_save_button" class="btn btn-primary waves-effect waves-light" onclick="location_store()" >সাবমিট</button>
                    <button type="button" id="location_update_button" class="btn btn-warning waves-effect waves-light" onclick="location_update()" >আপডেট</button>
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


    var location_table = $('.location_table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: {

          url: "{{ route('location_list') }}",

          data: function (e) {

                e.upazila_id = $('#upazila_id').val(),
                e.district_id = $('#district_id').val()

            }

        },

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'district_name', name: 'district_name'},

            {data: 'upazila_name', name: 'upazila_name'},


            { data: 'id', name: 'id', render:function(data, type, row, meta){

                return "<a href='javascript:void(0)' class='edit btn btn-info btn-bordered-info btn-sm' onclick='location_edit("+meta.row+")' >Edit</a> <a href='javascript:void(0)' class='edit btn btn-danger btn-sm' onclick='location_delete("+meta.row+")' >Delete</a>"

            }},

        ]

    });



  });

//filtering location
function location_filter(){

    $(".location_table").DataTable().draw(true);
}

</script>

<script type="text/javascript" src="{{ asset('public/admin/assets/js/admin.js') }}"></script>
@endsection