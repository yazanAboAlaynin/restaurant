<?php

namespace App\Http\Controllers\Casher;

use App\Http\Controllers\Controller;
use App\Reservation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CasherController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:casher');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function reservations(Request $request){

        if($request->ajax())
        {
            $data = Reservation::latest()->with('user')->get();

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

        return view('casher.reservations');

    }

    public function editReservation(Reservation $reservation){

        return view('casher.edit-reservation',compact('reservation'));
    }

    public function updateReservation(Request $request,Reservation $reservation){
        $request->validate([

        ]);
        //$reservation->name = $request['name'];

        $reservation->save();

        return redirect()->route('casher.reservation');
    }

    public function deleteReservation(Request $request){
        Reservation::destroy($request->id);
        return response([],200);
    }
}
