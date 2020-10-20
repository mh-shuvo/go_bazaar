@extends("layouts.admin")
@section("title","Dashboard")
@section("content")


<style type="text/css">
    .padding-left-50 {
        padding-left: 50px !important;
    }

    .padding-left-100 {
        padding-left: 100px !important;
    }

</style>

<!-- Start container-fluid -->
<div class="container-fluid">
    {{-- row start --}}
    <div class="row">
        <div class="col-9">
            <div>
                <h4 class="header-title mb-3">ACL Update</h4>
            </div>
        </div>

    </div>
    <!-- end row -->

    {{-- start table row --}}
    <div class="row">
        <div class="col-md-12">
            {{-- <div class="card-box"> --}}
                <form class="form-group" name="acl_form" action="{{ route('acl.edit.action') }}" method="post">

                    @csrf

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="role_name" id="role_name" placeholder="Role name" value="{{ $data['role_name'] }}" required />

                            <input type="hidden" name="role_id" id="role_id" value="{{ $data['id'] }}" />
                        </div>

                        <div class="col-md-1">
                            <button class="btn btn-md btn-warning" type="submit">Update</button>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">

                            <table class="table table-stripped table-boarderd">

                                <thead>
                                    <th style="width: 60%;">Menu</th>
                                    <th>View</th>
                                    <th>Create</th>
                                    <th>Update</th>
                                    <th>Delete</th>
                                </thead>

                                <tbody>
                                @if(permission_check("customer"))
                                    <tr>
                                        <td> কাষ্টমারগন </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="customer_view" <?php echo (isset($data['widget']['customer'])) ? (in_array('view', $data['widget']['customer']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="customer_create" <?php echo (isset($data['widget']['customer'])) ? (in_array('create', $data['widget']['customer']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td></td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="customer_delete" <?php echo (isset($data['widget']['customer'])) ? (in_array('delete', $data['widget']['customer']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                    </tr>
                                @endif
                                @if(permission_check("productsell", "view"))
                                    <tr>
                                        <td> পন্য বিক্রি </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="productsell_view" <?php echo (isset($data['widget']['productsell'])) ? (in_array('view', $data['widget']['productsell']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @if(permission_check("product", "view"))

                                    <tr>
                                        <td> পন্য ব্যবস্থাপনা </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="product_view" <?php echo (isset($data['widget']['product'])) ? (in_array('view', $data['widget']['product']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="product_create" <?php echo (isset($data['widget']['product'])) ? (in_array('create', $data['widget']['product']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="product_edit" <?php echo (isset($data['widget']['product'])) ? (in_array('edit', $data['widget']['product']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="product_delete" <?php echo (isset($data['widget']['product'])) ? (in_array('delete', $data['widget']['product']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                    </tr>
                                    @endif
                                    @if(permission_check("wastage", "view"))
                                    <tr>
                                        <td> নষ্ট পন্য ব্যবস্থাপনা </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="wastage_view" <?php echo (isset($data['widget']['wastage'])) ? (in_array('view', $data['widget']['wastage']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="wastage_create" <?php echo (isset($data['widget']['wastage'])) ? (in_array('create', $data['widget']['wastage']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="wastage_edit" <?php echo (isset($data['widget']['wastage'])) ? (in_array('edit', $data['widget']['wastage']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="wastage_delete" <?php echo (isset($data['widget']['wastage'])) ? (in_array('delete', $data['widget']['wastage']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                    </tr>
                                    @endif
                                    @if(permission_check("stock", "view"))
                                    <tr>
                                        <td> স্টক ব্যবস্থাপনা </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="stock_view" <?php echo (isset($data['widget']['stock'])) ? (in_array('view', $data['widget']['stock']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="stock_create" <?php echo (isset($data['widget']['stock'])) ? (in_array('create', $data['widget']['stock']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="stock_edit" <?php echo (isset($data['widget']['stock'])) ? (in_array('edit', $data['widget']['stock']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="stock_delete" <?php echo (isset($data['widget']['stock'])) ? (in_array('delete', $data['widget']['stock']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                    </tr>
                                    @endif

                                    @if(permission_check("delivery", "view"))
                                    <tr>
                                        <td> ডেলিভারি ম্যান যোগ </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="delivery_view" <?php echo (isset($data['widget']['delivery'])) ? (in_array('view', $data['widget']['delivery']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="delivery_create" <?php echo (isset($data['widget']['delivery'])) ? (in_array('create', $data['widget']['delivery']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="delivery_edit" <?php echo (isset($data['widget']['delivery'])) ? (in_array('edit', $data['widget']['delivery']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="delivery_delete" <?php echo (isset($data['widget']['delivery'])) ? (in_array('delete', $data['widget']['delivery']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                    </tr>
                                    @endif
                                    @if(permission_check("acl", "view"))
                                    <tr>
                                        <td> ACL </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="acl_view" <?php echo (isset($data['widget']['acl'])) ? (in_array('view', $data['widget']['acl']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="acl_create" <?php echo (isset($data['widget']['acl'])) ? (in_array('create', $data['widget']['acl']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="acl_edit" <?php echo (isset($data['widget']['acl'])) ? (in_array('edit', $data['widget']['acl']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="acl_delete" <?php echo (isset($data['widget']['acl'])) ? (in_array('delete', $data['widget']['acl']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                    </tr>
                                    @endif
                                    @if(permission_check("employee", "view"))
                                    <tr>
                                        <td> কর্মী ব্যবস্থাপনা </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="employee_view" <?php echo (isset($data['widget']['employee'])) ? (in_array('view', $data['widget']['employee']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="employee_create" <?php echo (isset($data['widget']['employee'])) ? (in_array('create', $data['widget']['employee']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="employee_edit" <?php echo (isset($data['widget']['employee'])) ? (in_array('edit', $data['widget']['employee']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="employee_delete" <?php echo (isset($data['widget']['employee'])) ? (in_array('delete', $data['widget']['employee']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                    </tr>
                                    @endif
                                    @if(permission_check("offer", "view"))
                                    <tr>
                                        <td> অফার ব্যবস্থাপনা </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="offer_view" <?php echo (isset($data['widget']['offer'])) ? (in_array('view', $data['widget']['offer']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="offer_create" <?php echo (isset($data['widget']['offer'])) ? (in_array('create', $data['widget']['offer']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="offer_edit" <?php echo (isset($data['widget']['offer'])) ? (in_array('edit', $data['widget']['offer']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="offer_delete" <?php echo (isset($data['widget']['offer'])) ? (in_array('delete', $data['widget']['offer']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                    </tr>
                                    @endif
                                    @if(permission_check("sell"))
                                    <tr>
                                        <td> <h6>বিক্রয়</h6> </td>

                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @if(permission_check("sell", "productwise", "view"))
                                    <tr>
                                        <td class="padding-left-50"> পন্য ভিত্তিক বিক্রয় রিপোর্ট </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="sell_productwise_view" <?php echo (isset($data['widget']['sell']['productwise'])) ? (in_array('view', $data['widget']['sell']['productwise']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endif   
                                    @if(permission_check("sell", "daily", "view"))
                                    
                                    <tr>
                                        <td class="padding-left-50"> দৈনিক বিক্রয় রিপোর্ট </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="sell_daily_view" <?php echo (isset($data['widget']['sell']['daily'])) ? (in_array('view', $data['widget']['sell']['daily']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @if(permission_check("sell", "monthly", "view"))
                                    <tr>
                                        <td class="padding-left-50"> মাসিক বিক্রয় রিপোর্ট </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="sell_monthly_view" <?php echo (isset($data['widget']['sell']['monthly'])) ? (in_array('view', $data['widget']['sell']['monthly']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @if(permission_check("report", "salesman", "view"))
                                    <tr>
                                        <td class="padding-left-50">বিক্রয় কর্মী রিপোর্ট </td>

                                        <td>                                             <input type="checkbox" name="widget[]" value="report_salesman_view" <?php echo (isset($data['widget']['report']['salesman'])) ? (in_array('view', $data['widget']['report']['salesman']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @endif
                                    @if(permission_check("report","employesale","view") || permission_check("report","employepurchase","view"))
                                    <tr>
                                        <td> <h6>কর্মীর রিপোর্ট</h6> </td>

                                        <td>
                                            
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @if(permission_check("report","employesale","view"))
                                    <tr>
                                        <td class="padding-left-50"> বিক্রয় রিপোর্ট </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="report_employesale_view" <?php echo (isset($data['widget']['report']['employesale'])) ? (in_array('view', $data['widget']['report']['employesale']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endif
                                @if(permission_check("report","employepurchase","view"))
                                    <tr>
                                        <td class="padding-left-50"> পণ্য ক্রয় রিপোর্ট </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="report_employepurchase_view" <?php echo (isset($data['widget']['report']['employepurchase'])) ? (in_array('view', $data['widget']['report']['employepurchase']) ? 'checked' : '') : ''; ?>/>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endif
                                @endif

                                    @if(permission_check("order"))

                                    <tr>
                                        <td> <h6>অর্ডার</h6> </td>

                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td class="padding-left-50"> অর্ডার সমূহ </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="order_list_view" <?php echo (isset($data['widget']['order']['list'])) ? (in_array('view', $data['widget']['order']['list']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="padding-left-50"> অর্ডার রিপোর্ট </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="order_report_view" <?php echo (isset($data['widget']['order']['report'])) ? (in_array('view', $data['widget']['order']['report']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @if(permission_check("expanse"))
                                    <tr>
                                        <td> <h6>ব্যয় ব্যবস্থাপনা</h6> </td>

                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td class="padding-left-50"> ব্যয় ক্যাটাগরি </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_category_view" <?php echo (isset($data['widget']['expanse']['category'])) ? (in_array('view', $data['widget']['expanse']['category']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_category_create" <?php echo (isset($data['widget']['expanse']['category'])) ? (in_array('create', $data['widget']['expanse']['category']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_category_edit" <?php echo (isset($data['widget']['expanse']['category'])) ? (in_array('edit', $data['widget']['expanse']['category']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_category_delete" <?php echo (isset($data['widget']['expanse']['category'])) ? (in_array('delete', $data['widget']['expanse']['category']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                    </tr>
                                    @endif
                                @if(permission_check("expanse","entry"))
                                    <tr>
                                        <td class="padding-left-50"> ব্যয় এন্ট্রি </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_entry_view" <?php echo (isset($data['widget']['expanse']['entry'])) ? (in_array('view', $data['widget']['expanse']['entry']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_entry_create" <?php echo (isset($data['widget']['expanse']['entry'])) ? (in_array('create', $data['widget']['expanse']['entry']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_entry_edit" <?php echo (isset($data['widget']['expanse']['entry'])) ? (in_array('edit', $data['widget']['expanse']['entry']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_entry_delete" <?php echo (isset($data['widget']['expanse']['entry'])) ? (in_array('delete', $data['widget']['expanse']['entry']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                    </tr>
                                    @endif
                                @if(permission_check("report"))
                                    <tr>
                                        <td> <h6>রিপোর্ট</h6> </td>

                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @if(permission_check("report", "stock", "view"))
                                    <tr>
                                        <td class="padding-left-50"> স্টক রিপোর্ট </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="report_stock_view" <?php echo (isset($data['widget']['report']['stock'])) ? (in_array('view', $data['widget']['report']['stock']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @if(permission_check("report", "profitloss", "view"))
                                    
                                    <tr>
                                        <td class="padding-left-50"> লাভ ক্ষতি রিপোর্ট </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="report_profitloss_view" <?php echo (isset($data['widget']['report']['profitloss'])) ? (in_array('view', $data['widget']['report']['profitloss']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @if(permission_check("report", "expanse", "view"))
                                    
                                    <tr>
                                        <td class="padding-left-50"> ব্যায় রিপোর্ট </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="report_expanse_view" <?php echo (isset($data['widget']['report']['expanse'])) ? (in_array('view', $data['widget']['report']['expanse']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @if(permission_check("report", "balancestatement", "view"))
                                    
                                    <tr>
                                        <td class="padding-left-50"> ব্যালেন্স স্টেটমেন্ট </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="report_balancestatement_view" <?php echo (isset($data['widget']['report']['balancestatement'])) ? (in_array('view', $data['widget']['report']['balancestatement']) ? 'checked' : '') : ''; ?> />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    @endif
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            {{-- </div> --}}
        </div>
    </div>
    <!-- end row -->

</div>
<!-- end container-fluid -->

@endsection

@section('js')

<script type="text/javascript" src="{{ asset('public/admin/assets/js/admin.js') }}"></script>

@endsection
