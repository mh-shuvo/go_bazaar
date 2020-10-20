@extends("layouts.admin")
@section("title","বারকোড প্রিন্ট করুন")

@section("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-10">
            <div>
                <h4 class="header-title mb-3"> বারকোড প্রিন্ট করুন</h4>
            </div>
        </div>

        <div class="col-12">
            @if ($message = Session::get('success'))

            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>

                <strong>{{ $message }}</strong>
            </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">

            <div class="row">
                <div class="col-sm-2 offset-sm-1">
                    <div class="form-group">
                        <label class="control-label">ক্যাটাগরি</label>
                        <select class="form-control" id="filter_category" name="filter_category" required>
                            <option value=""> নির্বাচন করুন </option>
                            @foreach (App\Category::where('type','1')->get() as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label">সাব ক্যাটাগরি</label>
                        <select class="form-control" id="filter_sub_category" name="filter_sub_category" required>
                            <option value=""> নির্বাচন করুন </option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label">তারিখ(হতে)</label>
                        <input type="text" class="form-control" id="from_date" name="from_date">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label">তারিখ(পর্যন্ত)</label>
                        <input type="text" class="form-control" id="to_date" name="to_date">
                    </div>
                </div>

                <div class="col-sm-2">
                    <button type="button" class="btn btn-primary btn-block GetProduct" style="margin-top: 30px;">
                         সাবমিট
                    </button>
                </div>
            </div>
            <form action="{{ route('download.bulk_barcode') }}" method="post" id="BarcodePrintForm" target="__blank">
                @csrf
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered ProductsTable">
                            <thead>
                                <th>পন্যের নাম</th>
                                <th>বিক্রয় মূল্য</th>
                                <th>সটক</th>
                                <th>বারকোডের পরিমাণ</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-sm-12 print_btn d-none text-right">
                        <button type="submit" class="btn btn-primary btn-md"> <i class="fa fa-print"></i> প্রিন্ট</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @endsection

    @section('js')
    <script src="{{ asset('public/admin/assets/js/pages/stock.js') }}"></script>
    <script type="text/javascript">
        $(document).on('click','.GetProduct',function(){
            let category_id = $("#filter_category") .val() || 0;
            let sub_category_id = $("#filter_sub_category") .val() || 0;
            let from_date = $("#from_date") .val() || 0;
            let to_date = $("#to_date") .val() || 0;

            if(category_id == 0 && sub_category_id == 0 && from_date == 0 && to_date == 0){
                toastr.error('যে কোন একটি ফিল্ড নির্বাচন করুন');
            }
            
            if(category_id != 0 || sub_category_id != 0 || from_date != 0 || to_date != 0){
                $.ajax({
                    url:"{{route('GetProductByFiltering')}}",
                    data:{
                        from_date: from_date,
                        to_date:to_date,
                        category_id:category_id,
                        sub_category_id:sub_category_id
                    },
                    type:"POST",
                    success:function(res){
                        if(res.status == 'success'){
                            htmlRender(res.products);
                            $('.print_btn').removeClass('d-none');
                        }
                        else{
                            toastr.error(res.msg)
                        }
                    }
                })
            }
        });


        function htmlRender(data){
            let html = '';
            data.forEach(element =>{
                html+=htmlTemplate(element);
            });
            $('.ProductsTable tbody').html(html);
        }

        function htmlTemplate(item){
            let html = '<tr>';
                    html += '<td>';
                        html+=item.name;
                    html += '</td>';
                    html += '<td>';
                        html+=item.selling_price;
                    html += '</td>';
                    html += '<td>';
                        html+='<input type="hidden" name="product_name[]" value="'+item.name+'">';
                        html+='<input type="hidden" name="selling_price[]" value="'+item.selling_price+'">';
                        html+='<input type="hidden" name="inventory_id[]" value="'+item.id+'">';
                        html+=item.current_stock;
                    html += '</td>';
                    html += '<td>';
                        html+='<input type="text" class="form-control" name="quantity[]">';
                    html += '</td>';
                html+='</tr>';
            return html;
        }
    </script>
    @endsection