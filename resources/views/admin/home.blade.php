@extends("layouts.admin")
@section("title","Dashboard")
@section("content")
 <!-- Start container-fluid -->
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div>
                                <h4 class="header-title mb-3">স্বাগতম ! </h4>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-12">
                            <div>
                                <div class="card-box widget-inline">
                                    <div class="row">
                                        @if(Auth::user()->user_type == 1)
                                        
										<div class="col-xl-3 col-sm-6 widget-inline-box">
                                            <div class="text-center p-3">
                                            <a href="{{route('suppliers')}}">
                                                <h2 class="mt-2"><i class="text-primary mdi mdi-account-multiple mr-2"></i> <b id="total_supplier" class="spinner-border text-primary SpinerTag"></b></h2>
                                                <p class="text-muted mb-0">মোট সরবরাহকারী</p>
                                            </a>
                                            </div>
                                        </div>
                                        @endif
                                        @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 2)
                                        <div class="col-xl-3 col-sm-6 widget-inline-box">
                                            <div class="text-center p-3">
                                             @if(Auth::user()->user_type == 1)
                                            <a href="{{route('clients')}}">
                                            @else
                                            <a href="{{route('supplier.client.index')}}">
                                            @endif
                                                <h2 class="mt-2"><i class="text-teal  mdi mdi-account-multiple-plus mr-2"></i> <b id="total_clients" class="spinner-border text-success SpinerTag"></b></h2>
                                                <p class="text-muted mb-0">মোট ক্রেতা</p>
                                            </a>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="col-xl-3 col-sm-6 widget-inline-box">
                                            <div class="text-center p-3">
                                             @if(permission_check("order"))
                                            <a href="{{route('supplier.order.index')}}">
                                            @else
                                            <a href="javascript:void(0)"></a>
                                            @endif

                                                <h2 class="mt-2"><i class="text-info mdi mdi-bell-plus mr-2"></i> <b id="total_orders" class="spinner-border text-info SpinerTag"></b></h2>
                                                <p class="text-muted mb-0">মোট অর্ডার</p>
                                            </a>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-sm-6">
                                            <div class="text-center p-3">
                                             @if(permission_check("order"))
                                            <a href="{{route('supplier.order.index')}}">
                                            @else
                                            <a href="javascript:void(0)"></a>
                                            @endif
                                            
                                                <h2 class="mt-2"><i class="text-danger mdi mdi-cellphone-link mr-2"></i> 
												<b id="today_order" class="spinner-border text-primary SpinerTag"></b></h2>
                                                <p class="text-muted mb-0">আজকের অর্ডার</p>
                                            </a>
                                            </div>
                                        </div>
                                        @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 2)

                                        <div class="col-xl-3 col-sm-6 widget-inline-box">
                                            <div class="text-center p-3">
                                              @if(Auth::user()->user_type == 1)
                                                <a href="javascript:void(0)">
                                              @else
                                                <a href="{{route('supplier.inventory.report')}}">
                                              @endif
                                                <h2 class="mt-2"><i class="text-primary mdi mdi-account-cash mr-2"></i> 
												<b id="current_stock" class="spinner-border text-info SpinerTag"></b></h2>
                                                <p class="text-muted mb-0">বর্তমান স্টক</p>
                                            </a>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-sm-6">
                                            <div class="text-center p-3">
                                              @if(Auth::user()->user_type == 1)
                                                <a href="javascript:void(0)">
                                              @else
                                                <a href="{{route('supplier.report.product')}}">
                                              @endif
                                                <h2 class="mt-2"><i class="text-info mdi mdi-sale mr-2"></i> <b id="total_sale" class="spinner-border text-primary SpinerTag"></b></h2>
                                                <p class="text-muted mb-0">মোট বিক্রয় </p>
                                            </a>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6">
                                            <div class="text-center p-3">
                                              @if(Auth::user()->user_type == 1)
                                                <a href="javascript:void(0)">
                                              @else
                                                <a href="{{route('supplier.report.product')}}">
                                              @endif
                                                <h2 class="mt-2"><i class="text-info mdi mdi-sale mr-2"></i> <b id="today_sale" class="spinner-border text-primary SpinerTag"></b></h2>
                                                <p class="text-muted mb-0">আজকের বিক্রয় </p>
                                            </a>
                                            </div>
                                        </div>
                                        @endif

                                        @if(Auth::user()->user_type == 4)

                                        <div class="col-xl-3 col-sm-6 widget-inline-box">
                                            <div class="text-center p-3">
                                            <a href="{{route('supplier.order.index')}}">
                                                <h2 class="mt-2"><i class="text-info mdi mdi-bell-plus mr-2"></i> <b id="total_pending_orders" class="spinner-border text-primary SpinerTag"></b></h2>
                                                <p class="text-muted mb-0">মোট অপেক্ষমান অর্ডার</p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 widget-inline-box">
                                            <div class="text-center p-3">
                                            <a href="{{route('supplier.order.index')}}">
                                                <h2 class="mt-2"><i class="text-info mdi mdi-bell-plus mr-2"></i> <b id="total_confirm_order" class="spinner-border text-success SpinerTag"></b></h2>
                                                <p class="text-muted mb-0">মোট কনফার্ম অর্ডার</p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 widget-inline-box">
                                            <div class="text-center p-3">
                                            <a href="{{route('supplier.order.index')}}">
                                                <h2 class="mt-2"><i class="text-info mdi mdi-bell-plus mr-2"></i> <b id="total_reject_order" class="spinner-border text-info SpinerTag"></b></h2>
                                                <p class="text-muted mb-0">মোট রিজেক্ট অর্ডার</p>
                                                </a>
                                            </div>
                                        </div>

                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end row -->
					<div class="row">
    <!--div class="col-lg-6">
        <div class="mt-5">
            <h4 class="header-title">Border spinner</h4>
            <p class="sub-header">
                Use the border spinners for a lightweight loading indicator.
            </p>

            <div class="">
                <div class="spinner-border text-primary m-2" role="status"></div>
                <div class="spinner-border text-secondary m-2" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="spinner-border text-success m-2" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="spinner-border text-danger m-2" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="spinner-border text-warning m-2" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="spinner-border text-info m-2" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="mt-5">
            <h4 class="header-title">Growing spinner</h4>
            <p class="sub-header">
                If you don’t fancy a border spinner, switch to the grow spinner. While it doesn’t technically spin, it does repeatedly grow!
            </p>

            <div class="">
                <div class="spinner-grow text-primary m-2" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="spinner-grow text-secondary m-2" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="spinner-grow text-success m-2" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="spinner-grow text-danger m-2" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="spinner-grow text-warning m-2" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="spinner-grow text-info m-2" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div-->

                </div>
                <!-- end container-fluid -->
@endsection
@section('js')
<script>
$(function () {
	$.ajax({
			url:'{{route("home.data")}}',
			type:'GET',
			dataType:'JSON',
			success:function(res){
				$(".SpinerTag").removeClass('spinner-border');
				$("#total_supplier").html(res.total_supplier);
				$("#today_orders").html(res.today_orders);
				$("#today_sale").html(res.today_sale);
				$("#total_clients").html(res.total_clients);
				$("#total_confirm_orders").html(res.total_confirm_orders);
				$("#total_orders").html(res.total_orders);
				$("#total_pending_orders").html(res.total_pending_orders);
				$("#total_reject_orders").html(res.total_reject_orders);
				$("#total_sale").html(res.total_sale);
				$("#current_stock").html(res.current_stock);
			}
		});
	});
</script>
@endsection