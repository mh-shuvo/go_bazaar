@extends("layouts.admin")
@section("title","স্টকের তালিকা")

@section("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-10">
            <div>
                <h4 class="header-title mb-3"> ওপেনিং স্টক </h4>
            </div>
        </div>

        <div class="col-12">
            @if ($message = Session::get('success'))

            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>

                <strong>{{ $message }}</strong>
            </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('stock.opening.action') }}" method="post" >
                @csrf

            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label">ক্যাটাগরি</label>
                        <select class="form-control" id="filter_category" name="filter_category" required>
                            <option value=""> নির্বাচন করুন </option>
                            @foreach (App\Category::where('type','1')->get() as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label">সাব ক্যাটাগরি</label>
                        <select class="form-control" id="filter_sub_category" name="filter_sub_category" required>
                            <option value=""> নির্বাচন করুন </option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-2">
                    <button class="btn btn-primary btn-block FilterResult" style="margin-top: 30px;">
                        Entry
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>

    @endsection

    @section('js')
    <script src="{{ asset('public/admin/assets/js/pages/stock.js') }}"></script>
    @endsection