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
                    <div class="">
                        <table class="complain_table table table-bordered contact-table table table-hover mails m-0 table table-actions-bar table-centered">

                            <thead>

                                <tr>

                                    <th>নং</th>

                                    <th>অর্ডার আইডি</th>

                                    <th>কাস্টমারের নাম</th>
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
            url: "{{ route('supplier.complain') }}",
             data:function (e) { }
        },

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'order_id', name: 'order_id'},
            {data: 'customer', name: 'customer'},
            {data: 'message', name: 'message'},
            {data: 'created_at', name: 'created_at'},
            {name: 'action',orderable: false,searchable: false,render:function(data,type,row){
                return '<a href="'+url+'/supplier/complain/reply/'+row.id+'" class="btn btn-primary btn-sm">বিস্তারিত</a>';
            }},
            
        ]

    });

  });
</script>
@endsection