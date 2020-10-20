@extends("layouts.admin")
@section("title","Order Report")
@section("content")
    <!-- Start container-fluid -->
    <div class="container-fluid">
        {{-- row start --}}
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="header-title mb-3">অর্ডার রিপোর্ট</h4>
                </div>
            </div>
        </div>
        <!-- end row -->
             <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <form method="get" action="{{route('report.order.download')}}" target="__blank">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">রিপোর্টের ধরণ নির্বাচন করুন</label>
                                            <select class="form-control" id="reportType" name="type">
                                                <option value="">রিপোর্টের ধরণ</option>
                                                <option value="1">দৈনিক রিপোর্ট</option>
                                                <option value="2">মাসিক রিপোর্ট</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 d-none daily">
                                        <div class="form-group">
                                            <label class="control-label">তারিখ নির্বাচন করুন</label>
                                            <input type="text" class="form-control datepicker" id="from_date" name="date">
                                        </div>
                                    </div>

                                    <div class="col-sm-3 d-none monthly">
                                        <div class="form-group">
                                            <label class="control-label">মাস নির্বাচন করুন</label>
                                            <input type="text" class="form-control monthpicker" name="month"></select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">অর্ডার অরিজিন</label>
                                            <select class="form-control origin" id="origin" name="origin">
                                                <option value="">সিলেক্ট</option>
                                                <option value="1">ওয়েবসাইট</option>
                                                <option value="2">পস</option>
                                            </select>
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

@section('js')
<script type="text/javascript">

$(document).on('change','#reportType',function(){
    let type = $(this).val();
    $(".daily").addClass('d-none');
    $(".monthly").addClass('d-none');
    if(type == 1){
        $(".daily").removeClass('d-none');
    }
    else if(type == 2){
        $(".monthly").removeClass('d-none');
    }

});
</script>

@endsection