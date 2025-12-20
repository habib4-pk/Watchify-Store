<?php

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\User;
use App\Models\Watch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    

    

    public function dashboard(){

        if(Auth::user() && Auth::user()->role ==='admin'){
            
        $totalSales = Order::where('status','completed')->sum('total_amount');

        $totalUsers = User::where('role','!=','admin')->count();


        $totalOrders= Order::count();

        $totalWatches = Watch::count();

        return view("admin.dashboard",compact('totalSales','totalOrders','totalUsers','totalWatches'));

        }else{
            return redirect()->route('login');
        }


    }


    public function showAllUsers(){

         $allUsers = User::all();
        return view('admin.users.index',compact('allUsers'));

    }
    

    public function destroy(Request $req){
        $id = $req->id;

        User::destroy($id);

        return redirect()->route('allUsers');
    }
 
}
