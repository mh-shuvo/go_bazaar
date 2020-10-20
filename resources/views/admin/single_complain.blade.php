@extends("layouts.admin")
@section("title","অভিযোগের কথোপকথন")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">অভিযোগের কথোপকথন</h4>
                </div>
            </div>
        </div>
        <!-- end row -->
             <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                          <div class="row">
                                 <div class="col-sm-12" id="messageBody" style="height: 450px; overflow-y: scroll; bottom: 0;">

                                   <div class="row p-2">
                                    <div class="col-sm-11 text-right">
                                       <p class="mt-2">{{$complain->message}}</p>
                                     </div>
                                     <div class="col-sm-1">
                                       <img src="{{asset('public/default.jpg')}}" alt="Client" style="height: 40px;border-radius: 100%;">
                                     </div>
                                     
                                   </div>

                                   <div id="reply_message_section">
                                     @foreach($complain_reply as $reply)
                                    
                                        @if($reply->reply_from == 1)
                                          <div class="row p-2">
                                           <div class="col-sm-11">
                                             <p class="mt-2 text-right">{{$reply->message}}</p>
                                           </div>
                                           <div class="col-sm-1">
                                             <img src="{{asset('public/upload/supplier')}}/{{$supplier->shop_image}}" alt="{{$supplier->name}}" style="height: 40px;border-radius: 100%;">
                                           </div>
                                         </div>
                                        @else
                                         <div class="row p-2 mt-1">
                                           <div class="col-sm-1">
                                             <img src="{{asset('public/default.jpg')}}" alt="Client" style="height: 40px;border-radius: 100%;">
                                           </div>
                                            <div class="col-sm-11">
                                             <p class="mt-2">{{$reply->message}}</p>
                                           </div>
                                         </div>
                                       @endif

                                       @endforeach
                                   </div>         

                                 </div>        
                               </div>
                        </div>
                    </div>
                </div>
        {{-- start table row --}}
    </div>
    <!-- end container-fluid -->
@endsection

@section('js')
<script type="text/javascript">
    $(document).ready(function(){
       var messageBody = document.querySelector('#messageBody');
       messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight; 
    });
  </script>
@endsection