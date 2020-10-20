@extends("layouts.admin")
@section("title","লাভ ক্ষতি রিপোর্ট")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">লাভ ক্ষতি রিপোর্ট</h4>
                </div>
            </div>
        </div>
        <!-- end row -->
             <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                              <form method="POST" action="{{route('download.profit_loss_report')}}" target="_blank">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">তারিখ(হতে) নির্বাচন করুন</label>
                                            <input type="text" class="form-control datepicker" id="from_date" name="from_date">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">তারিখ(পর্যন্ত) নির্বাচন করুন</label>
                                            <input type="text" class="form-control datepicker" id="to_date" name="to_date">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-primary btn-block" style="margin-top:30px;"><i class="fa fa-print"></i> প্রিন্ট</button>

                                        <!-- <span id="text"></span> <span id="value"></span> -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        {{-- start table row --}}

            <div class="row">

            <!-- <div class="col-sm-12">

                <div class="card-box">
                    <div class="">
                        <table class="orderlist_table table table-bordered report-table table table-hover mails m-0 table table-actions-bar table-centered">

                            <thead>

                                <tr>

                                    <th>নং</th>

                                    <th>পন্যের নাম</th>

                                    <th>পরিমাণ</th>

                                    <th>মোট ক্রয়</th>

                                    <th>মোট বিক্রয়</th>

                                </tr>

                            </thead>

                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th class="text-right">Total:</th>
                                    <th class="text-right"></th>
                                    <th class="text-right"></th>
                                    <th class="text-right"></th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
    <!-- end container-fluid -->
@endsection

@section('js')
<script type="text/javascript">

 $(function () {
    let total_purchase = 0;
    let total_sale = 0;
    var table = $('.report-table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: {
            url: "{{ route('report.profit_loss.data') }}",
             data:function (e) {
                e.from_date = $('#from_date').val() || 0,
                e.to_date = $('#to_date').val() || 0
            }
        },

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'product_name', name: 'product_name' },
            
            {data: 'total_qty', name: 'total_qty', className:'dt-body-right'},

            {data: 'total_purchase', name: 'total_purchase',className:'dt-body-right'},

            {data: 'total_sale', name: 'total_sale',className:'dt-body-right'},
            
        ],
        "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;
         
                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };         
                  
                    total_sale_price = api
                        .column( 4, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                      total_purchase_price = api
                        .column( 3, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                      total_qty = api
                        .column( 2, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                        total_purchase = total_purchase_price;
                        total_sale = total_sale_price;
                    // Update footer
                    $( api.column( 3 ).footer() ).html(total_purchase_price+'৳');
                    $( api.column( 2 ).footer() ).html(total_qty);
                    $( api.column( 4 ).footer() ).html(total_sale_price+'৳');
                    CalculateProfitLoass();
                }

    });
    $(document).on('click','.FilterResult',function(){
         $(".report-table").DataTable().draw(true);

    });

    function CalculateProfitLoass(){
        let profit_loss = total_purchase - total_sale;
        let text = "Loss";
        let color = 'red';
        if(profit_loss < 0){
            profit_loss = Math.abs(profit_loss);
            text = "Profit";
            color = 'green';
        }

        $("#text").html(text);
        $("#text").css('color',color);
        $("#value").html(profit_loss+' ৳');
    }
  });
</script>
@endsection