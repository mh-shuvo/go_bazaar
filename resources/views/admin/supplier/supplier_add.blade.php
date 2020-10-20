@extends("layouts.admin")
@section("title","Dashboard")
@section("content")

    <!-- Start container-fluid -->
    <div class="container-fluid">

        <!-- start  -->
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title">সরবরাহকারীর তথ্য</h4>
                </div>

                <div class="col-lg-12">

                    <form class="form-validation" action="{{ route('supplier_store') }}" method="post" enctype="multipart/form-data"  data-parsley-validate="" onsubmit="return mobile_validate()">

                         @csrf 

                        <div class="form-group row">

                            <label for="upazila_id" class="col-md-2 form-control-label">জেলা<span class="text-danger">*</span></label>
                            <div class="col-md-4">
 
                                <select type='text' class="form-control" id="district_id" name="district_id" onchange="get_location(this.value, 3, 'upazila_id')"  parsley-trigger="change" required  data-parsley-required-message="উপজেলা সিলেক্ট করুন">
                                    <option value="">সিলেক্ট</option>
                    
                                    @foreach($data as $item)
                                    <option value="{{ $item->id }}" {{ (old('district_id') == $item->id) ? 'selected="selected"' : '' }}>{{ $item->en_name }}</option>
                                    @endforeach

                                </select>



                                @error('upazila_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                
                            </div>

                            <label for="union_id" class="col-md-2 form-control-label">উপজেলা<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <select type='text' class="form-control" id="upazila_id" name="upazila_id"  parsley-trigger="change" required data-parsley-required-message="ইউনিয়ন সিলেক্ট করুন" >
                                    <option value="">সিলেক্ট</option>
                                </select>

                                @error('upazila_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">

                            <label for="supplier_type" class="col-md-2 form-control-label">সরবরাহকারীর ধরন<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <select type='text'  class="form-control" id="supplier_type" name="supplier_type"  parsley-trigger="change" required data-parsley-required-message="সরবরাহকারীর ধরন সিলেক্ট করুন">
                                    <option value="">সিলেক্ট</option>
                                    @foreach($supplier_type as $sitem)
                                    <option value="{{ $sitem->id }}"  {{ (old('supplier_type') == $sitem->id) ? 'selected="selected"' : '' }}>{{ $sitem->name }}</option>
                                    @endforeach
                                </select>

                                @error('supplier_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <label for="shop_name" class="col-md-2 form-control-label">প্রতিষ্ঠানের নাম<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="shop_name" name="shop_name" value="{{ old('shop_name') }}" placeholder="প্রতিষ্ঠানের নাম প্রদান করুন"  parsley-trigger="change" required data-parsley-required-message="প্রতিষ্ঠানের নাম প্রদান করুন" >

                                @error('shop_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>
                        <div class="form-group row">

                            <label for="name" class="col-md-2 form-control-label">মালিকের নাম<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text"  class="form-control" name="name" value="{{ old('name') }}" id="name" placeholder="মালিকের নাম প্রদান করুন"  parsley-trigger="change"  required data-parsley-required-message="মালিকের নাম প্রদান করুন">

                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                {{-- required parsley-type="name" --}}
                            </div>

                            <label for="mobile" class="col-md-2 form-control-label">মোবাইল<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="mobile" id="mobile" value="{{ old('mobile') }}" placeholder="মোবাইল নাম্বার প্রদান করুন" >

                                <span class="text-danger" id="mobile_error"></span>
                                @error('mobile')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">

                            <label for="inputEmail3" class="col-md-2 form-control-label">ই-মেইল</label>
                            <div class="col-md-4">
                                <input type="email"  class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="ই-মেইল প্রদান করুন">
                            </div>

                            <label for="address" class="col-md-2 form-control-label">ঠিকানা<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text"  class="form-control" name="address" id="address" value="{{ old('address') }}" placeholder="ঠিকানা প্রদান করুন" parsley-trigger="change" required data-parsley-required-message="ঠিকানা প্রদান করুন">
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-md-2 form-control-label">ইউজারনেম<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input  id="username" name="username" value="{{ old('username') }}" type="text" placeholder="ইউজারনেম প্রদান করুন"  class="form-control"  parsley-trigger="change" required data-parsley-required-message='ইউজারনেম প্রদান করুন' >

                                @error('username')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="hori-pass1" class="col-md-2 form-control-label">পাসওয়ার্ড<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input id="hori-pass1" type="password" name="password" placeholder="Password"  class="form-control"  parsley-trigger="change" required data-parsley-required-message='পাসওয়ার্ড প্রদান করুন' autocomplete="new-password" minlength="8">

                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                           
                        </div>
                        <div class="form-group row">
                             <label for="shop_image" class="col-md-2 form-control-label">দোকানের ছবি</label>
                             <div class="col-md-4">
                                 <input id="shop_image" class="form-control" type="file" name="shop_image" />
                                  <img src="{{ asset('public/logo-black.png') }}" class="d-none" id="image_preview" style="height:70px; width:100px; border-radious: 5px;">
                             </div>
                             <label for="nid" class="col-md-2 form-control-label">জাতীয় পরিচয়পত্র নম্বরঃ<span class="text-danger">*</span></label>
                             <div class="col-md-4">
                                 <input type="text" name="nid" id="nid" class="form-control" placeholder="জাতীয় পরিচয়পত্র নম্বর দিন" required  data-parsley-required-message="জাতীয় পরিচয়পত্র নম্বর দিন">
                                  @error('nid')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                             </div>
                        </div>

                        <div class="form-group row">
                             <label for="trade_photo" class="col-md-2 form-control-label">ট্রেড লাইসেন্স এর ছবি:</label>
                             <div class="col-md-4">
                                 <input id="trade_photo" class="form-control" type="file" name="trade_photo" />
                                  <img src="{{ asset('public/logo-black.png') }}" class="d-none" id="trade_image_preview" style="height:70px; width:100px; border-radious: 5px;">
                             </div>
                             <label for="trade_id" class="col-md-2 form-control-label">ট্রেড লাইসেন্স এর নম্বর:<s:<span class="text-danger">*</span></label>
                             <div class="col-md-4">
                                 <input type="text" name="trade_id" id="trade_id" class="form-control" placeholder="ট্রেড লাইসেন্স এর নম্বর দিন"  required  data-parsley-required-message="ট্রেড লাইসেন্স এর নম্বর দিন">
                                  @error('trade_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                             </div>
                        </div>
                        <div class="form-group row">
                             <label for="permission" class="col-md-2 offset-sm-6 form-control-label">পারমিশন:</label>
                             <div class="col-md-4">
                                <select name="permission" id="permission" class="form-control" parsley-trigger="change" required  data-parsley-required-message="পারমিশন সিলেক্ট করুন">
                                    <option value="">পারমিশন নির্বাচন করুন</option>
                                    @foreach($permissions as $item)
                                    <option value="{{$item->id}}">{{$item->role_name}}</option>
                                    @endforeach
                                </select>
                                @error('permission')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                             </div>
                        </div>

                        
                        <div class="form-group row justify-content-end">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                    সাবমিট
                                </button>
                                <a href="{{ route('suppliers') }}" class="btn btn-danger waves-effect">
                                    বাতিল
                                </a>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
                <!-- end row -->
        </div>
        <!-- end -->

    </div>
    <!-- end container-fluid -->

@endsection

@section('js')

<script type="text/javascript">
//bd phone number validation
function mobile_validate() {

    var mobile = $('#mobile').val();

  var bd_rgx = /\+?(88)?0?1[3256789][0-9]{8}\b/;

  if(mobile == ''){

    $('#mobile_error').html('মোবাইল নাম্বার প্রদান করুন');

    return false;

  }else{

   if(mobile.length == 11){

        if (mobile.match(bd_rgx)) {

            $('#mobile_error').html('');

            return true;

        }else {

            $('#mobile_error').html('মোবাইল নাম্বার সঠিক নয়');

            return false;
        }

    }else{

        $('#mobile_error').html('মোবাইল নাম্বার সঠিক নয়');

        return false;

    }

  }

  
}

$(document).on('change','#shop_image',function(){
    $('#image_preview').addClass('d-none');
     readURL(this,'#image_preview');
     $('#image_preview').removeClass('d-none');
});

$(document).on('change','#trade_photo',function(){
    $('#trade_image_preview').addClass('d-none');
     readURL(this,'#trade_image_preview');
     $('#trade_image_preview').removeClass('d-none');
});

</script>



<script type="text/javascript" src="{{ asset('public/admin/assets/js/admin.js') }}"></script>
@endsection