<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Farmer;
use DataTables;
use Symfony\Component\HttpFoundation\Session\Session;
use Illuminate\Support\Facades\Auth;

class FarmerController extends Controller
{
    public function index(){
        return view('admin.Farmer.index');
    }
    public function create(){
        return view('admin.Farmer.create');
    }
    public function data(Request $request)
    {
        if ($request->ajax()) {

			$data = Farmer::latest()->get();

			return Datatables::of($data)

                ->addIndexColumn()

				->addColumn('action', function ($row) {

                    $btn = '<a href="'.route("farmer.edit",$row->id).'" class="btn btn-primary btn-sm">সম্পাদন</a>
                    <a href="'.route("farmer.delete",$row->id).'" class="btn btn-danger btn-sm">বাতিল</a>';

					return $btn;

				})

				->rawColumns(['action'])

				->make(true);

		}
    }
    public function store(Request $request)
    {
        
        $request->validate([
            "name" => "required",
            "phone" => "required",
            "address" => "required",
            "nid" => "required",
        ]);
            
        $msg = "কৃষকের তথ্য সফলভাবে জমা হয়েছে।";

        if(isset($request->id)){
            $farmer_data = Farmer::find($request->id);
            $farmer_data->updated_by = Auth::user()->id;
            $farmer_data->updated_by_ip = $request->ip();
            $msg = "কৃষকের তথ্য সফলভাবে হালনাগাদ হয়েছে।";
        }
        else{
            $farmer_data = new Farmer();

            $farmer_data->created_by = Auth::user()->id;
            $farmer_data->created_by_ip = $request->ip();
        }

        $farmer_data->union_id = Auth::user()->union_id;
        $farmer_data->upazila_id = Auth::user()->upazila_id;
        $farmer_data->name = $request->name;
        $farmer_data->phone = $request->phone;
        $farmer_data->address = $request->address;
        $farmer_data->nid = $request->nid;
        $farmer_data->save();

        return back()->with('success',$msg);

    }
    
    public function edit($id)
    {
        $data = Farmer::find($id);

        return view("admin.farmer.create",compact("data"));
    }

    public function delete($id)
    {
        $data = Farmer::find($id);
        $data->delete();
        return back()->with('success',"কৃষকের তথ্য সফলভাবে ডিলিট হয়েছে।");
        
    }
}
