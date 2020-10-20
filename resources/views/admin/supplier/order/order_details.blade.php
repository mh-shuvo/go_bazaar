@extends("layouts.admin")
@section("title","Order Details")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        <!-- start  -->
        <div class="row">
            <div class="col-md-12">
                <div class="mt-3">
                    <div class="clearfix row">
                        <div class="text-left mb-2 col-sm-3">
                            <img src="{{ asset('/public/logo/logo_black.png') }}" alt="Logo" style="height: 50px;" />
                        </div>
						<div class="float-left mb-2 text-center col-sm-4">
                            <h5>{{$supplier_info->shop_name}}</h5>
							<p>{{$supplier_info->address}}</p>
                        </div>
                        <div class="text-right col-sm-5">
                            <h3 class="m-0 d-print-none">অর্ডারের বিস্তারিত বিবরণ</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="float-left mt-3">
                                <p><b>হ্যালো,  {{ !empty($order->client_name)?$order->client_name:'' }}</b></p>
                                <p class="text-muted">আপনাকে অনেক ধন্যবাদ কারণ আপনি আমাদের পণ্য ক্রয় করে চলেছেন। আমাদের সংস্থা প্রতি লেনদেনের জন্য আপনার জন্য উচ্চ মানের পণ্য সরবরাহের পাশাপাশি অসামান্য গ্রাহক পরিষেবা প্রদানের প্রতিশ্রুতি দেয়।</p>
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-4 offset-2">
                            <div class="mt-3 float-right">
                                <p><strong>অর্ডার তারিখ: </strong> {{ date('d-m-Y',strtotime(!empty($order->created_at)?$order->created_at:now())) }}</p>
                                <p><strong>অর্ডার স্ট্যাটাস: </strong>
                                    @if($orderedProducts[0]->status == 1)
                                    <label class="badge badge-primary">Pending</label>
                                    @elseif($orderedProducts[0]->status == 2)
                                    <label class="badge badge-teal">Confirmed</label>
                                    @else
                                    <label class="badge badge-danger">Rejected</label>
                                    @endif
                                </p>
                                <p><strong>অর্ডার আইডি: </strong> #{{ $order->order_id }}</p>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <h5 class="font-16">কাস্টমারের ঠিকানা</h5>

                            <address class="line-h-24">
                               {{ $order->client_name }}<br />
                                {{ $order->client_address }}<br />
                                <abbr title="Phone">P:</abbr> {{ $order->client_mobile }}
                            </address>
                        </div>

                        <div class="col-sm-6">
                            <div class="text-sm-right">
                                <h5 class="font-16">শিপিং এর ঠিকানা</h5>

                                <address class="line-h-24">
                                    {{ $order->shipping_address }}
                                </address>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-centered mt-4">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>পন্য</th>
                                            <th>পরিমাণ</th>
                                            <th>মূল্য( প্রতি একক )</th>
                                            <th class="text-right">মোট</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                    $count = 0;
                                    $total = 0;
                                    @endphp
                                    @foreach ($orderedProducts as $product)
                                    @php
                                    $count++;
                                    $total = $total+($product->selling_price * $product->debit);
                                    @endphp
                                        <tr>
                                            <td>{{ $count }}</td>
                                            <td>
                                                <b>{{ $product->Product->name}}</b> <br />
                                                <label class="badge badge-light-info">{{ $product->Product->Category->name}}</label>,<label class="badge badge-light-purple">{{ $product->Product->SubCategory->name}}</label>
                                            </td>
                                            <td>{{ Converter::en2bn($product->debit) }}</td>
                                            <td>৳ {{ Converter::en2bn($product->selling_price) }}</td>
                                            <td class="text-right">৳ {{ Converter::en2bn($product->selling_price * $product->debit) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="clearfix pt-4">
                                <h6 class="text-muted">নোটিশ:</h6>

                                <small>
                                    চালান প্রাপ্তি থেকে সমস্ত অ্যাকাউন্টের 7 দিনের মধ্যে প্রদান করতে হবে। চেক বা ক্রেডিট কার্ড বা অনলাইনে সরাসরি অর্থ প্রদানের মাধ্যমে প্রদান করতে হবে। যদি অ্যাকাউন্টটি days দিনের মধ্যে পরিশোধ না করা হয় তবে কাজটির নিশ্চিতকরণ হিসাবে সরবরাহিত ক্রেডিট বিশদটি উপরে উল্লিখিত সম্মত উদ্ধৃত ফি নেওয়া হবে।
                                </small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="float-right">
                                <p><b>মোট:</b> ৳ {{ Converter::en2bn($total) }}</p>
                                <p><b>ডিসকাউন্ট (0%):</b> ৳{{Converter::en2bn($order->discount)}}</p>
                                <h3>৳ {{ Converter::en2bn($total-$order->discount) }} টাকা</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="text-right d-print-none">
                            <a href="javascript:window.print()" class="btn btn-primary waves-effect waves-light mr-1"><i class="fa fa-print mr-1"></i> প্রিন্ট</a>
                            <a href="{{route('supplier.order.receipt',[$order->order_id])}}" class="btn btn-info waves-effect waves-light mr-1" target="__blank"><i class="fa fa-print mr-1"></i> রিসিপ্ট প্রিন্ট</a>
                        </div>
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
$(document).on("change","#status",function(){
    let order_id = $("#order_id").val();
    $.ajax({
        url:"{{ route('supplier.order.status_change') }}",
        method: "GET",
        dataType: 'JSON',
        data: {
            order_id : order_id,
            status   : $(this).val()
        },
        success: function(res){
            if(res.status == 'success'){
                  swal("সফল!",'', "success");
            }
        }
    });
});
</script>

@endsection