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
                <h4 class="header-title mb-3">New ACL</h4>
            </div>
        </div>

    </div>
    <!-- end row -->

    {{-- start table row --}}
    <div class="row">
        <div class="col-md-12">
            {{-- <div class="card-box"> --}}
                <form class="form-group" name="acl_form" action="{{ route('acl.create.action') }}" method="post">

                    @csrf

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="role_name" id="role_name" placeholder="Role name" required />
                        </div>

                        <div class="col-md-1">
                            <button class="btn btn-md btn-primary" type="submit">Save</button>
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
                                            <input type="checkbox" name="widget[]" value="customer_view" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="customer_create" />
                                        </td>
                                        <td></td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="customer_delete" />
                                        </td>
                                    </tr>
                                @endif
                                @if(permission_check("productsell", "view"))
                                    <tr>
                                        <td> পন্য বিক্রি </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="productsell_view" />
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
                                            <input type="checkbox" name="widget[]" value="product_view" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="product_create" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="product_edit" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="product_delete" />
                                        </td>
                                    </tr>
                                    @endif
                                    @if(permission_check("wastage", "view"))
                                    <tr>
                                        <td> নষ্ট পন্য ব্যবস্থাপনা </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="wastage_view" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="wastage_create" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="wastage_edit" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="wastage_delete" />
                                        </td>
                                    </tr>
                                    @endif
                                    @if(permission_check("stock", "view"))
                                    <tr>
                                        <td> স্টক ব্যবস্থাপনা </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="stock_view" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="stock_create" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="stock_edit" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="stock_delete" />
                                        </td>
                                    </tr>

                                    @endif

                                    @if(permission_check("delivery", "view"))
                                    
                                    <tr>
                                        <td> ডেলিভারি ম্যান যোগ </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="delivery_view" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="delivery_create" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="delivery_edit" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="delivery_delete" />
                                        </td>
                                    </tr>
                                    @endif
                                    @if(permission_check("acl", "view"))
                                    <tr>
                                        <td> ACL </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="acl_view" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="acl_create" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="acl_edit" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="acl_delete" />
                                        </td>
                                    </tr>
                                    @endif
                                    @if(permission_check("employee", "view"))
                                    <tr>
                                        <td> কর্মী ব্যবস্থাপনা </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="employee_view" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="employee_create" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="employee_edit" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="employee_delete" />
                                        </td>
                                    </tr>
                                    @endif
                                    @if(permission_check("offer", "view"))
                                    
                                    <tr>
                                        <td> অফার ব্যবস্থাপনা </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="offer_view" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="offer_create" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="offer_edit" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="offer_delete" />
                                        </td>
                                    </tr>
                                    @endif
                                    @if(permission_check("sell"))
                                    <tr>
                                        <td> <h6>বিক্রয়</h6> </td>

                                        <td>
                                            
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @if(permission_check("sell", "productwise", "view"))
                                    <tr>
                                        <td class="padding-left-50"> পন্য ভিত্তিক বিক্রয় রিপোর্ট </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="sell_productwise_view" />
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
                                            <input type="checkbox" name="widget[]" value="sell_daily_view" />
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
                                            <input type="checkbox" name="widget[]" value="sell_monthly_view" />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @if(permission_check("report", "salesman", "view"))
                                    <tr>
                                        <td class="padding-left-50">বিক্রয় কর্মী রিপোর্ট </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="report_salesman_view" />
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
                                    @if(permission_check("report", "employesale", "view"))
                                    <tr>
                                        <td class="padding-left-50"> বিক্রয় রিপোর্ট </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="report_employesale_view" />
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
                                            <input type="checkbox" name="widget[]" value="report_employepurchase_view" />
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

                                        <td>
                                            
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td class="padding-left-50"> অর্ডার সমূহ </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="order_list_view" />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="padding-left-50"> অর্ডার রিপোর্ট </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="order_report_view" />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @if(permission_check("expanse"))
                                    <tr>
                                        <td> <h6>ব্যয় ব্যবস্থাপনা</h6> </td>

                                        <td>
                                            
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                   
                                    <tr>
                                        <td class="padding-left-50"> ব্যয় ক্যাটাগরি </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_category_view" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_category_create" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_category_edit" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_category_delete" />
                                        </td>
                                    </tr>
                                @endif
                                @if(permission_check("expanse","entry"))
                                    <tr>
                                        <td class="padding-left-50"> ব্যয় এন্ট্রি </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_entry_view" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_entry_create" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_entry_edit" />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="widget[]" value="expanse_entry_delete" />
                                        </td>
                                    </tr>
                                @endif
                                @if(permission_check("report"))
                                    <tr>
                                        <td> <h6>রিপোর্ট</h6> </td>

                                        <td>
                                            
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @if(permission_check("report", "stock", "view"))
                                    <tr>
                                        <td class="padding-left-50"> স্টক রিপোর্ট </td>

                                        <td>
                                            <input type="checkbox" name="widget[]" value="report_stock_view" />
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
                                            <input type="checkbox" name="widget[]" value="report_profitloss_view" />
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
                                            <input type="checkbox" name="widget[]" value="report_expanse_view" />
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
                                            <input type="checkbox" name="widget[]" value="report_balancestatement_view" />
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
