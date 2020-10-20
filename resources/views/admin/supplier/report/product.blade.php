@extends("layouts.admin")
@section("title","পন্য বিক্রয় রিপোর্ট")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">পন্য বিক্রয় রিপোর্ট</h4>
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
                                        <label class="control-label">ক্যাটাগরি</label>
                                        <select class="form-control" id="category" name="category">
                                            <option value="">পন্যের ধরন নির্বাচন করুণ</option>
                                             @foreach (App\Category::where('type','1')->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label">সাব ক্যাটাগরি</label>
                                        <select class="form-control" id="sub_category" name="filter_product">
                                            <option value="">সাব ক্যাটাগরি নির্বাচন করুন</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label">পন্য</label>
                                        <select class="form-control" id="product" name="product">
                                            <option value="">পন্য নির্বাচন করুন</option>
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
                                        <input type="text" class="form-control datepicker" id="to_date" name="to_date"></select>
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
                        <table class="orderlist_table table table-bordered report-table table table-hover mails m-0 table table-actions-bar table-centered">

                            <thead>

                                <tr>

                                    <th>নং</th>

                                    <th>পন্যের নাম</th>

                                    <th>পন্যের পরিমান</th>

                                    <th>টাকা</th>

                                </tr>

                            </thead>

                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2"></th>
                                    <th class="text-right">Total:</th>
                                    <th class="text-right"></th>
                                </tr>
                            </tfoot>

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
    var table = $('.report-table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: {
            url: "{{ route('supplier.report.product.data') }}",
             data:function (e) {

                e.category = $('#category').val() || 0,
                e.sub_category = $('#sub_category').val() || 0,
                e.product = $('#product').val() || 0,
                e.from_date = $('#from_date').val() || 0,
                e.to_date = $('#to_date').val() || 0
            }
        },

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'product_name', name: 'product_name'},

            {name: 'quantity',orderable:false,render:function(data,type,row){
                return row.quantity;
            }},

            {data: 'total_sale', name: 'total_sale'},
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
                    // Total over this page
                    total_sale_price = api
                        .column( 3, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                    // Update footer
                    $( api.column( 3 ).footer() ).html(total_sale_price+'৳');
                }

    });
  });
$(document).on('change','#category',function(){
    var category_id = parseInt($(this).val()) || 0;
     document.getElementById("sub_category").innerHTML = "<option value=''>পন্যের সাব ক্যাটাগরি নির্বাচন করুণ</option>";
    if(category_id != 0){
      getSubCategory(category_id);
    }
});

$(document).on('change','#sub_category',function(){
    var category_id = parseInt($("#category").val()) || 0;
    var sub_category_id = parseInt($(this).val()) || 0;
     document.getElementById("product").innerHTML = "<option value=''>পন্য নির্বাচন করুণ</option>";
    if(category_id != 0 && sub_category_id != 0){
      getProduct(category_id,sub_category_id);
    }
});


  $(document).on('click','.FilterResult',function(){
         $(".report-table").DataTable().draw(true);

});
</script>

@endsection