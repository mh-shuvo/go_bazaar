@extends("layouts.web")
@section("title","অভিযোগের তালিকা")
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
                                     অভিযোগের তালিকা
                                  </h5>
                               </div>
                               <div class="order-list-tabel-main table-responsive">
                                  <table class="table table-bordered">
                                    <thead>
                                      <th>অর্ডার আইডি</th>
                                      <th>সরবরাহকারী</th>
                                      <th>অভিযোগ</th>
                                      <th>তারিখ</th>
                                      <th>একশন</th>
                                    </thead>
                                    <tbody>
                                    @foreach($complain_data as $item)
                                      <tr>
                                        <td>{{$item->order_id}}</td>
                                        <td>{{$item->Supplier->name}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td>{{$item->message}}</td>
                                        <td><a class="btn btn-warning" href="{{route('web.complain_details',[$item->id])}}">বিস্তারিত</a></td>
                                      </tr>
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

<script type="text/javascript">
  
</script>

