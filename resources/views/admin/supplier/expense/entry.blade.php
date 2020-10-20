@extends("layouts.admin")
@section("title","ব্যয় বিভাগ তালিকা")
@section("content")
<!-- Start container-fluid -->
<div class="container-fluid">
    {{-- row start --}}
    <div class="row">
        <div class="col-9">
            <div>
                <h4 class="header-title mb-3">ব্যয় তালিকা</h4>
            </div>
        </div>
        <div class="col-3">
            @if (permission_check('expanse', 'entry', 'create'))
            <button type="button" class="btn btn-sm btn-teal btn-bordered-success float-right  mb-3 AddExpenseEntry"> <i
                    class="ti-plus"></i> নতুন যোগ করুন</button>
            @endif
        </div>
    </div>
    <!-- end row -->

    {{-- start table row --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
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
                            <label class="control-label">ব্যয় ক্যাটাগরি</label>
                            <select class="form-control" id="filter_account_head">
                                <option value="">ক্যাটাগরি নির্বাচন করুন</option>
                                @foreach($heads as $head)
                                <option value="{{$head->id}}">{{$head->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-primary btn-block FilterResult" style="margin-top:30px;"><i
                                class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-sm-12">

        <div class="card-box">
            <div class="">
                <table
                    class="table table-bordered data-table table table-hover mails m-0 table table-actions-bar table-centered entry_table"
                    style="width: 100%;">

                    <thead>

                        <tr>

                            <th>নং</th>
                            <th>ক্যাটাগরি</th>
                            <th>টাকা</th>
                            <th>নোট</th>
                            <th width="100px">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

</div>
<!-- end container-fluid -->

<!-- user add modal content -->
<div id="AddExpenseEntryModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">ব্যয় ক্যাটেগরি</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form class="form-validation" id="AddExpenseEntryForm" action="javascript:void(0)">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="account_head" class="form-control-label">ক্যাটাগরি<span
                                class="text-danger">*</span></label>
                        <select class="form-control" id="account_head" name="account_head">
                            <option value="">ক্যাটাগরি নির্বাচন করুন</option>
                            @foreach($heads as $head)
                            <option value="{{$head->id}}">{{$head->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">টাকার পরিমান</label>
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="টাকার পরিমান">
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">নোট</label>
                        <textarea id="note" name="note" placeholder="নোট লিখুন..." class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id">

                    <button type="submit" id="category_save_button"
                        class="btn btn-primary waves-effect waves-light">সাবমিট</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">বাতিল</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection

@section('js')
<script type="text/javascript">

var edit_permission = 0, delete_permission = 0;

edit_permission = {{ permission_check('expanse', 'entry', 'edit') ? 1 : 0 }};
delete_permission = {{ permission_check('expanse', 'entry', 'delete') ? 1 : 0 }};

$(function () {

    var category_table = $('.entry_table').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        serverSide: true,
        processing: true,

        ajax: "{{ route('expense.entry') }}",
        ajax: {
            url: "{{ route('expense.entry') }}",
            "data": function (e) {

                    e.from_date = $('#from_date').val() || 0,
                    e.to_date = $('#to_date').val() || 0
                    e.account_head = $('#filter_account_head').val() || 0

            }
        },

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'head_name', name: 'head_name'},

            {data: 'amount', name: 'amount'},

            {data: 'note', name: 'note'},
            

            { data: 'id', name: 'id', render:function(data, type, row, meta){

                var action = edit_permission ? "<a href='javascript:void(0)' class='btn btn-primary btn-sm EditExpenseEntry' data-row='"+meta.row+"'>সম্পাদন</a>" : "";
                
                action += delete_permission ? "<a href='javascript:void(0)' class='btn btn-danger btn-sm DeleteExpenseEntry' data-row='"+meta.row+"'>বাতিল</a>" : "";

                return action;

            }},

        ]

    });



  });

$(document).on('click','.FilterResult',function(){
    $('.entry_table').DataTable().draw(true);
});

</script>

<script type="text/javascript" src="{{ asset('public/admin/assets/js/expense.js') }}"></script>
@endsection