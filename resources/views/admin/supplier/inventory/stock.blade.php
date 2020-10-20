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
                <form method="get" action="{{route('download.stock_report')}}" target="_blank">
                    @csrf
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">ক্যাটাগরি</label>
                            <select class="form-control" id="filter_category" name="category_id">
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
                            <select class="form-control" id="filter_sub_category" name="sub_category_id">
                                <option value="">সাব ক্যাটাগরি নির্বাচন করুন</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">পন্য</label>
                            <select class="form-control filter_product" id="filter_product" name="product_id">
                                <option value="">পন্য নির্বাচন করুন</option>
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                         <div class="form-group">
                            <label class="control-label">তারিখ(হতে) নির্বাচন করুন</label>
                            <input type="text" class="form-control" id="from_date" name="from_date">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">তারিখ(পর্যন্ত) নির্বাচন করুন</label>
                            <input type="text" class="form-control" id="to_date" name="to_date">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-primary btn-block FilterResult" style="margin-top: 30px;"><i class="fa fa-print"></i> প্রিন্ট</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>

@endsection

@section('js')
<script type="text/javascript">

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