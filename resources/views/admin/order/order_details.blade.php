@extends("layouts.admin")
@section("title","Order Details")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        <!-- start  -->
        <div class="row">
            <div class="col-md-12">
                <div class="mt-3">
                    <div class="clearfix">
                        <div class="float-left mb-2">
                            <img src="{{ asset('/logo-black.png') }}" alt="" style="height: 50px;" />
                        </div>
                        <div class="float-right">
                            <h3 class="m-0 d-print-none">অর্ডারের বিস্তারিত বিবরণ</h3>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-6">
                            <div class="float-left mt-3">
                                <p><b>হ্যালো,  {{ $client->client_name }}</b></p>
                                <p class="text-muted">আপনাকে অনেক ধন্যবাদ কারণ আপনি আমাদের পণ্য ক্রয় করে চলেছেন। আমাদের সংস্থা প্রতি লেনদেনের জন্য আপনার জন্য উচ্চ মানের পণ্য সরবরাহের পাশাপাশি অসামান্য গ্রাহক পরিষেবা প্রদানের প্রতিশ্রুতি দেয়।</p>
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-4 offset-2">
                            <div class="mt-3 float-right">
                                <p><strong>অর্ডার তারিখ: </strong> <?php echo converter::en2bn(date('d-m-Y', strtotime($client->created_at)));?></p>
                                
                                <p><strong>অর্ডার স্ট্যাটাস: </strong>
                                     
                                    
                                    @if( $client->status == 1)
                                    <label class="badge badge-primary">Pending</label>
                                    @elseif( $client->status == 2)
                                    <label class="badge badge-teal">Confirmed</label>
                                    @else
                                    <label class="badge badge-danger">Rejected</label>
                                    @endif  
                                </p>
                                <p><strong>অর্ডার আইডি: </strong> # {{ converter::en2bn($client->order_id) }}</p>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <h5 class="font-16">কাস্টমারের ঠিকানা</h5>

                            <address class="line-h-24">
                               {{ $client->client_name }}<br />
                                {{ $client->client_address }}<br />
                                <abbr title="Phone">মোবাইল:</abbr> {{ converter::en2bn($client->client_mobile) }}
                            </address>
                        </div>

                        <div class="col-sm-6">
                            <div class="text-sm-right">
                                <h5 class="font-16">শিপিং এর ঠিকানা</h5>

                                <address class="line-h-24">
                                    {{ $client->shipping_address }}
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
                                            <th>ছবি</th>
                                            <th>পন্য</th>
                                            <th>পরিমাণ</th>
                                            <th>মূল্য( প্রতি একক )</th>
                                            <th class="text-right">মোট</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sr = 1;
                                            $total = 0;
                                        @endphp
                                        @foreach($products as $item)


                                        <tr>
                                            <td>{{ converter::en2bn($sr++) }}</td>
                                            <td><img style="width: 50px;" src="{{ asset('public/upload/product') }}/<?php echo $item->picture;?>"></td>
                                            <td>
                                                <b>{{ $item->product_name }}</b> <br />
                                                <label class="badge badge-light-info">{{ $item->shop_name }}</label>,<label class="badge badge-light-purple">{{ $item->shop_address }}</label>
                                            </td>
                                            <td>{{ converter::en2bn($item->debit) }}</td>
                                            <td>৳ {{ converter::en2bn($item->selling_price) }}</td>
                                            <td class="text-right">৳ {{ converter::en2bn($item->selling_price * $item->debit) }}</td>
                                        </tr>

                                        @php
                                            $total += ($item->selling_price * $item->debit);
                                        @endphp

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                           
                        </div>
                        <div class="col-6">
                            <div class="float-right">
                                <p><b>মোট:</b> ৳ {{ converter::en2bn($total) }}</p>
                                <p><b>ডিসকাউন্ট:</b>  {{ converter::en2bn($client->discount) }}</p>
                                <h3>৳ {{ converter::en2bn($total-$client->discount) }} টাকা</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="text-right d-print-none">
                            <a href="javascript:window.print()" class="btn btn-primary waves-effect waves-light mr-1"><i class="fa fa-print mr-1"></i> প্রিন্ট</a>
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
{{-- <script type="text/javascript">
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
</script> --}}

@endsection