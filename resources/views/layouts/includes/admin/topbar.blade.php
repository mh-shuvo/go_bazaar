    <!-- Topbar Start -->
        <div class="navbar-custom">
            <ul class="list-unstyled topnav-menu float-right mb-0">

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle nav-user mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ asset('public/admin/assets/images/users/default.jpg')}}" alt="user-image" class="rounded-circle">

                        <span class="pro-user-name ml-1">
                            @if(Auth::user()->user_type == 2)
                                @php
                                    $data = App\Supplier::find(Auth::user()->record_id);
                                    echo $data->name;
                                @endphp
                            @elseif(Auth::user()->user_type == 5)
                                @php
                                $employee = App\Employe::find(Auth::user()->record_id);
                                echo $employee->name;
                                @endphp
                            @else
                            {{Auth::user()->username}}
                            @endif <i class="mdi mdi-chevron-down"></i> 
                            </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        <!-- item-->

                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">
                            @if(Auth::user()->user_type == 2)
                                @php
                                    $data = App\Supplier::find(Auth::user()->record_id);
                                    echo $data->name;
                                @endphp
                            @else
                            স্বাগতম !
                            @endif
                        </h6>
                        </div>

                        <!-- item-->
                        @if(Auth::user()->user_type == 2)
                        <a href="{{ route('supplier_profile') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-outline"></i>
                            <span>প্রোফাইল</span>
                        </a>
                        @elseif(Auth::user()->user_type == 4)
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-outline"></i>
                            <span>প্রোফাইল</span>
                        </a>
                        @endif
                        <div class="dropdown-divider"></div>

                        
                        <a href="javascript:void(0)" class="dropdown-item notify-item" onclick="password_changes()">
                            <i class=" mdi mdi-onepassword"></i>
                            <span> পাসওয়ার্ড পরিবর্তন </span>
                        </a>
                       
                        @if(!session()->has('auth_user_id'))
                        <!-- item-->
                        <a href="{{ route('logout') }}" class="dropdown-item notify-item" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout-variant"></i>
                            <span>লগ আউট</span>
                             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                        </a>
                        @else
                        <a href="{{ route('impersonate.leave') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-logout-variant"></i>
                            <span>লগ আউট</span>
                        </a>
                        @endif

                    </div>
                </li>

            </ul>

            <!-- LOGO -->
            <div class="logo-box">
                <a href="{{ route('home') }}" class="logo text-center logo-dark">
                    <span class="logo-lg">
                            <img src="{{ asset('public/logo/logo_black.png')}}" alt="" height="55">
                            <!-- <span class="logo-lg-text-dark">Simple</span> -->
                    </span>
                    <span class="logo-sm">
                            <!-- <span class="logo-lg-text-dark">S</span> -->
                    <img src="{{ asset('public/logo/logo_black.png')}}" alt="" height="22">
                    </span>
                </a>

                <a href="{{ route('home') }}" class="logo text-center logo-light">
                    <span class="logo-lg">
                            <img src="{{ asset('public/logo/logo_black.png')}}" alt="" height="26">
                    </span>
                    <span class="logo-sm">
                            <!-- <span class="logo-lg-text-light">S</span> -->
                    <img src="{{ asset('public/logo/logo_black.png')}}" alt="" height="22">
                    </span>
                </a>
            </div>

            <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                <li>
                    <button class="button-menu-mobile">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </li>

                @if(Auth::user()->user_type != 6)

                <li>
                    <a style="border: none;color: rgba(98,103,115,.7);display: inline-block;height: 60px;line-height: 70px;background-color: transparent;font-size: 20px;cursor: pointer;" href="{{ route('supplier.support') }}">
                        <span><i class="mdi mdi-headphones"></i> সাপোর্ট</span>
                     </a>
                </li>

                @endif
               
                
                <li class="d-none d-lg-block">
                   <p class="mt-3 h3 ml-3">
                    @if(Auth::user()->upazila_id)
                        <span class="text-info"> {{Auth::user()->Upazila->en_name}},</span>
                    @endif
                    @if(Auth::user()->district_id)
                        <span class="text-primary">{{Auth::user()->District->en_name}}</span>
                    @endif
                  </p>
                </li>
                
                

            </ul>
        </div>
        <!-- end Topbar -->