@extends("layouts.admin")
@section("title","ব্যায় রিপোর্ট")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">ব্যায় রিপোর্ট</h4>
                </div>
            </div>
        </div>
        <!-- end row -->
             <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                              <form method="get" action="{{route('expense.report.download')}}" target="_blank">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">তারিখ(হতে) নির্বাচন করুন</label>
                                            <input type="text" class="form-control datepicker" id="from_date" name="from_date">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">তারিখ(পর্যন্ত) নির্বাচন করুন</label>
                                            <input type="text" class="form-control datepicker" id="to_date" name="to_date">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">ব্যায় ক্যাটাগরি</label>
                                            <select class="form-control" name="account_head">
                                                <option value="">ক্যাটাগরি নির্বাচন করুন</option>
                                                @foreach($heads as $head)
                                                <option value="{{$head->id}}">{{$head->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
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
