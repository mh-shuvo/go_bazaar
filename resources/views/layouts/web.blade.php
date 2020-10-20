
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="author" content="gobazaar.com.bd">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta name="path" content="{{ url('/') }}">
      <title> {{ (isset($get_product_info->product_name) != '') ? $get_product_info->product_name.' | GoBazaar' : 'GoBazaar | ঘরে বসে সবই পাই,বাজারে যাওয়ার দরকার নাই' }} </title>

      <meta name="description" content="{{ (isset($get_product_info->description) != '') ? $get_product_info->description : 'Bangladesh’s best online shopping destination for grocery, gadgets, electronics, appliances and more.' }}">

      <link rel="canonical" href="{{ url('/') }}" />

      <meta property="og:type" content="Product" />

      <meta property="og:title" content="{{ (isset($get_product_info->product_name) != '') ? $get_product_info->product_name . ' | GoBazaar' : 'GoBazaar | ঘরে বসে সবই পাই,বাজারে যাওয়ার দরকার নাই' }}" />

      <meta property="og:description" content="{{ (isset($get_product_info->description) != '') ? $get_product_info->description : 'Bangladesh’s best online shopping destination for grocery, gadgets, electronics, appliances and more.' }}" />

      @if(isset($get_product_info->picture) != '')

      <meta property="og:image" content="{{ asset('public/upload/product')}}/{{ $get_product_info->picture}}" />

      @else

      <meta property="og:image" content="{{ asset('public/logo/logo_white.png') }}" />

      @endif

      @if(isset($get_product_info->rate) != '')

      <meta property="product:price:amount" content="{{ number_format($get_product_info->rate, 2) }}" />
      <meta property="product:price:currency" content="BDT" />
      
      @endif

      <meta property="fb:app_id" content="" />
      <meta name="keywords" content="buy accessories, buy grocery item, grocery, eletronics, offer, deals, best price in bangladesh, online, shopping ,shop, bangladesh, buy online, store, gobazaar, gobazaar.com.bd, gobazaar shop, brands, cash on delivery, home delivery, price in bangladesh, price in bd, গোবাজার.কম.বিডি">

      <meta property="og:url" content="{{ url()->current() }}" />

      <meta property="og:site_name" content="GOBAZAAR.COM.BD" />

      <!-- Favicon Icon -->
      <link rel="icon" type="image/ico" href="{{ asset('/public/logo/favicon.ico') }}">
      <!-- Bootstrap core CSS -->
      <link href="{{ asset('public/web/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
      <!-- Material Design Icons -->
      <link href="{{ asset('public/web/vendor/icons/css/materialdesignicons.min.css') }}" media="all" rel="stylesheet" type="text/css" />
      <!-- <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/2.1.99/css/materialdesignicons.min.css"> -->
      <!-- Select2 CSS -->
      <link href="{{ asset('public/web/vendor/select2/css/select2-bootstrap.css') }}" />
      <link href="{{ asset('public/web/vendor/select2/css/select2.min.css') }}" rel="stylesheet" />
      <!-- Custom styles for this template -->
      <link href="{{ asset('public/web/css/osahan.css') }}" rel="stylesheet">
      <link href="{{ asset('public/web/css/custom.css') }}" rel="stylesheet">
      <link href="{{ asset('public/web/css/sweetalert.css') }}" rel="stylesheet">
      <!-- Owl Carousel -->
      <link rel="stylesheet" href="{{ asset('public/web/vendor/owl-carousel/owl.carousel.css') }}">
      <link rel="stylesheet" href="{{ asset('public/web/vendor/owl-carousel/owl.theme.css') }}">

          {{-- for data table --}}
      <link href="{{ asset('public/admin/assets/libs/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" id="app-stylesheet" />
      <link href="{{ asset('public/admin/assets/libs/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" id="app-stylesheet" />

      <link href="//fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>

       @toastr_css
   </head>
   <body>

    

    <style type="text/css">
        .offer-price{font-size: 15px;}
        body{
            font-family: 'SolaimanLipi', Roboto, sans-serif !important;
        }
        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6{
            font-family: 'SolaimanLipi', Roboto, sans-serif !important;
        }
    </style>
    {{-- customer login and signup modal --}}
    @include("layouts.includes.web.authentication_modal")

    {{-- topbar and menubar --}}
    @include("layouts.includes.web.header")

    @yield('slider')


     @yield("content")

    {{-- footer --}}
    @include("layouts.includes.web.footer")

    {{-- right side cart details modal --}}
    @include('layouts.includes.web.cart')

      <!-- Bootstrap core JavaScript -->
      <script src="{{ asset('public/web/vendor/jquery/jquery.min.js') }}"></script>
      <script src="{{ asset('public/web/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

      {{-- for data table js --}}
      <script src="{{ asset('public/admin/assets/libs/datatables/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('public/admin/assets/libs/datatables/dataTables.bootstrap4.min.js') }}"></script>
      <!-- select2 Js -->
      <script src="{{ asset('public/web/vendor/select2/js/select2.min.js') }}"></script>
      <!-- Owl Carousel -->
      <script src="{{ asset('public/web/vendor/owl-carousel/owl.carousel.js') }}"></script>
      <!-- Custom -->
      <script src="{{ asset('public/web/js/custom.js') }}"></script>
      <script src="{{ asset('public/web/js/core.js') }}"></script>
      <script src="{{ asset('public/web/js/cart.js') }}"></script>
      <script src="{{ asset('public/web/js/sweetalert.min.js') }}"></script>
      <script type="text/javascript">
        const observer = lozad();
        observer.observe();
      </script>

      @toastr_js

      @toastr_render

      {{-- @include('sweetalert::alert') --}}
      @yield('js')
   </body>
</html>

