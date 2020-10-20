@extends("layouts.admin")
@section("title","Dashboard")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">অর্ডার সমূহ </h4>
                </div>
            </div>
        </div>
        <!-- end row -->

        {{-- start table row --}}
        <div class="row">

            <div class="col-sm-12">

                <div class="card-box">
                    <div class="row">
                        <div class="col-sm-3 offset-sm-1">
                            <div class="form-group">
                                <label for="district_id" class="control-label">জেলা</label>
                                <select type="text" name="district_id" id="district_id" class="form-control">
                                    <option value="">সিলেক্ট</option>
                                    @foreach($districts as $item)
                                    <option value="{{$item->id}}">{{$item->en_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="shop_id" class="control-label">দোকান</label>
                                <select type="text" name="shop_id" id="shop_id" class="form-control">
                                    <option value="">দোকান নির্বাচন করুন</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="status" class="control-label">স্ট্যাটাস</label>
                                <select type="text" name="status" id="status" class="form-control">
                                    <option value="">সিলেক্ট</option>
                                    <option value="1">অপেক্ষমান</option>
                                    <option value="2">কনফার্ম</option>
                                    <option value="3">বাতিল</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <button class="btn btn-sm btn-primary" style="margin-top:33px;" onclick="order_filter()" >সার্চ</button>
                        </div>
                    </div>

                    <div class="">
                        <table class="orderlist_table table table-bordered">

                            <thead>

                                <tr>

                                    <th>নং</th>
                                    <th>অর্ডার আইডি</th>
                                    <th>জেলা</th>
                                    <th>দোকানের নাম</th>
                                    <th>ত্রেতার নাম</th>
                                    <th>মোবাইল</th>
                                    <th>শিপিং এড্রেস</th>
                                    <th>সর্বমোট</th>
                                    <th>ই-মেইল</th>
                                    <th>স্ট্যাটাস</th>
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
        <!-- end row -->

    </div>
    <!-- end container-fluid -->

@endsection

@section('js')
<script type="text/javascript">

  $(function () {


    var orderlist_table = $('.orderlist_table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: {

          url: "{{ route('orders') }}",

          data: function (e) {

                e.district_id = $('#district_id').val() || 0,
                e.shop_id = $('#shop_id').val() || 0,
                e.status = $('#status').val() || 0

            }

        },

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'order_id', name: 'order_id'},

            {data: 'district_name', name: 'district_name'},

            {data: 'shop_name', name: 'shop_name'},

            {data: 'name', name: 'name'},

            {data: 'mobile', name: 'mobile'},


            {data: 'shipping_address', name: 'shipping_address'},

            {data: 'order_amount', name: 'order_amount'},
            {data: 'email', name: 'email'},

            {data: 'status', name: 'status', render:function(data, type, row, meta){

                if(row.status == 1){
                    return '<span class="badge badge-primary">অপেক্ষমান</span>';
                }else if(row.status == 2){
                    return '<span class="badge badge-teal">কনফার্ম</span>';
                }else{
                    return '<span class="badge badge-danger">বাতিল</span>';
                }

            }},


            {data: 'action', name: 'action', orderable: false, searchable: false},

        ]

    });

  });

//custom filtering
function order_filter(){

    $(".orderlist_table").DataTable().draw(true);

}

$(document).on('change','#district_id',function(){
    let district_id = $(this).val();
    if(district_id != '' && district_id != null){
        $.ajax({
            url: "{{route('get_shop_by_district')}}",
            type: "POST",
            data:{
                district_id: district_id
            },
            success:function(res){
                if(res.status != 'error'){
                    let option_data = "<option value=''>দোকান নির্বাচন করুন</option>";
                    res.data.forEach(element => {
                        option_data += '<option value="'+element.id+'">'+element.shop_name+'</option>';
                    });
                    $("#shop_id").html(option_data);
                }
            }
        });
    }
});

</script>
<script type="text/javascript" src="{{ asset('public/admin/assets/js/admin.js') }}"></script>
@endsection