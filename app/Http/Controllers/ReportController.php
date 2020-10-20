<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Order;
use App\Supplier;
use App\Client;
use App\Employe;
use DataTables;
use PDF;
use converter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
	public function productReport() {
		return view('admin.supplier.report.product');
	}

	public function productReportData(Request $request) {

		if ($request->ajax()) {
			if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}

			$query = "SELECT products.name as product_name, SUM(inventories.debit) AS quantity, SUM(inventories.debit * inventories.selling_price) AS total_sale FROM inventories

            INNER JOIN products ON inventories.product_id = products.id AND inventories.supplier_id = $supplier_id AND products.supplier_id = $supplier_id AND inventories.deleted_at IS NULL
            WHERE inventories.type = 2 AND inventories.`status` = 2 AND products.deleted_at IS NULL ";
			if ($request->product != 0) {
				$query .= "AND products.id = $request->product AND inventories.product_id = $request->product
                ";
			}
			if ($request->category != 0) {
				$query .= "AND products.category_id = $request->category ";
			}
			if ($request->sub_category != 0) {
				$query .= "AND products.sub_category_id = $request->sub_category ";
			}
			if ($request->from_date != 0) {
				$from_date = $request->from_date . ' 00:00:00';

				$query .= "AND inventories.created_at >= '$from_date' ";
			}
			if ($request->to_date != 0) {
				$to_date = $request->to_date . ' 23:59:59';
				$query .= "AND inventories.created_at <= '$to_date'";
			}

		
			$query .= "GROUP BY inventories.product_id ";
			$data = DB::select(DB::raw($query));
			return Datatables::of($data)
				->addIndexColumn()

				->make(true);
		}
	}
	
	public function orderReport()
	{
		return view('admin.supplier.report.order');
	}

	public function orderReportDownload(Request $request)
	{

		$date_month;
		if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}

		$query = "SELECT inventories.order_id,clients.name as client_name, orders.client_id, DATE(orders.created_at) AS created_at, SUM(inventories.debit * inventories.selling_price) AS total_amount,orders.discount, SUM(inventories.debit * inventories.selling_price) AS net_amount,orders.origin FROM orders JOIN inventories  ON inventories.order_id = orders.order_id AND inventories.supplier_id = $supplier_id  LEFT JOIN clients ON clients.id = inventories.client_id AND clients.deleted_at IS NULL WHERE inventories.`type` = 2 AND inventories.`status` = 2 AND orders.deleted_at IS NULL AND inventories.deleted_at IS NULL ";
		
		if($request->type == 1){
				$date_month = $request->date;
				$query.="AND DATE(inventories.created_at)='$request->date' ";
		}

		else{
			$extract = explode('-', $request->month);
			$query.="AND MONTH(inventories.created_at) = '$extract[0]' AND YEAR(inventories.created_at) = '$extract[1]' ";

			$date_month = $request->month;
		}

		if(isset($request->origin)){
			$query.="AND orders.origin='$request->origin' ";
		}

		$query .= "GROUP BY inventories.order_id ";
		$data = DB::select(DB::raw($query));
		$type = $request->type;
		$shop = Supplier::find($supplier_id);

		$data = [
				'shop' => $shop->shop_name,
				'shop_image' => $shop->shop_image,
				'data' => $data,
				'type' => $type,
				'date_month' => $date_month,
			];

		$pdf = PDF::loadView('download.order_report',$data);
		return $pdf->stream('Order Report '.date('dMY').'.pdf');
		
	}

	public function dailySaleReport()
	{
		return view('admin.supplier.report.daily_sale');
	}

	public function monthlySaleReport()
	{
		return view('admin.supplier.report.monthly_sale');
	}

	public function saleReportData(Request $request){
		$report_name = "দৈনিক বিক্রয় রিপোর্ট";
		$dateText = '';
		if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}
		$query = "SELECT products.name,clients.name as client_name,SUM(inventories.debit) as quantity,inventories.selling_price, SUM(inventories.debit * inventories.selling_price) AS amount FROM inventories JOIN products ON products.id = inventories.product_id AND products.deleted_at IS NULL LEFT JOIN clients ON clients.id = inventories.client_id AND clients.deleted_at IS NULL WHERE inventories.`type` = 2 AND inventories.`status` = 2 AND inventories.deleted_at IS NULL AND inventories.supplier_id='$supplier_id' ";
			
			if(isset($request->from_date)){
				$dateText = date('d M Y',strtotime($request->from_date));
				$query.="AND DATE(inventories.created_at)='$request->from_date' ";
			}

			if(isset($request->month)){

				$month = '1-'.$request->month;
				$dateText = date('M Y',strtotime($month));
				$report_name = "মাসিক বিক্রয় রিপোর্ট";

				$extract = explode('-', $request->month);
				$query.="AND MONTH(inventories.created_at) = '$extract[0]' AND YEAR(inventories.created_at) = '$extract[1]' ";
			}

			$query .= "GROUP BY inventories.product_id ";
			$products = DB::select(DB::raw($query));

			$shop = Supplier::find($supplier_id);
			$data = [
					'shop' => $shop->shop_name,
					'shop_image' => $shop->shop_image,
					'report_name' => $report_name,
					'products' => $products,
					'date_text' => $dateText
				];

			$pdf = PDF::loadView('download.sale_report',$data);
			return $pdf->stream('Sale Report '.date('d M Y').'.pdf');
			
		// $pdf = PDF::loadView('download.sale_report',compact('data'));
        // return $pdf->stream('Sale Report.pdf');
			// return view('download.sale_report',compact('data'));
	}


	public function profitLoassReport()
	{
		return view('admin.supplier.report.profit_loss');
	}
	public function profitLoassReportData(Request $request)
	{
		
		if ($request->ajax()) {

			if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}

			$query = "SELECT products.name as product_name,SUM(inventories.debit) as total_qty, SUM(inventories.debit * inventories.selling_price) AS total_sale,SUM(inventories.debit * inventories.buying_price) AS total_purchase FROM inventories

            INNER JOIN products ON inventories.product_id = products.id AND inventories.supplier_id = $supplier_id AND products.supplier_id = $supplier_id AND inventories.deleted_at IS NULL
            WHERE inventories.`status` != 3 AND inventories.type = 2 AND products.deleted_at IS NULL ";

			if ($request->from_date != 0) {
				$from_date = $request->from_date;

				$query .= "AND date(inventories.created_at) >= '$from_date' ";
			}
			if ($request->to_date != 0) {
				$to_date = $request->to_date;
				$query .= "AND date(inventories.created_at) <= '$to_date'";
			}

		
			$query .= "GROUP BY inventories.product_id ";
			$data = DB::select(DB::raw($query));
			return Datatables::of($data)
				->addIndexColumn()

				->make(true);
		}
	}

	public function DownloadProfitLosskReport(Request $request)
	{
		$products = [];
		if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}

		$request->from_date = $request->from_date!=null?$request->from_date:0;
		$request->to_date = $request->to_date!=null?$request->to_date:0;

		$query = "SELECT products.name as product_name,SUM(inventories.debit) as total_sale_qty, SUM(inventories.debit * inventories.selling_price) AS total_sale,SUM(inventories.debit * inventories.buying_price) AS total_purchase FROM inventories

            INNER JOIN products ON inventories.product_id = products.id AND inventories.supplier_id = $supplier_id AND products.supplier_id = $supplier_id AND inventories.deleted_at IS NULL
            WHERE inventories.`status` != 3  AND inventories.type = 2  AND products.deleted_at IS NULL ";

			if ($request->from_date != 0) {
				$from_date = $request->from_date;

				$query .= "AND date(inventories.created_at) >= '$from_date' ";
			}
			if ($request->to_date != 0) {
				$to_date = $request->to_date;
				$query .= "AND date(inventories.created_at) <= '$to_date'";
			}

		
			$query .= "GROUP BY inventories.product_id ";
			$products = DB::select(DB::raw($query));
		    $shop = Supplier::find($supplier_id);

		    $dateText='';

		    $from_date = $request->from_date!=null ? date('d-m-Y',strtotime($request->from_date)):null; 
			$to_date = $request->to_date!=null ? date('d-m-Y',strtotime($request->to_date)):null; 

			if($from_date!=null){
				$dateText .= converter::en2bn($from_date).' হতে ';
			}
			if($to_date!=null){
				$dateText .= converter::en2bn($to_date).' পর্যন্ত';
			}

			$data = [
				'shop' => $shop->shop_name,
				'shop_image' => $shop->shop_image,
				'products' => $products,
				'dateText' => $dateText,
			];

		$pdf = PDF::loadView('download.profit_loss_report',$data);
		return $pdf->stream('Profit Loss Report'.date('dMY').'.pdf');
	}

	public function BalanceStatement()
	{
		return view('admin.supplier.report.balance_statement');
		
	}

	public function DownloadBalanceStatement(Request $request)
	{
		// echo $this->TotalStock($request);
		// echo $this->TotalSale($request);
		// echo $this->TotalExpense($request);

		   $dateText='';

		    $from_date = $request->from_date!=null ? date('d-m-Y',strtotime($request->from_date)):null; 
			$to_date = $request->to_date!=null ? date('d-m-Y',strtotime($request->to_date)):null; 

			if($from_date!=null){
				$dateText .= converter::en2bn($from_date).' হতে ';
			}
			if($to_date!=null){
				$dateText .= converter::en2bn($to_date).' পর্যন্ত';
			}

		if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}

		$shop = Supplier::find($supplier_id);

			$data = [
				'shop' => $shop->shop_name,
				'shop_image' => $shop->shop_image,
				'total_sale' => $this->ProfitLoss($request)['total_sale'],
				'total_purchase' => $this->ProfitLoss($request)['total_purchase'],
				'profit_loss' => $this->ProfitLoss($request)['profit_loss'],
				'total_expense' => $this->TotalExpense($request),
				'total_stock' => $this->TotalStock($request),
				'dateText' => $dateText,
			];

		$pdf = PDF::loadView('download.balance_statement',$data);
		return $pdf->stream('Balance Statement'.date('dMY').'.pdf');
	}

	public function TotalExpense($request)
	{
		if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}
		$query = DB::table('expenses')
    					->select('expenses.*')
    					->leftJoin('account_heads as AH',function($join){
    						$join->on('AH.id','=','expenses.head')
    								->on('AH.supplier_id','=','expenses.supplier_id');
    					})->where('expenses.supplier_id','=',$supplier_id)
    					->whereNull('expenses.deleted_at');

    			if ($request->from_date != 0) {
					$query->whereDate('expenses.created_at','>=', $request->from_date);
				}
				if ($request->to_date != 0) {
					$query->whereDate('expenses.created_at','<=', $request->to_date);
				}

				if ($request->account_head != 0) {
					$query->where('expenses.head','=', $request->account_head);
				}

    	$total_expense = $query->orderBy('expenses.id','desc')->sum('expenses.amount');

    	return $total_expense;
	}

	public function ProfitLoss($request)
	{
		$from_date = $request->from_date!=null?$request->from_date:0;
		$to_date = $request->to_date!=null?$request->to_date:0;
		if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}

		// DB::enableQueryLog();
		$query = Inventory::select(DB::raw('SUM(selling_price * debit) as total_sale_price'),DB::raw('SUM(buying_price * debit) as total_purchase_price') )->where('supplier_id',$supplier_id)->where('type',2)->where('status',2);

		if ($from_date != 0 && $to_date != 0) {
			$query->whereBetween(DB::raw('date(created_at)'), [$from_date,$to_date]);
		}
		
		$total_sale_data = $query->groupBy('id')->get();
		// dd(DB::getQueryLog());
		$total_sale = 0;
		$total_purchase = 0;
		foreach ($total_sale_data as  $item) {
			$total_sale+=$item->total_sale_price;
			$total_purchase+=$item->total_purchase_price;
		}
		return [
			'total_sale' => $total_sale,
			'total_purchase' => $total_purchase,
			'profit_loss' => $total_sale - $total_purchase,
		];

	}

	public function TotalStock($request){

		$request->from_date = $request->from_date!=null?$request->from_date:0;
		$request->to_date = $request->to_date!=null?$request->to_date:0;
		if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}
    	
    	$total_stock = 0;

    	$query="SELECT inventories.id,products.id AS product_id,products.name,cat.name as category,cat2.name as sub_category, inventories.buying_price,SUM(inventories.credit) AS total_credit FROM products INNER JOIN inventories ON inventories.product_id = products.id AND inventories.is_sold !=1 AND inventories.supplier_id = products.supplier_id AND inventories.inventory_id IS NULL INNER JOIN categories as cat ON cat.id = products.category_id INNER JOIN categories as cat2 ON cat2.id =products.sub_category_id WHERE products.supplier_id = '{$supplier_id}' AND inventories.`type`=1 AND products.deleted_at IS NULL AND inventories.deleted_at IS NULL";

	    	if ($request->from_date != 0) {
				$from_date = $request->from_date;

				$query .= " AND date(inventories.created_at) >= '$from_date' ";
			}
			if ($request->to_date != 0) {
				$to_date = $request->to_date;
				$query .= " AND date(inventories.created_at) <= '$to_date'";
			}

			$query .= " GROUP BY inventories.id";

    	$stockData = DB::select(DB::raw($query));
    	foreach ($stockData as $product) {
    		$debitQuery = "SELECT SUM(inventories.debit) AS total_debit FROM inventories WHERE inventories.`status`!=3 AND inventories.inventory_id = '{$product->id}' AND inventories.inventory_id IS NOT null AND inventories.deleted_at IS NULL";

    		if ($request->from_date != 0) {
				$from_date = $request->from_date;

				$query .= " AND date(inventories.created_at) >= '$from_date' ";
			}
			if ($request->to_date != 0) {
				$to_date = $request->to_date;
				$query .= " AND date(inventories.created_at) <= '$to_date'";
			}

			$query .= " GROUP BY inventories.inventory_id";

    		$debit = DB::select(DB::raw($debitQuery));

    		$debit= !empty($debit)?$debit[0]->total_debit:0;

    		$current_stock = ($product->total_credit-$debit)>=0 ? ($product->total_credit-$debit):0;
    		$total_stock += $current_stock * $product->buying_price;
    	}

    	return $total_stock ;
	}

	public function salesman()
	{
		if(Auth::user()->user_type == 5){	// shop employee
				$employee = Employe::find(Auth::user()->record_id);

				$supplier_id = $employee->shop_id;
			} else {
				$supplier_id = Auth::user()->record_id;
			}

		$employes = Employe::where('shop_id',$supplier_id)->get();

		return view('admin.supplier.report.salesman',compact('employes'));
	}
	public function downloadSalesmanReport(Request $request)
	{
		if($request->employe_id != null){
			$employe_id = $request->employe_id;
		}
		else{
			$employe_id = Auth::user()->record_id;	
		}

		if(Auth::user()->user_type == 5){	// shop employee
			$employee = Employe::find(Auth::user()->record_id);

			$supplier_id = $employee->shop_id;
		} else {
			$supplier_id = Auth::user()->record_id;
		}
		$dateText='';
		$query = "SELECT inventories.order_id,clients.name as client_name, orders.client_id, DATE(orders.created_at) AS created_at, SUM(inventories.debit * inventories.selling_price) AS total_amount,orders.discount, SUM(inventories.debit * inventories.selling_price) AS net_amount,orders.origin FROM orders JOIN inventories  ON inventories.order_id = orders.order_id AND inventories.supplier_id = $supplier_id  LEFT JOIN clients ON clients.id = inventories.client_id AND clients.deleted_at IS NULL WHERE inventories.`type` = 2 AND inventories.`status` = 2 AND orders.deleted_at IS NULL AND inventories.deleted_at IS NULL AND inventories.created_by = $employe_id ";

		if ($request->from_date != null) {
				$from_date = $request->from_date;
				$query .= "AND date(inventories.created_at) >= '$from_date' ";
				$dateText .= converter::en2bn(date('d-m-Y',strtotime($from_date))).' হতে ';
			}
			if ($request->to_date != null) {
				$to_date = $request->to_date;
				$query .= "AND date(inventories.created_at) <= '$to_date'";
				$dateText .= converter::en2bn(date('d-m-Y',strtotime($to_date))).' পর্যন্ত';
			}

		$query .= "GROUP BY inventories.order_id ";
		$data = DB::select(DB::raw($query));

		$shop = Supplier::find($supplier_id);
		$employe = Employe::find($employe_id);

		$data = [
				'shop' => $shop->shop_name,
				'shop_image' => $shop->shop_image,
				'data' => $data,
				'employe' => $employe,
				'dateText' => $dateText,
			];

		$pdf = PDF::loadView('download.salesman_report',$data);
		return $pdf->stream('Salesman Report - '.$employe->name.'-'.date('dMY').'.pdf');


	}
}
