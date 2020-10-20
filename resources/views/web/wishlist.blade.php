@extends("layouts.web")
@section("title","Customer Profile")
@section("content")

      <section class="shop-single section-padding pt-3">
         <div class="container">
           <div class="row">
              <div class="col-lg-11 mx-auto">
                 <div class="row no-gutters">
                    
                    {{-- customer menu added --}}
                    @include("web.customer_menu")
                    
                     <div class="col-md-9">
                        <div class="card card-body account-right">
                            <div class="widget">
                               <div class="section-header">
                                  <h5 class="heading-design-h5">
                                     উইস লিষ্ট
                                  </h5>
                               </div>
                               <div class="order-list-tabel-main table-responsive">
                                  <table class="datatabel table table-striped table-bordered order-list-tabel" width="100%" cellspacing="0">
                                     <thead>
                                        <tr>
                                           <th>#</th>
                                           <th>পন্যের ছবি</th>
                                           <th>পন্যের নাম</th>
                                           <th>তারিখ</th>
                                           <!-- <th>পন্যের বর্ণনা</th> -->
                                           <th>একশন</th>
                                        </tr>
                                     </thead>
                                     <tbody>
                                      @php
                                      $cnt = 1;
                                      @endphp
                                      @foreach($wishlist_data as $item)
                                        <tr>
                                          <td>{{$cnt}}</td>
                                          <td width="20%;" class="text-center">
                                            <img data-src="{{asset('public/upload/product')}}/{{$item->Product->picture}}" class="lozad" style="height: 70px;">
                                          </td>
                                          <td>{{$item->Product->name}}</td>
                                          <td>{{date('d M Y',strtotime($item->created_at))}}</td>
                                          <!-- <td>{{$item->Product->description}}</td> -->
                                          <td>
                                            <button class="btn btn-danger btn-sm removeWish" data-id="{{$item->id}}"> <i class="mdi mdi-delete"></i> </button>
                                          </td>
                                        </tr>
                                        @php
                                        $cnt++;
                                        @endphp
                                      @endforeach
                                     </tbody>
                                  </table>
                               </div>
                            </div>
                         </div>
                    </div>
                   
                 </div>
              </div>
           </div>
        </div>
      </section>

@endsection

