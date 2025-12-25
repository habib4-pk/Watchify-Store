<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Watch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class AdminController extends Controller
{
    public function dashboard()
    {
        try {
            $totalSales = Order::where('status', 'completed')->sum('total_amount');
            $totalUsers = User::where('role', '!=', 'admin')->count();
            $totalOrders = Order::count();
            $totalWatches = Watch::count();

            return view("admin.dashboard", compact('totalSales', 'totalOrders', 'totalUsers', 'totalWatches'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Unable to load dashboard data.');
        }
    }

    public function showAllUsers()
    {
        try {
            $allUsers = User::all();
            return view('admin.users.index', compact('allUsers'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Unable to fetch users.');
        }
    }

    public function destroy(Request $req)
    {
        try {
            $id = $req->id;
            User::destroy($id);

            return redirect()->route('allUsers')->with('success', 'User deleted successfully!');
        } catch (Exception $e) {
            return redirect()->route('allUsers')->with('error', 'Failed to delete user. They may have active records.');
        }
    }
}