@extends("layouts.admin")
@section("title","বিক্রয় কর্মী রিপোর্ট")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">বিক্রয় কর্মী রিপোর্ট</h4>
                </div>
            </div>
        </div>
        <!-- end row -->
             <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                              <form method="get" action="{{route('report.salesman.download')}}" target="_blank">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">তারিখ(হতে) নির্বাচন করুন</label>
                                            <input type="text" class="form-control" id="from_date" name="from_date">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">তারিখ(পর্যন্ত) নির্বাচন করুন</label>
                                            <input type="text" class="form-control" id="to_date" name="to_date">
                                        </div>
                                    </div>
                                    @if(Auth::user()->user_type == 2)
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">বিক্রয় কর্মী <span class="text-danger">*</span></label>
                                            <select class="form-control" id="employe_id" name="employe_id" required="">
                                                <option value="">বিক্রয় কর্মী নির্বাচন করুন</option>
                                                @foreach($employes as $employe)
                                                <option value="{{$employe->id}}">{{$employe->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-primary btn-block" style="margin-top:30px;"><i class="fa fa-print"></i> প্রিন্ট</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
    </div>
    <!-- end container-fluid -->
@endsection
