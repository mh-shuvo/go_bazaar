@extends("layouts.web")
@section("title","অভিযোগ এর কথোপকথন")
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
                                     অভিযোগ এর কথোপকথন

                                  </h5>
                               </div>
                               <hr>
                               <div class="row">
                                 <div class="col-sm-12" id="messageBody" style="height: 350px; overflow-y: scroll; bottom: 0;">

                                   <div class="row p-2">
                                     <div class="col-sm-1">
                                       <img src="{{asset('public/upload/clients')}}/{{$client_data->photo}}" alt="{{$client_data->name}}" style="height: 40px;border-radius: 100%;">
                                     </div>
                                     <div class="col-sm-11">
                                       <p class="mt-2">{{$complain->message}}</p>
                                     </div>
                                   </div>

                                   <div id="reply_message_section">
                                     @foreach($complain_reply as $reply)
                                    
                                        @if($reply->reply_from == 0)
                                          <div class="row p-2">
                                            <div class="col-sm-11">
                                             <p class="mt-2 text-right">{{$reply->message}}</p>
                                           </div>
                                           <div class="col-sm-1">
                                             <img src="{{asset('public/upload/clients')}}/{{$client_data->photo}}" alt="{{$client_data->name}}" style="height: 40px;border-radius: 100%;">
                                           </div>
                                           
                                         </div>
                                        @else
                                         <div class="row p-2 mt-1">
                                           <div class="col-sm-1">
                                             <img src="{{asset('public/default.jpg')}}" alt="Supplier" style="height: 40px;border-radius: 100%;">
                                           </div>
                                            <div class="col-sm-11">
                                             <p class="mt-2">{{$reply->message}}</p>
                                           </div>
                                         </div>
                                       @endif

                                       @endforeach
                                   </div>         

                                 </div>
                                 <div class="col-sm-12">
                                  <div class="input-group mb-3">
                                    <input type="hidden" name="complain_id" id="complain_id" value="{{$complain->id}}">
                                    <input type="text" class="form-control" placeholder="আপনার অভিযোগ এখানে লিখুন..." id="message" name="message">
                                    <div class="input-group-append">
                                     <button type="button" class="btn btn-primary" onclick="ReplyMessageSubmit()">
                                             <i class="mdi mdi-near-me"></i>
                                      </button>
                                    </div>
                                  </div>
                                 </div>         
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
@section('js')
<script type="text/javascript">
  $(document).ready(function(){
       var messageBody = document.querySelector('#messageBody');
       messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight; 
    });
   $.ajaxSetup({
      headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
    });
   $(document).on('keyup','#message',function(e){
      if(e.keyCode == 13){
        ReplyMessageSubmit();
      }
      
   });

   function ReplyMessageSubmit(){
    let complain_id = $("#complain_id").val();
    let message = $("#message").val();
      $.ajax({
        url:"{{route('web.complain_reply_submit')}}",
        dataType:"JSON",
        data:{
          complain_id:complain_id,
          message : message
        },
        method:"POST",
        success:function(res){
          if(res.status == 'success'){
            let htmlData = htmlRender(message);
            $("#reply_message_section").html(htmlData);
            $("#message").val('');
          }
          else{
            toastr.error('কোন ভুল হয়েছে');
          }
        }
      });
   }

   function htmlRender(message){
    var html = $("#reply_message_section").html();
    let client_logo = "{{asset('public/upload/clients')}}/{{$client_data->photo}}";
    html+='<div class="row p-2">'+
            '<div class="col-sm-11">'+
              '<p class="mt-2 text-right">'+message+'</p>'+
            '</div>'+
            '<div class="col-sm-1">'+
              '<img src="'+client_logo+'" alt="{{$client_data->name}}" style="height: 40px;border-radius: 100%;">'+
            '</div>'+
          '</div>';
    return html;
   }
</script>
@endsection

