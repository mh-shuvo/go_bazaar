@extends("layouts.admin")
@section("title","স্টক রিপোর্ট")
@section("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div>
                <h4 class="header-title mb-3">স্টক রিপোর্ট</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">ক্যাটাগরি</label>
                            <select class="form-control" id="filter_category" name="filter_category">
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
                            <select class="form-control" id="filter_sub_category" name="filter_sub_category">
                                <option value="">সাব ক্যাটাগরি নির্বাচন করুন</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">পন্য</label>
                            <select class="form-control filter_product" id="filter_product" name="filter_product">
                                <option value="">পন্য নির্বাচন করুন</option>
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary btn-block" style="margin-top: 30px;"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="">
                    <table class="table table-bordered products-table table table-hover mails m-0 table table-actions-bar table-centered">
                        <thead>
                            <tr>
                                <th>নং</th>

                                <th>ক্যাটাগরি</th>

                                <th>সাব-ক্যাটাগরি</th>

                                <th>পন্যের নাম</th>

                                <th>পরিমাণ</th>

                                <th>ক্রয় মূল্য</th>

                                <th>মোট মূল্য</th>

                                <th>স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody>

                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">সর্বমোট:</th>
                                    <th class="text-right"></th>
                                    <th class="text-right"></th>
                                    <th class="text-right"></th>
                                    <th class="text-right"></th>
                                </tr>
                            </tfoot> -->
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>

@endsection

@section('js')
<script type="text/javascript">

  $(function () {
    var table = $('.products-table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

         ajax: {
            url: "{{ route('supplier.inventory.stockData') }}",
            data:function (e) {

                e.category_id = $('#filter_category').val() || 0,
                e.sub_category_id = $('#filter_sub_category').val() || 0,
                e.product_id = $('.filter_product').val() || 0

            }
        },

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'category', name: 'category'},

            {data: 'sub_category', name: 'sub_category'},

            {data: 'name', name: 'name'},

            {data: 'current_stock', name: 'current_stock'},
            {data: 'buying_price', name: 'buying_price'},
            {data: 'total_stock', name: 'total_stock'},
            {data: 'status', name: 'status'},

        ]

    });
  });

  $(document).on('click','.FilterResult',function(){
       let parameter = {
        "filter_category" : "required",
        // "filter_sub_category" : "required",
    }
    let validate = validation(parameter);

    if(validate == false){
        $(".products-table").DataTable().draw(true);

    }
    else{
        return false;
    }
});
$(document).on('change','#filter_category',function(){
    var category_id = parseInt($(this).val()) || 0;
     document.getElementById("filter_sub_category").innerHTML = "<option value=''>পন্যের সাব ক্যাটাগরি নির্বাচন করুণ</option>";
    if(category_id != 0){
      getSubCategory(category_id,null,'filter_sub_category');
    }
});

$(document).on('change','#filter_sub_category',function(){
    var category_id = parseInt($("#filter_category").val()) || 0;
    var sub_category_id = parseInt($(this).val()) || 0;
     document.getElementById("filter_product").innerHTML = "<option value=''>পন্য নির্বাচন করুণ</option>";
    if(category_id != 0 && sub_category_id != 0){
      getProduct(category_id,sub_category_id,null,"filter_product");
    }
});
</script>
@endsection