@extends("layouts.admin")
@section("title","Dashboard")
@section("content")

    <!-- Start container-fluid -->
    <div class="container-fluid">

        <!-- start  -->
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title">সরবরাহকারীর প্রোফাইল</h4>
                </div>

                <div class="col-lg-12">

                    @if ($message = Session::get('success'))

                    <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
                      <strong>{{ $message }}</strong>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>

                @endif

                    <form class="form-validation" action="{{ route('supplier_profile_update') }}" method="post" enctype="multipart/form-data" >

                         @csrf 

                        <div class="form-group row">

                            <label for="district_id" class="col-md-2 form-control-label">জেলা<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                {{-- required parsley-type="upazila_id" --}}
                                <select type='text'   class="form-control" id="district_id" name="district_id" onchange="get_location(this.value, 3, 'upazila_id')" parsley-trigger="change"  required>
                                    <option value="">সিলেক্ট</option>
                    
                                    @foreach($data as $item)
                                    <option value="{{ $item->id }}" {{  ($supplier->district_id == $item->id) ? 'selected="selected"' : '' }}>{{ $item->en_name }}</option>
                                    @endforeach

                                </select>

                                @error('district_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                
                            </div>

                            <label for="upazila_id" class="col-md-2 form-control-label">উপজেলা<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <select type='text'  class="form-control" id="upazila_id" name="upazila_id" parsley-trigger="change"  required>

                                    @foreach($upazila_data as $uitem)
                                    <option value="{{ $uitem->id }}" {{  ($supplier->upazila_id == $uitem->id) ? 'selected="selected"' : '' }}>{{ $uitem->en_name }}</option>
                                    @endforeach

                                </select>

                                @error('upazila_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">

                            <label for="supplier_type" class="col-md-2 form-control-label">সরবরাহকারীর ধরন<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <select type='text'  class="form-control" id="supplier_type" name="supplier_type" parsley-trigger="change"  required >
                                    <option value="">সিলেক্ট</option>
                                    @foreach($suppliers_type as $sitem)

                                    <option value="{{ $sitem->id }}" {{ (old('suppliertype')? old('suppliertype') : $supplier->supplier_types == $sitem->id) ? 'selected="selected"' : '' }}>{{ $sitem->name }}</option>
                                    @endforeach
                                </select>

                                @error('suppliertype')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                       

                            <label for="shop_name" class="col-md-2 form-control-label">প্রতিষ্ঠানের নাম<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="shop_name" name="shop_name" value="{{ (old('shop_name')) ? old('shop_name') : $supplier->shop_name }}" placeholder="প্রতিষ্ঠানের নাম প্রদান করুন" parsley-trigger="change"  required >

                                @error('shop_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>
                        <div class="form-group row">

                            <label for="name" class="col-md-2 form-control-label">মালিকের নাম<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text"  class="form-control" name="name" value="{{ (old('name')) ? old('name') : $supplier->name }}" id="name" placeholder="মালিকের নাম প্রদান করুন" parsley-trigger="change"  required >

                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                {{-- required parsley-type="name" --}}
                            </div>

                            <label for="mobile" class="col-md-2 form-control-label">মোবাইল<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="mobile" id="mobile" value="{{ (old('mobile')) ? old('mobile') : $supplier->mobile }}" placeholder="মোবাইল নাম্বার প্রদান করুন" parsley-trigger="change"  required >
                                @error('mobile')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">

                            <label for="inputEmail3" class="col-md-2 form-control-label">ই-মেইল</label>
                            <div class="col-md-4">
                                <input type="email"  class="form-control" id="email" name="email" value="{{ (old('email')) ? old('email') : $supplier->email }}" placeholder="ই-মেইল প্রদান করুন">
                            </div>

                            <label for="address" class="col-md-2 form-control-label">ঠিকানা<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text"  class="form-control" name="address" id="address" value="{{ (old('address')) ? old('address') : $supplier->address }}" placeholder="ঠিকানা প্রদান করুন" parsley-trigger="change"  required >
                                 @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-md-2 form-control-label">ইউজারনেম<span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input id="username" name="username" value="{{ (old('username')) ? old('username') : Auth::user()->username }}" type="text" placeholder="ইউজারনেম প্রদান করুন"  class="form-control" parsley-trigger="change"  required>

                                @error('username')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="hori-pass1" class="col-md-2 form-control-label">পাসওয়ার্ড</label>
                            <div class="col-md-4">
                                <input id="hori-pass1" type="password" name="password" placeholder="Password"  class="form-control">

                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                           
                        </div>
                        <div class="form-group row">
                             <label for="shop_image" class="col-md-2 form-control-label">দোকানের ছবি</label>
                             <div class="col-md-4">
                                 <input id="shop_image" class="form-control" type="file" name="shop_image" />
                                 @if(!empty($supplier->shop_image))
                                  <img src="{{ asset('public/upload/supplier') }}/{{$supplier->shop_image}}" id="image_preview" style="height:70px; width:100px; border-radious: 5px;">
                                  @else
                                   <img src="{{ asset('public/logo-black.png') }}" class="d-none" id="image_preview" style="height:70px; width:100px; border-radious: 5px;">
                                  @endif
                             </div>
                               <label for="nid" class="col-md-2 form-control-label">জাতীয় পরিচয়পত্র নম্বরঃ</label>
                             <div class="col-md-4">
                                 <input type="text" name="nid" id="nid" class="form-control" placeholder="জাতীয় পরিচয়পত্র নম্বর দিন" value="{{ (old('nid')) ? old('nid') : $supplier->nid }}">
                                 @error('nid')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                             </div>
                        </div>

                        <div class="form-group row">
                             <label for="trade_photo" class="col-md-2 form-control-label">ট্রেড লাইসেন্স এর ছবি:</label>
                             <div class="col-md-4">
                                 <input id="trade_photo" class="form-control" type="file" name="trade_photo" />
                                  @if(!empty($supplier->trade_photo))
                                   <img src="{{ asset('public/upload/supplier') }}/{{$supplier->trade_photo}}" id="trade_image_preview" style="height:70px; width:100px; border-radious: 5px;">
                                  @else 
                                  <img src="{{ asset('public/logo-black.png') }}" class="d-none" id="trade_image_preview" style="height:70px; width:100px; border-radious: 5px;">
                                  @endif
                             </div>
                             <label for="trade_id" class="col-md-2 form-control-label">ট্রেড লাইসেন্স এর আইডি:<span class="text-danger">*</span></label>
                             <div class="col-md-4">
                                 <input type="text" name="trade_id" id="trade_id" class="form-control" value="{{ (old('trade_id')) ? old('trade_id') : $supplier->trade_id }}" placeholder="ট্রেড লাইসেন্স এর আইডি দিন">
                                  @error('trade_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                             </div>
                        </div>

                        <input type="hidden" name="row_id" value="{{ $supplier->id }}">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        
                        <div class="form-group row justify-content-end">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-warning waves-effect waves-light mr-1">
                                    আপডেট করুন
                                </button>
                                <button type="reset" class="btn btn-danger waves-effect">
                                    বাতিল
                                </button>
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
    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
        $("#success-alert").slideUp(500);
    });
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