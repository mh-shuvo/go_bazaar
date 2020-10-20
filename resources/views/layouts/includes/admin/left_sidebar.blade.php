<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <div class="user-box">
        <div class="float-left">
            @if(Auth::user()->user_type == 2)
            @php
            $data = App\Supplier::find(Auth::user()->record_id);
            @endphp
            @if(!empty($data->shop_image) && $data->shop_image !=NULL)
            <img src="{{ asset('public/upload/supplier')}}/{{$data->shop_image}}" alt="user-image" class="rounded-circle">
            @else
            <img src="{{ asset('public/admin/assets/images/users/default.jpg')}}" alt="user-image" class="rounded-circle">
            @endif
            @else
            <img src="{{ asset('public/admin/assets/images/users/default.jpg')}}" alt="user-image" class="rounded-circle">
            @endif
        </div>
        <div class="user-info">
            @if(Auth::user()->user_type == 2)
            <a href="{{ route('supplier_profile') }}">
                @else
                <a href="javascript:void(0)">
                @endif

                    @if(Auth::user()->user_type == 2)
                    @php
                    $data = App\Supplier::find(Auth::user()->record_id);
                    echo $data->shop_name;
                    @endphp
                    @elseif(Auth::user()->user_type == 2)
                    Delivery Member
                    @elseif(Auth::user()->user_type == 5)
                    @php
                    $employee = App\Employe::find(Auth::user()->record_id);
                    $data = App\Supplier::find($employee->shop_id);
                    echo $data->shop_name;
                    @endphp
                    @elseif(Auth::user()->user_type == 6)
                        @php
                            $record = App\CentralUser::find(Auth::user()->record_id);
                            echo $record->name.' '.$record->post;
                        @endphp
                    @else
                    Super Admin
                    @endif

                </a>
                <!-- <p class="text-muted m-0">{{Auth::user()->username}}</p> -->
        </div>
    </div>

    <!--- Sidemenu -->
    <div id="sidebar-menu">

        <ul class="metismenu" id="side-menu">

        @if(Auth::user()->user_type != 6)
            <li>
                <a href="{{ route('home') }}">
                    <i class="ti-home"></i>
                    <span> ড্যাশবোর্ড </span>
                </a>
            </li>
        @endif

            {{-- super admin --}}
            @if(Auth::user()->user_type == 1)

             <li>
                <a href="{{ route('eccomerce_setup') }}">
                    <i class="fa fa-wrench"></i>
                    <span> ই-কমার্স সেটআপ </span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.acl') }}">
                    <i class="mdi mdi-account"></i>
                    <span> ACL </span>
                </a>
            </li>

            <li>
                <a href="{{ route('location_list') }}">
                    <i class=" mdi mdi-map-marker-plus "></i>
                    <span> লোকেশন </span>
                </a>
            </li>
            <li>
                <a href="{{ route('category_list') }}">
                    <i class="mdi mdi-view-comfy"></i>
                    <span> ক্যাটেগরি ও সাব-ক্যাটেগরি </span>
                </a>
            </li>
            <li>
                <a href="{{ route('clients') }}">
                    <i class=" mdi mdi-account-group"></i>
                    <span> কাষ্টমারগন </span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.user_list') }}">
                    <i class="mdi mdi-account"></i>
                    <span> সেন্ট্রাল ইউজার </span>
                </a>
            </li>
            <li>
                <a href="javascript: void(0);">
                    <i class="mdi mdi-dump-truck"></i>
                    <span> সরবরাহকারী </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level nav" aria-expanded="false">
                    <li>
                        <a href="{{ route('supplier_type') }}">সরবরাহকারীর ধরন</a>
                    </li>
                    <li>
                        <a href="{{ route('supplier_add') }}">নতুন সরবরাহকারী</a>
                    </li>
                    <li>
                        <a href="{{ route('suppliers') }}">সরবরাহকারীর তালিকা</a>
                    </li>

                </ul>
            </li>

            <li>
                <a href="{{ route('csv') }}">
                    <i class="fa fa-upload"></i>
                    <span> CSV ফাইল আপলোড</span>
                </a>
            </li>

            <li>
                <a href="{{ route('orders') }}">
                    <i class=" mdi mdi-cart-plus "></i>
                    <span> অর্ডার সমূহ </span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.contact') }}">
                    <i class="mdi mdi-contacts"></i>
                    <span> যোগাযোগ রিপোর্ট</span>
                </a>
            </li>
             <li>
                <a href="{{ route('admin.complain') }}">
                    <i class="mdi mdi-contacts"></i>
                    <span>অভিযোগ</span>
                </a>
            </li>         
            @endif

            @if(Auth::user()->user_type == 6)
            <li>
                <a href="{{ route('central.dashboard') }}">
                    <i class="ti-home"></i>
                    <span> ড্যাশবোর্ড </span>
                </a>
            </li>
            <li>
                <a href="{{ route('central.supplier_list') }}">
                    <i class="mdi mdi-dump-truck"></i>
                    <span> সরবরাহকারীর রিপোর্ট </span>
                </a>
            </li>
            <li>
                <a href="{{ route('central.customer_list') }}">
                    <i class=" mdi mdi-account-group"></i>
                    <span> কাষ্টমারগন </span>
                </a>
            </li>
            <li>
                <a href="{{ route('central.order_list') }}">
                    <i class=" mdi mdi-cart-plus "></i>
                    <span> অর্ডার সমূহ </span>
                </a>
            </li>
            <li>
                <a href="{{ route('central.contact') }}">
                    <i class="mdi mdi-contacts"></i>
                    <span> কন্ট্যাক্ট রিপোর্ট</span>
                </a>
            </li>
             <li>
                <a href="{{ route('central.complain') }}">
                    <i class="mdi mdi-contacts"></i>
                    <span>অভিযোগ</span>
                </a>
            </li> 
            @endif
            {{-- shop owner or shop employee --}}
            @if(Auth::user()->user_type == 2 || Auth::user()->user_type == 5)
            @if(permission_check("order"))
            <li>
                <a href="javascript: void(0);">
                    <i class="ti-bag"></i>
                    <span> অর্ডার </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">

                    @if(permission_check("order", "list", "view"))
                    <li>
                        <a href="{{ route('supplier.order.index') }}">অর্ডার সমূহ</a>
                    </li>
                    @endif

                    @if(permission_check("order", "report", "view"))
                    <li>
                        <a href="{{ route('report.order') }}"> অর্ডার রিপোর্ট</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if(permission_check("customer", "view"))
            <li>
                <a href="{{ route('supplier.client.index') }}">
                    <i class="mdi mdi-account-group"></i>
                    <span>কাষ্টমারগন</span>
                </a>
            </li>
            @endif

            @if(permission_check("productsell", "view"))
            <li>
                <a href="{{ route('product_sale') }}">
                    <i class="ti-shopping-cart-full"></i>
                    <span>পন্য বিক্রি</span>
                </a>
            </li>
            @endif

            @if(permission_check("product", "view"))
            <li>
                <a href="{{ route('supplier.product.index') }}">
                    <i class="ti-bag"></i>
                    <span> পন্য ব্যবস্থাপনা </span>
                </a>
            </li>
            @endif

            


            @if(permission_check("stock", "view"))
            <li>
                <a href="{{ route('supplier.inventory.index') }}">
                    <i class="ti-bag"></i>
                    <span> স্টক ব্যবস্থাপনা </span>
                </a>
            </li>
            @endif

            @if(permission_check("wastage", "view"))
            <li>
                <a href="{{ route('supplier.product.waste') }}">
                    <i class="ti-bag"></i>
                    <span> নষ্ট পন্য ব্যবস্থাপনা </span>
                </a>
            </li>
            @endif

            @if(permission_check("delivery", "view"))
            <li>
                <a href="{{ route('deliverymans') }}">
                    <i class="mdi mdi-bike-fast"></i>
                    <span> ডেলিভারি ম্যান যোগ </span>
                </a>
            </li>
            @endif

            @if(permission_check("acl", "view"))
            <li>
                <a href="{{ route('acl') }}">
                    <i class="mdi mdi-account"></i>
                    <span> ACL </span>
                </a>
            </li>
            @endif

            @if(permission_check("employee", "view"))
            <li>
                <a href="{{ route('supplier.employe') }}">
                    <i class="mdi mdi-account"></i>
                    <span> কর্মী ব্যবস্থাপনা </span>
                </a>
            </li>
            @endif
           

            @if(permission_check("offer", "view"))
            <li>
                <a href="{{ route('supplier.offer') }}">
                    <i class="mdi mdi-sale"></i>
                    <span> অফার ব্যবস্থাপনা </span>
                </a>
            </li>
            @endif

            @if(permission_check("expanse"))
            <li>
                <a href="javascript: void(0);">
                    <i class="ti-bag"></i>
                    <span> ব্যয় ব্যবস্থাপনা </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">

                    @if(permission_check("expanse", "category", "view"))
                    <li>
                        <a href="{{ route('expense.account_head') }}">ব্যয় ক্যাটাগরি</a>
                    </li>
                    @endif

                    @if(permission_check("expanse", "entry", "view"))
                    <li>
                        <a href="{{ route('expense.entry') }}">ব্যয় এন্ট্রি</a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif

            <li>
                <a href="javascript: void(0);">
                    <i class="ti-bag"></i>
                    <span> Accounts </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">

                    <li>
                        <a href="javascript:void(0);"> Cash </a>
                    </li>
                    
                    <li>
                        <a href="javascript:void(0);"> Bank </a>
                    </li>
                    
                    <li>
                        <a href="javascript:void(0);"> Due Collection  </a>
                    </li>
                    
                    <li>
                        <a href="javascript:void(0);"> Supplier Payment  </a>
                    </li>
                    
                    <li>
                        <a href="javascript:void(0);"> Advance </a>
                    </li>
                    
                    <li>
                        <a href="javascript:void(0);"> Loan </a>
                    </li>
                    
                    <li>
                        <a href="javascript:void(0);"> Capital </a>
                    </li>

                </ul>
            </li>

            @if(permission_check("report"))
            <li>
                <a href="javascript: void(0);">
                    <i class="fa fa-list"></i>
                    <span> রিপোর্ট </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">

                    @if(permission_check("report", "stock", "view"))
                    <li>
                        <a href="{{ route('supplier.inventory.report') }}"> স্টক রিপোর্ট</a>
                    </li>
                    @endif
                @if(permission_check("report"))
                    <li>
                        <a href="javascript: void(0);" aria-expanded="false">বিক্রয়
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-third-level nav" aria-expanded="false">
                            @if(permission_check("sell", "productwise", "view"))
                            <li>
                                <a href="{{ route('supplier.report.product') }}">পন্য ভিত্তিক বিক্রয় রিপোর্ট</a>
                            </li>
                            @endif

                            @if(permission_check("sell", "daily", "view"))
                            <li>
                                <a href="{{ route('report.daily_sale') }}">দৈনিক বিক্রয় রিপোর্ট</a>
                            </li>
                            @endif

                            @if(permission_check("sell", "monthly", "view"))
                            <li>
                                <a href="{{ route('report.monthly_sale') }}"> মাসিক বিক্রয় রিপোর্ট</a>
                            </li>
                            @endif
                            @if(permission_check("report", "salesman", "view"))
                            <li>
                                <a href="{{ route('report.salesman') }}"> বিক্রয় কর্মী </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif
                 @if(permission_check("report","employesale","view") || permission_check("report","employepurchase","view"))
                    <li>
                        <a href="javascript: void(0);" aria-expanded="false">
                            <span> কর্মীর রিপোর্ট </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">

                           @if(permission_check("report","employesale","view"))
                            <li>
                                <a href="{{ route('employe.sale_report') }}">বিক্রয় রিপোর্ট</a>
                            </li>
                            @endif
                            @if(permission_check("report","employepurchase","view"))
                            <li>
                                <a href="{{ route('employe.purchase_report') }}">পণ্য ক্রয় রিপোর্ট</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(permission_check("report", "profitloss", "view"))
                    <li>
                        <a href="{{ route('report.profit_loss') }}">লাভ ক্ষতি রিপোর্ট</a>
                    </li>
                    @endif

                    @if(permission_check("report", "expanse", "view"))
                    <li>
                        <a href="{{ route('expense.report') }}">ব্যায় রিপোর্ট</a>
                    </li>
                    @endif

                    @if(permission_check("report", "balancestatement", "view"))
                    <li>
                        <a href="{{ route('report.balance_statement') }}">ব্যালেন্স স্টেটমেন্ট</a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif

            <li>
                <a href="{{ route('supplier.complain') }}">
                    <i class="mdi mdi-contacts"></i>
                    <span>অভিযোগ</span>
                </a>
            </li>

            <li>
                <a href="javascript: void(0);">
                    <i class="fa fa-list"></i>
                    <span> সেটিংস </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ route('stock.opening') }}"> ওপেনিং স্টক </a>
                    </li>
                </ul>
            </li>
            @endif

        </ul>

    </div>
    <!-- End Sidebar -->

    <div class="clearfix"></div>

</div>
<!-- Left Sidebar End -->

<script type="text/javascript">
    function password_changes() {

        $('#password_modal').modal('show');
    }

</script>


<div id="password_modal" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="myCenterModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myCenterModalLabel">পাসওয়ার্ড পরিবর্তন করুন</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" class="form-validation" id="PasswordChangeForm">
                    <div class="form-group">
                        <label for="userName">User Name<span class="text-danger">*</span></label>
                        <input type="text" name="username" parsley-trigger="change" required placeholder="Enter user name" class="form-control" id="username" value="{{ Auth::user()->username }}" readonly="">
                    </div>

                    <div class="form-group">
                        <label for="pass1">Old Password<span class="text-danger">*</span></label>
                        <input id="old_password" type="password" name="old_password" placeholder="Old Password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="pass1">New Password<span class="text-danger">*</span></label>
                        <input id="password" type="password" name="password" placeholder="Password" class="form-control" minlength="8">
                    </div>

                    <div class="form-group">
                        <label for="pass1">Confirm Password<span class="text-danger">*</span></label>
                        <input id="confirm_password" type="password" name="confirm_password" placeholder="Password" class="form-control" minlength="8">
                    </div>

                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">

                    <div class="form-group text-right mb-0">
                        {{-- <button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
                                    Submit
                                </button> --}}
                        <button type="submit" id="update_button" class="btn btn-warning waves-effect waves-light">আপডেট</button>
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">বাতিল</button>
                    </div>

                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
