@extends("layouts.admin")
@section("title","ব্যালেন্স স্টেটম্যান্ট")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">ব্যালেন্স স্টেটম্যান্ট</h4>
                </div>
            </div>
        </div>
        <!-- end row -->
             <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                              <form method="GET" action="{{route('report.balance_statement.download')}}" target="_blank">
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
