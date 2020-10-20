<div class="col-md-3">
   <div class="card account-left">
      <div class="user-profile-header">
         {{-- <img alt="logo" src="{{ asset('public/logo_black.png') }}"> --}}
         @if($client_data->photo != '')
            <img id="output_image" src="{{ asset('public/upload/clients/')}}/{{ $client_data->photo }}" style="width: 80px;border-radius:50%;">
         @else
            <img id="output_image" src="{{ asset('public/web/img/user/webdefualt.jpg')}}" style="width: 80px;border-radius:50%;">
         @endif

         <h5 class="mb-1 text-secondary"> {{ $client_data->name }}</h5>
         <p>  {{ $client_data->mobile }}</p>
      </div>
      <div class="list-group">
         <a href="{{ route('customer.profile') }}" class="list-group-item list-group-item-action {{ ($cmenu == 'profile') ? 'active' : '' }}"><i aria-hidden="true" class="mdi mdi-account-outline"></i> প্রোফাইল </a>
         {{-- <a href="my-address.html" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-map-marker-circle"></i>  My Address</a> --}}
         <a href="{{route('customer.wish_list')}}" class="list-group-item list-group-item-action {{ ($cmenu == 'wishlist') ? 'active' : '' }}"><i aria-hidden="true" class="mdi mdi-heart-outline"></i>  উইস লিষ্ট </a>
         <a href="{{ route('customer_orders') }}" class="list-group-item list-group-item-action {{ ($cmenu == 'orderlist') ? 'active' : '' }}"><i aria-hidden="true" class="mdi mdi-cart"></i>  অর্ডার তালিকা</a>
         <a href="{{ route('order_tracking') }}" class="list-group-item list-group-item-action {{ ($cmenu == 'ordertrack') ? 'active' : '' }}"><i aria-hidden="true" class="mdi mdi-cart"></i>  অর্ডার ট্র্যাকিং</a> 

         <a href="{{ route('web.complain_box') }}" class="list-group-item list-group-item-action {{ ($cmenu == 'complain_box') ? 'active' : '' }}"><i aria-hidden="true" class="mdi mdi-cart"></i>  অভিযোগ কর্নার</a> 
         <a href="{{ route('web.complain_list') }}" class="list-group-item list-group-item-action {{ ($cmenu == 'complain_list') ? 'active' : '' }}"><i aria-hidden="true" class="mdi mdi-cart"></i> অভিযোগের তালিকা</a> 

         <a class="list-group-item list-group-item-action" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
            <i class="mdi mdi-lock"></i> লগআউট
        </a>    
        <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
         
      </div>
   </div>
</div>