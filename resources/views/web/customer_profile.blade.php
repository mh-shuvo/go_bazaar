@extends("layouts.web")
@section("title","Customer Profile")
@section("content") 



      <section class="account-page section-padding">
        <div class="container">
           <div class="row">

              <div class="col-lg-11 mx-auto">
                 <div class="row no-gutters">
                    
                    {{-- customer menu added --}}
                    @include("web.customer_menu")
                    
                    <div class="col-md-8">
                       <div class="card card-body account-right">
                          <div class="widget">
                             <div class="section-header">
                                <h5 class="heading-design-h5">
                                   প্রোফাইল
                                </h5>
                             </div>
                              <form action="{{ route('customer.update') }}" method="post" enctype="multipart/form-data" onsubmit=" return profile_update()" >
                               @csrf
                               
                               {{-- onsubmit=" return profile_update()" --}}

                                <div class="row">
                                   <div class="col-sm-6">
                                      <div class="form-group">
                                         <label class="control-label">নাম <span class="required">*</span></label>
                                         <input class="form-control border-form-control" name="name" id="client_name" value="{{ $client_data->name }}" placeholder="নাম" type="text" >

                                        @error('name')
                                            <span class="text-danger" id="name_errors">{{ $message }}</span>
                                        @enderror

                                         <span class="text-danger" id="name_error"></span>
                                      </div>
                                   </div>
                                   <div class="col-sm-6">
                                    <div class="from-group">
                                       <label class="control-label">ছবি</label>
                                       <input type="file" class="form-control border-form-control" name="photo" id="photo"  accept=".png, .jpg, .jpeg" onchange="loadFile(event)"/>

                                       <span class="text-danger" id="photo_error"></span>

                                    </div>
                                 </div>
                                </div>

                                <div class="row">
                                   <div class="col-sm-6">
                                      <div class="form-group">
                                         <label class="control-label">মোবাইল <span class="required">*</span></label>
                                         <input class="form-control border-form-control" name="mobile" id="client_mobile" value="{{ $client_data->mobile }}" placeholder="মোবাইল" type="text" >

                                        @error('mobile')
                                            <span class="text-danger" id="mobile_errors" >{{ $message }}</span>
                                        @enderror

                                         <span class="text-danger" id="mobile_error"></span>
                                      </div>
                                   </div>
                                   <div class="col-sm-6">
                                      <div class="form-group">
                                         <label class="control-label">ই-মেইল </label>
                                         <input class="form-control border-form-control" name="email" id="email" value="{{ $client_data->email }}" placeholder="iamosahan@gmail.com"  type="email">
                                      </div>
                                   </div>
                                </div>
                                <div class="row">
                                   <div class="col-sm-6">
                                      <div class="form-group">
                                         <label class="control-label">উপজেলাে </label>
                                         <select  class="form-control" name="upazila" id="upazila" onchange="get_location(this.value, 2, 'union') ">

                                          <option value="">সিলেক্ট</option>
                                          
                                          @foreach($upazila as $uzitem)

                                          <option value="{{ $uzitem->id }}" {{ ($uzitem->id == $client_data->upazila_id) ? "selected" : ''  }}>{{ $uzitem->name }}</option>

                                          @endforeach  
                                         </select>
                                      </div>
                                   </div>
                                   <div class="col-sm-6">
                                      <div class="form-group">
                                         <label class="control-label">ইউনিয়ন</label>
                                         <select  class="form-control" name="union" id="union">
                                            <option value="">সিলেক্ট</option>

                                            @foreach($union as $uitem)

                                          <option value="{{ $uitem->id }}" {{ ($uitem->id == $client_data->union_id) ? "selected" : ''  }}>{{ $uitem->name }}</option>

                                          @endforeach 
                                            
                                         </select>
                                      </div>
                                   </div>
                                </div>

                                <div class="row">
                                  <div class="col-sm-6">
                                    <div class="form-group">
                                      <label class="control-label">পোরসভা</label>
                                      <input class="form-control" type="text" id="porosova" name="porosova" placeholder="আপনার পোরসভার নাম লিখুন" value="{{$client_data->porosova}}" />
                                    </div>
                                  </div>
                                  <div class="col-sm-6">
                                    <div class="form-group">
                                      <label class="control-label">ওয়ার্ড</label>
                                      <input class="form-control" type="text" id="ward" name="ward" placeholder="আপনার ওয়ার্ড নং লিখুন" value="{{$client_data->ward}}" />
                                    </div>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label class="control-label">করোনা জোন</label>
                                      <select class="form-control" id="corona_zone" name="corona_zone">
                                        <option value="">করোনা জোন নির্বাচন করুন</option>
                                        <option value="1" @if($client_data->corona_zone == 1) Selected @endif>লাল</option>
                                        <option value="2" @if($client_data->corona_zone == 2) Selected @endif>হলুদ</option>
                                        <option value="3" @if($client_data->corona_zone == 3) Selected @endif>সবুজ</option>

                                        
                                      </select>
                                    </div>
                                  </div>
                                </div>

                                <div class="row">
                                   <div class="col-sm-6">
                                      <div class="form-group">
                                         <label class="control-label">নতুন পাসওয়ার্ড</label>
                                         <input class="form-control border-form-control" name="password" id="password" placeholder="****" type="password">
                                      </div>
                                   </div>
                                   <div class="col-sm-6">
                                      <div class="form-group">
                                         <label class="control-label">কনফার্ম পাসওয়ার্ড</label>
                                         <input class="form-control border-form-control" name="confirm_password" id="confirm_password" placeholder="****"  type="password">
                                      </div>
                                   </div>
                                </div>

                                <div class="row">
                                   <div class="col-sm-12">
                                      <div class="form-group">
                                         <label class="control-label">ঠিকানা </label>
                                         <textarea class="form-control border-form-control" name="address" id="address">{{ $client_data->address }}</textarea>
                                      </div>
                                   </div>
                                </div>

                                 <input type="hidden" name="user_id" value="{{ $client_data->user_id }}">
                                 <input type="hidden" name="client_id" value="{{ $client_data->client_id }}"> 

                                <div class="row">
                                   <div class="col-sm-12 text-right">
                                      <button type="submit" class="btn btn-success btn-lg"> আপডেট</button>
                                      {{-- <button type="button" class="btn btn-danger btn-lg"> বাতিল </button> --}}
                                   </div>
                                </div>
                             </form>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </section>

<script type="text/javascript">

  var url  = $('meta[name = path]').attr("content");
  var csrf    = $('mata[name = csrf-token]').attr("content");

//get all_location
function get_location(parent_id, type, target_id){


    $.ajaxSetup({
        
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({

        url: url + "/admin/get_location",
        type:"POST",
        dataType:"JSON",
        data:{
            parent_id:parent_id,
            type:type,
        },
        success:function(response){

            if (response.status == 'success') {

                var list = "<option value=''>সিলেক্ট করুন</option>";

                response.data.forEach(function(item){

                    list += "<option value='"+item.id+"'>"+item.name+"</option>";

                });

                $("#"+target_id).html(list);

            }else{

                $("#"+target_id).html("<option value=''>Not Found</option>");
            }

        }

     });

}


//image show onpage
 function loadFile(event) {
    var output_image = document.getElementById('output_image');
    output_image.src = URL.createObjectURL(event.target.files[0]);    
};


function profile_update(){

      var name = $('#client_name').val();
      var mobile = $('#client_mobile').val();
      var photo = $('#photo').val();

      var extension = photo.split('.').pop().toUpperCase();


      var error_status = true;

      if(photo != ''){

          if (extension!="PNG" && extension!="JPG" && extension!="GIF" && extension!="JPEG"){
              
            $('#photo_error').html('png, jpg, jpeg প্রদান করুন');
            error_status = false;
              
          }else{

            $('#photo_error').html('')
          }
      }

      if(name == ''){

          $('#name_error').html('নাম প্রদান করুন');
          $('#name_errors').html(' ');
          error_status = false;

      }else{

         $('#name_error').html('');


      }

      if(mobile == ''){

          $('#mobile_error').html('মোবাইল নাম্বার দিন');
          $('#mobile_errors').html(' ');
          error_status = false;

      }else{

          if(mobile.length == 11){

            if(mobile_number_validate(mobile)){

              $('#mobile_error').html('');

            }else{

              $('#mobile_error').html('সঠিক নাম্বার দিন');
              error_status = false;
            }

          }else{

            $('#mobile_error').html('সঠিক নাম্বার দিন');
              error_status = false;

          }

      }


      return error_status;
}

//bd phone number validation
function mobile_number_validate(mobile) {

  var bd_rgx = /\+?(88)?0?1[3256789][0-9]{8}\b/;

  if (mobile.match(bd_rgx)) {

    return true;

  }else {

    return false;
  }

}


</script>

@endsection

