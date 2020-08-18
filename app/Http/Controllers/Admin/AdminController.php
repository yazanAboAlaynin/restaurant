<?php

namespace App\Http\Controllers\Admin;

use App\Casher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.home');
    }

    public function casher(){
        return view('admin.cashers');
    }

    public function createCasher(){
        return view('admin.create-casher');
    }

    public function storeCasher(Request $request){
        $request->validate([
           'name'=>'required',
           'email'=>'required|email|unique:cashers',
           'password'=>'required|min:8',
           'salary'=>'required',
        ]);
        $casher = new Casher();
        $casher->name = $request['name'];
        $casher->email = $request['email'];
        $casher->password = Hash::make($request['password']);
        $casher->salary = $request['salary'];
        $casher->save();

        return redirect('admin/cashers');
    }

    public function cashers(Request $request){

        if($request->ajax())
        {
            $data = Casher::latest()->get();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<button type="button" name="edit" id="'.$data->id.'" 
                    class="edit btn btn-primary btn-sm" onclick=update('.$data->id.')>Edit</button>';
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="delete" id="'.$data->id.'" 
                    class="delete btn btn-danger btn-sm" onclick=del('.$data->id.')>Delete</button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.cashers');

    }

    public function editCasher(Casher $casher){

        return view('admin.edit-casher',compact('casher'));
    }

    public function updateCasher(Request $request,Casher $casher){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'salary'=>'required',
        ]);

        $casher->name = $request['name'];
        $casher->email = $request['email'];
        $casher->salary = $request['salary'];
        $casher->save();

        return redirect()->route('admin.cashers');
    }

    public function deleteCasher(Request $request){
        Casher::destroy($request->id);
        return response([],200);
    }

}
