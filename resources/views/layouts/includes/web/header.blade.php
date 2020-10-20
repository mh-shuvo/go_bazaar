<nav class="navbar navbar-light navbar-expand-lg bg-dark bg-faded osahan-menu">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}"> <img src="{{ asset('public/logo/logo_white.png') }}" alt="logo" style="width: 173px;" /> </a>
        <button class="navbar-toggler navbar-toggler-white" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse" id="navbarNavDropdown">
            <div class="navbar-nav mr-auto mt-2 mt-lg-0 margin-auto top-categories-search-main">
                <div class="top-categories-search">

                    <form action="{{ route('product_search') }}" method="POST">
                        @csrf
                        <div class="input-group">
                        <input class="form-control" name="product" id="product" placeholder="পণ্য খুজুন (যেমন, চাল, আলু, পেয়াজ)"  type="text" autocomplete="off"   required="required" />
                        <span class="input-group-btn">
                            <button class="btn btn-secondary product_find" type="submit"><i class="mdi mdi-file-find"></i> খুঁজুন</button>
                        </span>
                        </div>
                        
                        
                    </form>
                    {{ csrf_field() }}

                    <span id="product_list" class="dropdown scrollable-menu" style="background: #1c2224; width: 100%; position: absolute; z-index: 9999;height: auto;max-height: 300px;overflow-x: hidden;"></span>

                    <span id="response_alert" style="margin-top: 5px;"></span>

                </div>
            </div>
            <div class="my-2 my-lg-0" style="margin: -90px;">
                <ul class="list-inline main-nav-right">
                     @if(session()->has('client_id'))

                        <ul class="list-unstyled topnav-menu float-right mb-0" style="margin-top: 10px;">
                            <li class="dropdown notification-list">
                                <a class="nav-link dropdown-toggle nav-user mr-0 customerHedaerName" data-toggle="dropdown" href="javascript:void(0)" role="button" aria-haspopup="false" aria-expanded="false">
                                    
                                    @if(session('photo') != '')
                                        <img width="30px" height="30px" src="{{ asset('public/upload/clients/')}}/{{ session('photo') }}" alt="user-image" class="rounded-circle" />
                                    @else
                                        <img width="30px" height="30px" src="{{ asset('public/web/img/user/webdefualt.jpg')}}" alt="user-image" class="rounded-circle" />
                                    @endif

                                    <span class="pro-user-name ml-1">
                                        <span class="customerHedaerName">
                                            {{ session('name')}}
                                        </span>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                                    <!-- item-->
                                    <a href="{{ route('customer.profile')}}" class="dropdown-item notify-item">
                                        <i class="mdi mdi-account-outline"></i>
                                        <span>প্রোফাইল</span>
                                    </a>

                                    <div class="dropdown-divider"></div>
                                    <!-- item-->
                                    <a href="{{route('customer.wish_list')}}" class="dropdown-item notify-item">
                                        <i aria-hidden="true" class="mdi mdi-heart-outline"></i>
                                        <span>উইস লিষ্ট দেখুন</span>
                                    </a>

                                    <div class="dropdown-divider"></div>
                                    <!-- item-->
                                    <a href="{{ route('customer_orders') }}" class="dropdown-item notify-item">
                                        <i class="mdi mdi-cart-outline"></i>
                                        <span>অর্ডার দেখুন</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('order_tracking') }}" class="dropdown-item notify-item">
                                        <i class="mdi mdi-cart-outline"></i>
                                        <span>অর্ডার ট্র্যাকিং</span>
                                    </a>

                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('web.complain_box') }}" class="dropdown-item notify-item">
                                        <i class="mdi mdi-cart-outline"></i>
                                        <span>অভিযোগ করুন</span>
                                    </a>

                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('web.complain_list') }}" class="dropdown-item notify-item">
                                        <i class="mdi mdi-cart-outline"></i>
                                        <span>অভিযোগের তালিকা</span>
                                    </a>

                                    <div class="dropdown-divider"></div>
                                    <!-- item-->
                                   {{--  <a href=" {{ route('customer.edit') }}" class="dropdown-item notify-item">
                                        <i class="mdi mdi-eye-outline"></i>
                                        <span>পাসওয়ার্ড পরিবর্তন</span>
                                    </a> --}}

                                    {{-- <div class="dropdown-divider"></div> --}}

                                    <!-- item-->
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" >
                                        @csrf
                                        <button type="submit" tabindex="0" class="dropdown-item">
                                        <i class="mdi mdi-logout-variant"></i> লগআউট</button>
                                    </form>
                                </div>
                            </li>
                        </ul>

                        @else
                    <li class="list-inline-item">
                        <a href="javascript:void(0)" data-target="#authentication_modal" data-toggle="modal" class="btn btn-link"><i class="mdi mdi-account-circle"></i> সাইন ইন</a>
                    </li>
                    @endif

                    <li class="list-inline-item cart-btn">
                        <a href="javascript:void(0)" id="cartSidebar" data-toggle="offcanvas" class="cartShow btn btn-link border-none">
                            <i class="mdi mdi-cart"></i> কার্ট

                                <small class="{{ (Session('cart_product')) ? 'cart-value' : 'cartsvalue'}}">
                                    <span id="cart_product_total">
                                        @if(Session('cart_product'))
                                        {{count(Session('cart_product'))}}
                                        @endif
                                    </span>    
                                </small>

                        </a>
                    </li>
                </ul>
            </div>
            <input type="hidden" name="target" id="target" value="{{ asset('public/')}}" />
            <input type="hidden" name="imgPath" id="imgPath" value="{{ asset('public/upload/product')}}/" />
            <!-- login or Registration success message-->

            <div id="flash_msg" style="float: right;">
                @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
            </div>
        </div>
    </div>
</nav>
<nav class="navbar navbar-expand-lg navbar-light osahan-menu-2 pad-none-mobile">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0 margin-auto">
                @foreach($menu_category as $item)
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('product/list')}}/{{ $item->id }}"> {{ $item->name }}</a>
                </li>
                @endforeach
                
                <li class="nav-item">
                    <a href="{{ route('web.categories') }}" class="nav-link"> সকল ক্যাটাগরি </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('web.contact') }}" class="nav-link">যোগাযোগ </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
@section('js')


<script>
$(document).ready(function(){

 $('#product').keyup(function(){

        var query = $(this).val();

        if(query != ''){

            var _token = $('input[name="_token"]').val();
            
            $.ajax({
                url:"{{ route('productSearch') }}",
                method:"POST",
                data:{query:query, _token:_token},
                success:function(data){

                    console.log(data);

                    $('#product_list').fadeIn();  
                    $('#product_list').html(data);
                }
            });
        }
    });

    $(document).on('click', '#stext', function(){

        $('#product').val($(this).text());  
        $('#product_list').fadeOut();

    }); 



});
</script>
@endsection
