<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DataTables;
use DB;
use converter;
use App\Supplier;
use App\Employe;
use App\AccountHead;
use App\Expense;
use Response;
use PDF;

class ExpenseController extends Controller
{
    public function AccountHeadList(Request $request)
    {
    	if($request->ajax()){
            if(Auth::user()->user_type == 5){   // shop employee
                $employee = Employe::find(Auth::user()->record_id);

                $supplier_id = $employee->shop_id;
            } else {
                $supplier_id = Auth::user()->record_id;
            }
    		$data = AccountHead::where('supplier_id',$supplier_id)->get();

    		return Datatables::of($data)

				->addIndexColumn()

				->rawColumns(['action'])

				->make(true);
    	}
    	else{
    		return view('admin.supplier.expense.head');
    	}
    }

    public function AccountHeadStore(Request $request)
    {
    	
    	$status = 'error';
    	$message = 'ব্যয় ক্যটাগরি জমা হয়নি';
    		if(isset($request->id)){
    			$insert = AccountHead::find($request->id);
    			$insert->created_by = Auth::user()->id;
				$insert->created_by_ip = $request->ip();
    		}
    		else{
    			$insert = new AccountHead();
    			$insert->updated_by = Auth::user()->id;
				$insert->updated_by_ip = $request->ip();
    		}

    		$insert->supplier_id = Auth::user()->record_id;
    		$insert->name = $request->name;
    		$save = $insert->save();
    		if($save){
    			$status = 'success';
    			$message = 'ব্যয় ক্যটাগরি সফলভাবে জমা হয়েছে';
    		}
    		return Response::json(['status' => $status,'message' => $message]);

    }

    public function AccountHeadDelete(Request $request)
    {
    	if(!isset($request->id)){
    		return Response::json([
    			'status' => 'error',
    			'message' => 'কোন সমস্যা হয়েছে'
    		]);
    	}

    	$data = AccountHead::find($request->id);
    	$data->delete();
    	return Response::json([
    			'status' => 'success',
    			'message' => 'সফলভাবে বাতিল হয়েছে'
    		]);
    }

    public function EntryList(Request $request)
    {
        if(Auth::user()->user_type == 5){   // shop employee
                $employee = Employe::find(Auth::user()->record_id);

                $supplier_id = $employee->shop_id;
            } else {
                $supplier_id = Auth::user()->record_id;
            }

    	if($request->ajax()){

    		$query = DB::table('expenses')
    					->select('expenses.*','AH.id as account_head','AH.name as head_name')
    					->leftJoin('account_heads as AH',function($join){
    						$join->on('AH.id','=','expenses.head')
    								->on('AH.supplier_id','=','expenses.supplier_id');
    					})
    					->where('expenses.supplier_id','=',$supplier_id)
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

        $data = $query->orderBy('expenses.id','desc')->get();



    		return Datatables::of($data)

				->addIndexColumn()

				->rawColumns(['action'])

				->make(true);
    	}
    	else{
    		$heads = AccountHead::where('supplier_id',$supplier_id)->get();
    		return view('admin.supplier.expense.entry',compact('heads'));
    	}
    }

    public function EntryStore(Request $request)
    {
    	
    	$status = 'error';
    	$message = 'ব্যয় ক্যটাগরি জমা হয়নি';
        if(Auth::user()->user_type == 5){   // shop employee
                $employee = Employe::find(Auth::user()->record_id);

                $supplier_id = $employee->shop_id;
            } else {
                $supplier_id = Auth::user()->record_id;
            }

    		if(isset($request->id)){
    			$insert = Expense::find($request->id);
    			$insert->created_by = Auth::user()->id;
				$insert->created_by_ip = $request->ip();
    		}
    		else{
    			$insert = new Expense();
    			$insert->updated_by = Auth::user()->id;
				$insert->updated_by_ip = $request->ip();
    		}

    		$insert->supplier_id = $supplier_id;
    		$insert->head = $request->account_head;
    		$insert->amount = $request->amount;
    		$insert->note = $request->note;
    		$save = $insert->save();
    		if($save){
    			$status = 'success';
    			$message = 'ব্যয় সফলভাবে জমা হয়েছে';
    		}
    		return Response::json(['status' => $status,'message' => $message]);

    }

    public function EntryDelete(Request $request)
    {
    	if(!isset($request->id)){
    		return Response::json([
    			'status' => 'error',
    			'message' => 'কোন সমস্যা হয়েছে'
    		]);
    	}

    	$data = Expense::find($request->id);
    	$data->delete();
    	return Response::json([
    			'status' => 'success',
    			'message' => 'সফলভাবে বাতিল হয়েছে'
    		]);
    }

    public function Report()
    {
        if(Auth::user()->user_type == 5){   // shop employee
                $employee = Employe::find(Auth::user()->record_id);

                $supplier_id = $employee->shop_id;
            } else {
                $supplier_id = Auth::user()->record_id;
            }
            
    	$heads = AccountHead::where('supplier_id',$supplier_id)->get();
    		return view('admin.supplier.report.expense',compact('heads'));
    }

    public function DownloadReport(Request $request)
    {
    	$request->from_date = $request->from_date!=null?$request->from_date:0;
		$request->to_date = $request->to_date!=null?$request->to_date:0;
		$request->account_head = $request->account_head!=null?$request->account_head:0;

        $dateText='';
        if($request->from_date != 0){
            $dateText .= converter::en2bn(date('d-m-Y',strtotime($request->from_date))).' হতে ';
        }
        if($request->to_date !=0 ){
            $dateText .= converter::en2bn(date('d-m-Y',strtotime($request->to_date))).' পর্যন্ত';
        }

        if(Auth::user()->user_type == 5){   // shop employee
                $employee = Employe::find(Auth::user()->record_id);

                $supplier_id = $employee->shop_id;
            } else {
                $supplier_id = Auth::user()->record_id;
            }

    	$query = DB::table('expenses')
    					->select('expenses.*','AH.id as account_head','AH.name as head_name')
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

    	$expenses = $query->orderBy('expenses.id','desc')->get();

    	$shop = Supplier::find($supplier_id);

			$data = [
				'shop' => $shop->shop_name,
				'shop_image' => $shop->shop_image,
				'expenses' => $expenses,
                'dateText' => $dateText,
			];

		$pdf = PDF::loadView('download.expense',$data);
		return $pdf->stream('Expense Report'.date('dMY').'.pdf');
    }
}
