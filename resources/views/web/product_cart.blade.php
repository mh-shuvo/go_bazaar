@extends("layouts.web")
@section("title","Product Cart")
@section("content")

      <section class="cart-page section-padding">
        <div class="container">
        	<form action="{{ route('order.check') }}" method="post" enctype="multipart/form-data">
        		@csrf
           <div class="row">
           	    <div class="col-md-4">
                  <div class="shop-detail-right" style="height: 320px">
                 	@if($get_product_info->picture !='')     
		                           <img style="height: 270px" class="img-fluid lozad" data-src="{{ asset('upload/product/') }}/{{ $get_product_info->picture}}" alt="">
		                           <!-- <span class="veg text-success mdi mdi-circle"></span> -->
		                           @else
		                            @if($get_product_info->category_id ==1)
		                                <img style="height: 270px" class="img-fluid lozad" data-src="{{ asset('upload/product/default.jpg') }}" alt="">
		                                @endif
		                             @if($get_product_info->category_id ==2)
		                                  <img style="height: 270px" class="img-fluid lozad" data-src="{{ asset('upload/product/default2.jpg') }}" alt="">
		                             @endif
		                                
		                           @endif
                  </div>
               </div>

           	<div class="col-md-8">
                  <div class="shop-detail-right">
                  	<h5> পণ্যের নাম &nbsp;&nbsp;&nbsp;&nbsp;:  {{ $get_product_info->product_name}}</h5>  
                  	<h5> কৃষকের নাম &nbsp;&nbsp;:  {{ $get_product_info->farmer_name}}</h5>  
                  	<h5> মজুদ  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:  {{$total_stock}}</h5>  
                  	<h5> রেট  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:  ৳ {{ $get_product_info->rate}}</h5>  
                  	<h5> উপজেলা  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:  {{ $get_product_info->upazila_name}}</h5>  
                  	<h5> ইউনিয়ন  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:  {{ $get_product_info->district_name}}</h5>  
                  	<h5> লোডিং পয়েন্ট :  {{ $get_product_info->loading_poing}}</h5>  
                  
                  	<table>
                  		<tr>
                  			<td width="140px"><h5>পরিমান &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</h5> </td>
                  			
                  			<td> 
                  				<button class="plus-btn" type="button" name="button">
						        {{-- <img src="plus.svg" alt="" /> --}} -
						      </button>
						  </td>
						  <td style="width: 100px">
                  				  <input type="text" name="quantity" id="quantity"  min="1" value="1" class="form-control border-form-control form-control-sm input-number" >
                  			</td>
                  			<td> 
                  				<button class="plus-btn" type="button" name="button">
						        {{-- <img src="plus.svg" alt="" /> --}} + 
						      </button>
                  			</td>
                  		</tr>
                  		<tr>
                  			<td></td>
                  			<td colspan="2"> 
                  				<span  id="msg" style="color: red; display: none; "> Not Avilable Stock</span></td>
                  		</tr>

                  	</table>
                	<!--- hidden input -->
                	<input type="hidden" name="rate" id="rate" value="{{ $get_product_info->rate}}">
                	<input type="hidden" name="total_quantity" id="total_quantity" value="{{ $total_stock}}">
                	<input type="hidden" name="farmer_id" id="farmer_id" value="{{ $get_product_info->farmer_id}}">
                	<input type="hidden" name="upazila_id" id="upazila_id" value="{{ $get_product_info->upazila_id}}">
                	<input type="hidden" name="union_id" id="union_id" value="{{ $get_product_info->union_id}}">
                	<input type="hidden" name="product_id" id="product_id" value="{{ $get_product_info->product_id}}">
                	<input type="hidden" name="customer_id" id="customer_id" value="1">
                	<input type="hidden" name="total_amount" id="total_amount">
                	<input type="hidden" name="target" id="target" value="{{asset('')}}" />
                	
                	
                    <?php
                	if (Auth::check()) {?>

                		<input type="hidden" name="user_id" id="user_id" value="{{ isset( Auth::user()->id)}}" />

                	<?php }else{ ?>

						<input type="hidden" name="user_id" id="user_id" value="0" />

					<?php }?>
                	<!--- hidden input -->

                     
                  </div>
          
               </div>
              
           </div><br>

           <div class="row">
              <div class="col-md-12">
                 <div class="card card-body cart-table">
                    
                      <a onclick="orderCheck()" ><button class="btn btn-secondary btn-lg btn-block text-left" type="button"><span class="float-left"><i class="mdi mdi-cart-outline"></i> অর্ডার করুন  </span><span class="float-right"><strong>  মোট  : ৳ <span id="total_amount_show"> {{ $get_product_info->rate}}</span> </strong> <span class="mdi mdi-chevron-right"></span></span></button></a>


                       {{-- <input type="submit" name="submit" class="btn btn-secondary btn-lg btn-block text-left"  value="অর্ডার করুন"><span class="float-right"><strong>  মোট  : ৳ <span id="total_amount_show"> {{ $get_product_info->rate}}</span> </strong> <span class="mdi mdi-chevron-right"></span></span> --}}
                 </div>
              </div>
           </div>
           </form>
        </div>
     </section>
@endsection

