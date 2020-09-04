<?php

namespace App\Http\Controllers\Admin;

use App\Bill;
use App\Casher;
use App\Category;
use App\Http\Controllers\Controller;
use App\Meal;
use App\Reservation;
use App\Reservation_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
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

    /****************************************************/

    public function createCategory(){
        return view('admin.create-category');
    }

    public function storeCategory(Request $request){
        $request->validate([
            'name'=>'required',
            'image' => 'required|image'
        ]);
        $imagePath = "";
        if ($files = $request->file('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1500, 1500);
            $image->save();
        }
        $category = new Category();
        $category->name = $request['name'];
        $category->image = $imagePath;
        $category->save();

        return redirect('admin/categories');
    }

    public function categories(Request $request){

        if($request->ajax())
        {
            $data = Category::latest()->get();
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

        return view('admin.categories');

    }

    public function editCategory(Category $category){

        return view('admin.edit-category',compact('category'));
    }

    public function updateCategory(Request $request,Category $category){
        $request->validate([
            'name'=>'required',
        ]);
        $category->name = $request['name'];
        $category->save();

        return redirect()->route('admin.categories');
    }

    public function deleteCategory(Request $request){
        Category::destroy($request->id);
        return response([],200);
    }

    /****************************************************/

    public function createMeal(){
        $categories = Category::all();
        return view('admin.create-meal',compact('categories'));
    }

    public function storeMeal(Request $request){
        $request->validate([
            'name'=>'required',
            'image' => 'required|image',
            'description' => 'required',
            'category_id' => 'required',
            'price' => 'required',
        ]);
        $imagePath = "";
        if ($files = $request->file('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1500, 1500);
            $image->save();
        }
        $meal = new Meal();
        $meal->name = $request['name'];
        $meal->image = $imagePath;
        $meal->description = $request['description'];
        $meal->category_id = $request['category_id'];
        $meal->price = $request['price'];
        $meal->save();

        return redirect('admin/meals');
    }

    public function meals(Request $request){

        if($request->ajax())
        {
            $data = Meal::latest()->get();
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

        return view('admin.meals');

    }

    public function editMeal(Meal $meal){

        return view('admin.edit-meal',compact('meal'));
    }

    public function updateMeal(Request $request,Meal $meal){
        $request->validate([
            'name'=>'required',
            'description'=>'required',
            'price'=>'required',
        ]);
        $meal->name = $request['name'];
        $meal->description = $request['description'];
        $meal->price = $request['price'];
        $meal->save();

        return redirect()->route('admin.meals');
    }

    public function deleteMeal(Request $request){
        Meal::destroy($request->id);
        return response([],200);
    }

    /*************************************************/

    public function reservations(Request $request){

        if($request->ajax())
        {
            $data = Reservation::latest()->with('user')->get();

            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<div class="btn-group" role="group">';
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="delete" id="'.$data->id.'"
                    class="delete btn btn-danger btn-sm" onclick=del('.$data->id.')>Delete</button>';
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="orders" id="'.$data->id.'"
                    class="delete btn btn-success btn-sm" onclick=orders('.$data->id.')>Orders</button></div>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.reservations');

    }

    public function editReservation(Reservation $reservation){

        return view('admin.edit-reservation',compact('reservation'));
    }

    public function updateReservation(Request $request,Reservation $reservation){
        $request->validate([

        ]);
        //$reservation->name = $request['name'];

        $reservation->save();

        return redirect()->route('admin.reservation');
    }

    public function deleteReservation(Request $request){
        Reservation::destroy($request->id);
        return response([],200);
    }

    /*************************************************/

    public function orders(Request $request,Reservation $reservation){

        $data = Reservation_item::where('reservation_id',$reservation->id)->get();
        $total = $data->sum('tot_price');
        if($request->ajax())
        {
            $data = Reservation_item::where('reservation_id',$reservation->id)->get();

            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<div class="btn-group" role="group" aria-label="Basic example">';
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="delete" id="'.$data->id.'"
                    class="delete btn btn-danger btn-sm" onclick=del('.$data->id.')>Delete</button></div>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);

        }

        return view('admin.orders',compact('reservation','total'));

    }
    public function deleteOrder(Request $request){
        Reservation_item::destroy($request->id);
        return response([],200);
    }


}
