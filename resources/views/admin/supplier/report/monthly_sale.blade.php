@extends("layouts.admin")
@section("title","মাসিক বিক্রয় রিপোর্ট")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">মাসিক বিক্রয় রিপোর্ট</h4>
                </div>
            </div>
        </div>
        <!-- end row -->
             <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <form method="get" action="{{route('report.sale.data')}}" target="__blank">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">মাস নির্বাচন করুন</label>
                                            <input type="text" class="form-control monthpicker" id="month" name="month"></select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <button class="btn btn-primary btn-block download" style="margin-top:30px;"><i class="fa fa-download"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        {{-- start table row --}}


    </div>
    <!-- end container-fluid -->
@endsection
