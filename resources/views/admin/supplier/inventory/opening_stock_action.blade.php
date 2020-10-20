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
            {{-- <div class="card-box"> --}}
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label">ক্যাটাগরিঃ {{ $category_name }} </label>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label">সাব ক্যাটাগরিঃ {{ $sub_category_name }} </label>
                    </div>
                </div>
            </div>
            {{-- </div> --}}
        </div>
    </div>

    <div class="row">
        <form action="{{ route('stock.opening.save') }}" method="post" class="form-horizontal">
            @csrf

            <div class="col-sm-12">
                <table class="table table-stripped table-bordered">
                    <thead>
                        <tr>
                            <th>পণ্যের নাম</th>
                            <th width="120px">ক্রয় মুল্য</th>
                            <th width="120px">বিক্রয় মুল্য</th>
                            <th width="120px">পরিমান</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>
                                <input name="buying_price[]" id="buying_price" type="text"
                                    value="{{ $item->buying_price }}" class="form-control" />

                                <input name="product_id[]" id="product_id" type="hidden" value="{{ $item->product_id }}"
                                    class="form-control" />

                                <input name="inventory_id[]" id="inventory_id" type="hidden" value="{{ $item->id }}"
                                    class="form-control" />
                            </td>
                            <td>
                                <input name="selling_price[]" id="selling_price" type="text"
                                    value="{{ $item->selling_price }}" class="form-control" />
                            </td>
                            <td>
                                <input name="quantity[]" id="quantity" type="text" value="{{ $item->quantity }}"
                                    class="form-control" />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <button class="btn btn-primary" name="save" type="submit">Save</button>

                <a href="{{ route('stock.opening') }}">
                    <button class="btn btn-danger" type="button">Cancel</button>
                </a>

            </div>
        </form>

    </div>

    @endsection

    @section('js')
    <script src="{{ asset('public/admin/assets/js/pages/stock.js') }}"></script>
    @endsection