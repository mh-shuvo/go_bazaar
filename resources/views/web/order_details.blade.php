<style>
    @media print
    {    
        .footer, .no-print *
        {
            display: none !important;
        }
    }
</style>
@extends("layouts.web")
@section("title","Customer Profile")
@section("content")


{{-- {{ dd($data['client']->client_name) }} --}}
      <section class="shop-single section-padding pt-3" >
         <div class="container" style="background: #fff;">
            <div class="row">

               <div class="col-md-12">
                  <div class="container-fluid">
        <!-- start  -->
        <div class="row">
            <div class="col-md-12">
                <div class="mt-3">
                    <div class="clearfix">
                        <div class="float-left mb-2">
                            <img src="{{ asset('public/web/img/logo_black.png') }}" alt="" style="height: 50px;" />
                        </div>
                        <div class="float-right">
                            <h3 class="m-0 d-print-none">অর্ডারের বিস্তারিত বিবরণ</h3>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-6">
                            <div class="float-left mt-3">
                                <p><b>হ্যালো,  {{ $data['client']->client_name }}</b></p>
                                <p class="text-muted">আপনাকে অনেক ধন্যবাদ কারণ আপনি আমাদের পণ্য ক্রয় করে চলেছেন। আমাদের সংস্থা প্রতি লেনদেনের জন্য আপনার জন্য উচ্চ মানের পণ্য সরবরাহের পাশাপাশি অসামান্য গ্রাহক পরিষেবা প্রদানের প্রতিশ্রুতি দেয়।</p>
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-4 offset-2">
                            <div class="mt-3 float-right">
                                <p><strong>অর্ডার তারিখ: </strong> <?php echo converter::en2bn(date('d-m-Y', strtotime($data['client']->created_at)));?></p>
                                
                                <p><strong>অর্ডার স্ট্যাটাস: </strong>
                                     
                                    
                                    @if( $data['client']->status == 1)
                                    <label class="badge badge-primary">Pending</label>
                                    @elseif( $data['client']->status == 2)
                                    <label class="badge badge-teal">Confirmed</label>
                                    @elseif( $data['client']->status == 4)
                                    <label class="badge badge-success">Partial Confirmed</label>
                                    @else
                                    <label class="badge badge-danger">Rejected</label>
                                    @endif  
                                </p>
                                <p><strong>অর্ডার আইডি: </strong> # {{ converter::en2bn($data['client']->order_id) }}</p>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <h5 class="font-16">কাস্টমারের ঠিকানা</h5>

                            <address class="line-h-24">
                               {{ $data['client']->client_name }}<br />
                                {{ $data['client']->client_address }}<br />
                                <abbr title="Phone">মোবাইল:</abbr> {{ converter::en2bn($data['client']->client_mobile) }}
                            </address>
                        </div>

                        <div class="col-sm-6">
                            <div class="text-sm-right">
                                <h5 class="font-16">শিপিং এর ঠিকানা</h5>

                                <address class="line-h-24">
                                    {{ $data['client']->shipping_address }}
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
                                        @endphp
                                        @foreach($data['products'] as $item)
                                        <tr>
                                            <td>{{ converter::en2bn($sr++) }}</td>
                                            <td><img style="width: 50px;" src="{{ asset('public/upload/product') }}/{{ $item->picture  }}"></td>
                                            <td>
                                                <b>{{ $item->product_name }} @if($item->status == 3)<label class="badge badge-danger">Rejected</label>@elseif($item->status == 1)<label class="badge badge-primary">Pending</label>@endif</b> <br />
                                                <label class="badge badge-info">{{ $item->shop_name }}</label>,<label class="badge badge-primary">{{ $item->shop_address }}</label>
                                            </td>
                                            <td>{{ converter::en2bn($item->debit) }}</td>
                                            <td>৳ {{ converter::en2bn(number_format($item->selling_price)) }}</td>
                                            <td class="text-right">৳ {{ converter::en2bn($item->selling_price * $item->debit) }}</td>
                                        </tr>
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
                                <p><b>মোট:</b> ৳ {{ converter::en2bn($data['client']->total_amount) }}</p>
                                <p><b>ডিসকাউন্ট:</b>  {{ converter::en2bn($data['client']->discount) }}</p>
                                <h3>৳ {{ converter::en2bn($data['client']->net_amount) }} টাকা</h3>
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
               </div>
            </div>
         </div>
      </section>

@endsection

