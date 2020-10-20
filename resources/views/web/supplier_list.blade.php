@extends("layouts.web")
@section("title","বিক্রেতার তালিকা")
@section("content")
<!-- Inner Header -->
<section class="section-padding bg-dark inner-header">
    <div class="container">
       <div class="row">
          <div class="col-md-12 text-center">
             <h1 class="mt-0 mb-3 text-white">বিক্রেতার তালিকা</h1>
             <div class="breadcrumbs">
                <p class="mb-0 text-white"><a class="text-white" href="javascript:void(0)">Home</a>  /  <span class="text-success">বিক্রেতার তালিকা</span></p>
             </div>
          </div>
       </div>
    </div>
 </section>
 <!-- End Inner Header -->
 <!-- Supplier List -->
 <section class="product-items-slider section-padding">
         <div class="container">
            <div class="owl-carousel owl-carousel-featured">
              @foreach($suppliers as $supplier)
                <div class="item">
                    <div class="product" style="margin-bottom: 10px;">
                       <a href="javascript:void(0)">
                          <div class="product-header">
                             <!-- <span class="badge badge-success">50% OFF</span> -->
                             <img class="img-fluid lozad" data-src="{{asset('public/default.jpg')}}" alt="">
                             <!-- <span class="veg text-success mdi mdi-circle"></span> -->
                          </div>
                          <div class="product-body">
                             <h5>{{$supplier->shop_name}}</h5>
                             <h5>{{$supplier->name}}</h5>
                             <h6><strong>{{$supplier->Upazila->name}},{{$supplier->Union->name}}</strong></h6>
                             <p>{{$supplier->address}}</p>
                          </div>
                          
                       </a>
                    </div>
                </div>
              @endforeach

             
            </div>
             
               {{$suppliers->links()}}
            
         </div>
      </section>
 <!-- End Supplier List -->
@endsection