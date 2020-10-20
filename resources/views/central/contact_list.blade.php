@extends("layouts.admin")
@section("title","কন্ট্যাক্ট রিপোর্ট")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">কন্ট্যাক্ট রিপোর্ট</h4>
                </div>
            </div>
        </div>
        <!-- end row -->
             <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                          
                                <div class="row">
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
                                        <button class="btn btn-primary btn-block FilterResult" style="margin-top:30px;"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
        {{-- start table row --}}

             <div class="row">

            <div class="col-sm-12">

                <div class="card-box">
                    <div class="">
                        <table class="orderlist_table table table-bordered contact-table table table-hover mails m-0 table table-actions-bar table-centered">

                            <thead>

                                <tr>

                                    <th>Sl.</th>

                                    <th>Name</th>

                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Message</th>

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
    let total_purchase = 0;
    let total_sale = 0;
    var table = $('.contact-table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: {
            url: "{{ route('admin.contact.data') }}",
             data:function (e) {
                e.from_date = $('#from_date').val() || 0,
                e.to_date = $('#to_date').val() || 0
            }
        },

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'name', name: 'name'},
            {data: 'phone', name: 'phone'},
            {data: 'email', name: 'email'},
            {data: 'message', name: 'message'},
            
        ]

    });
    $(document).on('click','.FilterResult',function(){
         $(".contact-table").DataTable().draw(true);

    });

  });
</script>
@endsection