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
                                     অর্ডার তালিকা
                                  </h5>
                               </div>
                               <div class="order-list-tabel-main table-responsive">
                                  <table class="datatabel table table-striped table-bordered order-list-tabel" width="100%" cellspacing="0">
                                     <thead>
                                        <tr>
                                           <th>অর্ডার নং #</th>
                                           <th>ক্রয়ের তারিখ</th>
                                           <th>স্ট্যাটাস</th>
                                           <th>মোট</th>
                                           <th>Action</th>
                                        </tr>
                                     </thead>
                                     <tbody>

                                      @foreach($orders as $item)

                                        <tr>
                                           <td>{{ converter::en2bn($item->order_id) }}</td>
                                           <td>{{ converter::en2bn($item->created_at) }}</td>
                                           <td>
                                                @if( $item->status == 1)
                                                <span class="badge badge-primary">Pending</span>
                                                @elseif( $item->status == 2)
                                                <span class="badge badge-success">Confirmed</span>
                                                @elseif( $item->status == 4)
                                                <span class="badge badge-info">Partial Confirmed</span>
                                                @else
                                                <span class="badge badge-danger">Canceled</span>
                                                @endif
                                           </td>
                                           <td>৳ {{ converter::en2bn($item->net_amount) }}</td>
                                           <td>
                                            <a data-toggle="tooltip" data-placement="top" title="" href="{{ url('customer/order_details') }}/{{ $item->order_id }}" data-original-title="অর্ডার বিস্তারিত" class="btn btn-info btn-sm" target="_blank"><i class="mdi mdi-eye"></i> দেখুন</a> @if($item->status == 1)<a data-toggle="tooltip" data-placement="top" title="" href="javascript:void(0)" data-original-title="অর্ডার বাতিল" class="btn btn-warning btn-sm" onclick="order_rejcet({{ $item->order_id }})"><i class="mdi mdi-delete"></i> বাতিল</a>@endif

                                          </td>
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
   var url  = $('meta[name = path]').attr("content");

   function order_rejcet(id){
      
      swal({
         title: "আপনি কি অর্ডারটি বাতিল করতে চান ?",
         // text: "আপনি কি অর্ডারটি বাতিল করতে চান ?",
         type: "warning",
         showConfirmButton: true,
         showCancelButton: true,
         closeOnConfirm: true,
         allowEscapeKey: false,
         confirmButtonText: 'হ্যাঁ',
         cancelButtonText: 'না',
         cancelButtonColor: '#888',
      }, function(isOk){
            if(isOk){
              
               $.ajaxSetup({
                  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
               });

               $.ajax({
                   url: url + '/customer/order_reject',
                   type: "POST",
                   dataType:"JSON",
                   data: {id :id},
                   success:function(response){

                        swal(
                           response.message,
                        )

                     location.reload();
                   }
               });
            }
      });
   
   }
</script>

