@extends("layouts.admin")
@section("title","অভিযোগ সমূহ")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">অভিযোগ সমূহ</h4>
                </div>
            </div>
        </div>
        <!-- end row -->

            <div class="row">

            <div class="col-sm-12">

                <div class="card-box">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-inline row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label">সরবরাহকারী</label>
                                        <select class="form-control" id="supplier_id" name="supplier_id">
                                            <option value="">সরবরাহকারী নির্বাচন করুন</option>
                                            @foreach(App\Supplier::all() as $supplier)
                                            <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">তারিখ(হতে)</label>
                                            <input type="text" class="form-control datepicker" id="from_date" name="from_date">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">তারিখ(পর্যন্ত)</label>
                                            <input type="text" class="form-control datepicker" id="to_date" name="to_date">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary btn-block" style="margin-top:22px;" onclick="filter_result()" type="button"><i class="fa fa-search"></i></button>
                                    </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="">
                        <table class="complain_table table table-bordered contact-table table table-hover mails m-0 table table-actions-bar table-centered">

                            <thead>

                                <tr>

                                    <th>নং</th>

                                    <th>অর্ডার আইডি</th>

                                    <th>কাস্টমার</th>
                                    <th>সরবরাহকারী</th>
                                    <th>অভিযোগ</th>
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
@endsection

@section('js')
<script type="text/javascript">

 $(function () {
    var url = $("meta[name = path]").attr("content");
    var table = $('.complain_table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: {
            url: "{{ route('admin.complain') }}",
             data:function (e) { 
                e.supplier_id = $("#supplier_id").val() || 0,
                e.from_date = $("#from_date").val() || 0,
                e.to_date = $("#to_date").val() || 0
             }
        },

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'order_id', name: 'order_id'},
            {data: 'customer', name: 'customer'},
            {data: 'supplier', name: 'supplier'},
            {data: 'message', name: 'message'},
            {data: 'created_at', name: 'created_at'},
            {name: 'action',orderable: false,searchable: false,render:function(data,type,row){
                return '<a href="'+url+'/admin/complain/'+row.id+'" class="btn btn-primary btn-sm">বিস্তারিত</a>';
            }},
            
        ]

    });

  });
 function filter_result(){
    $('.complain_table').DataTable().draw(true);
 }
</script>
@endsection