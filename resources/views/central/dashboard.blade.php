@extends("layouts.admin")
@section("title","Dashboard")
@section("content")
 <!-- Start container-fluid -->
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div>
                                <h4 class="header-title mb-3">সেন্ট্রাল মনিটরিং সিস্টেম এ আপনাকে স্বাগতম ! </h4>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-12">
                            <div>
                                <div class="card-box widget-inline">
                                    <div class="row">
                                        <div class="col-xl-3 col-sm-6 widget-inline-box">
                                            <div class="text-center p-3">
                                            <a href="javascript:void(0)">
                                                <h2 class="mt-2"><i class="text-primary   mdi mdi-account-multiple mr-2"></i> <b>{{Converter::en2bn($total_supplier)}}</b></h2>
                                                <p class="text-muted mb-0">মোট সরবরাহকারী</p>
                                            </a>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 widget-inline-box">
                                            <div class="text-center p-3">
                                            <a href="javascript:void(0)">
                                                <h2 class="mt-2"><i class="text-teal  mdi mdi-account-multiple-plus mr-2"></i> <b>{{Converter::en2bn($total_clients)}}</b></h2>
                                                <p class="text-muted mb-0">মোট ক্রেতা</p>
                                            </a>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-sm-6 widget-inline-box">
                                            <div class="text-center p-3">
                                            <a href="javascript:void(0)"></a>
                                                <h2 class="mt-2"><i class="text-info mdi mdi-bell-plus mr-2"></i> <b>{{Converter::en2bn($total_orders)}}</b></h2>
                                                <p class="text-muted mb-0">মোট অর্ডার</p>
                                            </a>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-sm-6">
                                            <div class="text-center p-3">
                                            <a href="javascript:void(0)"></a>
                                            
                                                <h2 class="mt-2"><i class="text-danger mdi mdi-cellphone-link mr-2"></i> <b>{{Converter::en2bn($today_orders)}}</b></h2>
                                                <p class="text-muted mb-0">আজকের অর্ডার</p>
                                            </a>
                                            </div>
                                        </div>
                                       

                                        <div class="col-xl-3 col-sm-6">
                                            <div class="text-center p-3">
                                                <a href="javascript:void(0)">
                                                <h2 class="mt-2"><i class="text-info mdi mdi-sale mr-2"></i> <b>{{Converter::en2bn($total_sale)}}</b></h2>
                                                <p class="text-muted mb-0">মোট বিক্রয়</p>
                                            </a>
                                            </div>
                                        </div>

                                      

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end row -->
                </div>
                <!-- end container-fluid -->
@endsection
