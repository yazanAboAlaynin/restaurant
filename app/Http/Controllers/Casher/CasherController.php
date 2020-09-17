<?php

namespace App\Http\Controllers\Casher;

use App\Category;
use App\Http\Controllers\Controller;
use App\Meal;
use App\Report;
use App\Reservation;
use App\Reservation_item;
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
        return view('casher.home');
    }

    public function reservations(Request $request){

        if($request->ajax())
        {
            $data = Reservation::latest()->with('user')->get();

            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<div class="btn-group" role="group" aria-label="Basic example">';
                    $button .= '<button type="button" name="edit" id="'.$data->id.'"
                    class="edit btn btn-primary btn-sm" onclick=update('.$data->id.')>Edit</button>';
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="delete" id="'.$data->id.'"
                    class="delete btn btn-danger btn-sm" onclick=del('.$data->id.')>Delete</button>';
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="order" id="'.$data->id.'"
                    class="delete btn btn-warning btn-sm" onclick=order('.$data->id.')>Order</button> ';
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="orders" id="'.$data->id.'"
                    class="delete btn btn-success btn-sm" onclick=orders('.$data->id.')>Orders</button></div>';

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
            'date'=> 'required',
            'number'=> 'required',
        ]);


        $reservation->date = $request->date;
        $reservation->number = $request->number;
        $reservation->save();

        return redirect()->route('casher.reservations');
    }

    public function deleteReservation(Request $request){
        Reservation::destroy($request->id);
        return response([],200);
    }

    public function reservation(Request $request){
        return view('casher.reservation');
    }

    public function reserve(Request $request){
        $request->validate([
            'date'=> 'required',
            'number'=> 'required',
        ]);

        $reservation = new Reservation();
        $reservation->date = $request->date;
        $reservation->number = $request->number;
        $reservation->user_id = -1;
        $reservation->save();

        return redirect()->route('casher.home');
    }

    public function addOrder(Reservation $reservation){
        $categories = Category::all();
        return view('casher.add-order',compact('categories','reservation'));
    }

    public function storeOrder(Request $request,Reservation $reservation){

        $request->validate([
            'meals'=> 'required|array|min:1',
        ]);
        $orders = [];
        foreach ($request->meals as $meal){
            $order = new Reservation_item();
            $order->reservation_id =  $reservation->id;
            $order->casher_id = auth()->user()->id;
            $order->meal_id = $meal;
            $order->quantity = 1;
            $m = Meal::find($meal);


            $order->tot_price = $m->price * 1;
            $order->save();

            array_push($orders,$order);
        }
        //$meals = $request->meals;
        $orders = serialize($orders);
        return redirect()->route('casher.order.quantity',compact('orders'));
    }

    public function orderQuantity(Request $request)
    {
        $orders = unserialize($request->orders);
        $ords = serialize($orders);
        return view('casher.order-quantity',compact('orders','ords'));
    }

    public function storeOrderQuantity(Request $request,$ords){

        $request->validate([
            'numbers'=> 'required|array',
        ]);
        $orders = json_decode($ords);

        foreach ($orders as $k=>$orderr){
            $order = Reservation_item::find($orderr->id);
            $order->quantity = $request->numbers[$k];
            $m = Meal::find($order->meal_id);
            $order->tot_price = $m->price * $order->quantity;
            $order->save();
        }

        return redirect()->route('casher.reservations');
    }

    public function editOrder(Request $request,Reservation_item $order)
    {
        return view('casher.edit-order',compact('order'));
    }
    public function updateOrder(Request $request,Reservation_item $order){

        $request->validate([
            'quantity'=> 'required',
        ]);

        $order->quantity = $request->quantity;
        $order->tot_price = $order->meal()->get()[0]->price*$request->quantity;
        $order->save();

        return redirect()->route('casher.reservations');
    }

    public function orders(Request $request,Reservation $reservation){
        $data = Reservation_item::where('reservation_id',$reservation->id)->get();
        $total = $data->sum('tot_price');
        if($request->ajax())
        {
            $data = Reservation_item::where('reservation_id',$reservation->id)->with('meal')->get();

            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<div class="btn-group" role="group" aria-label="Basic example">';
                    $button .= '<button type="button" name="edit" id="'.$data->id.'"
                    class="edit btn btn-primary btn-sm" onclick=update('.$data->id.')>Edit</button>';
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="delete" id="'.$data->id.'"
                    class="delete btn btn-danger btn-sm" onclick=del('.$data->id.')>Delete</button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);

        }

        return view('casher.orders',compact('reservation','total'));

    }

    public function deleteOrder(Request $request){
        Reservation_item::destroy($request->id);
        return;
    }


    public function addReport(){

        return view('casher.add-report');
    }

    public function storeReport(Request $request){

        $request->validate([
            'content'=> 'required',
            'income'=> 'required',
            'outcome'=> 'required',
            'days_off'=> 'required',
        ]);
        $r = new Report();
        $r->content = $request['content'];
        $r->income = $request['income'];
        $r->outcome = $request['outcome'];
        $r->days_off = $request['days_off'];
        $r->casher_id = auth()->user()->id;
        $r->save();


        return redirect()->route('casher.reports');
    }

    public function editReport(Request $request,Report $report)
    {
        return view('casher.edit-report',compact('report'));
    }
    public function updateReport(Request $request,Report $report){

        $request->validate([
            'content'=> 'required',
            'income'=> 'required',
            'outcome'=> 'required',
            'days_off'=> 'required',
        ]);
        $report->content = $request['content'];
        $report->income = $request['income'];
        $report->outcome = $request['outcome'];
        $report->days_off = $request['days_off'];
        $report->casher_id = auth()->user()->id;
        $report->save();

        return redirect()->route('casher.reports');
    }

    public function reports(Request $request){
        if($request->ajax())
        {
            $data = Report::latest()->get();

            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<div class="btn-group" role="group" aria-label="Basic example">';
                    $button .= '<button type="button" name="edit" id="'.$data->id.'"
                    class="edit btn btn-primary btn-sm" onclick=update('.$data->id.')>Edit</button>';
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="delete" id="'.$data->id.'"
                    class="delete btn btn-danger btn-sm" onclick=del('.$data->id.')>Delete</button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);

        }

        return view('casher.reports');

    }

    public function deleteReport(Request $request){
        Report::destroy($request->id);
        return;
    }
}
