<?php

namespace App\Http\Controllers;

use App\Reservation;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('user.home');
    }

    public function reservation(Request $request){
        return view('user.reservation');
    }

    public function reserve(Request $request){
        $request->validate([
           'date'=> 'required',
           'number'=> 'required',
        ]);

        $reservation = new Reservation();
        $reservation->date = $request->date;
        $reservation->number = $request->number;
        $reservation->user_id = auth()->user()->id;
        $reservation->save();

        return redirect()->route('user.home');
    }
}
