<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Watch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

/**
 * AdminController
 * Handles admin dashboard and user management
 * Supports both AJAX (JSON) and traditional (redirect) responses
 */
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
        $validator = Validator::make($req->all(), [
            'id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            if ($req->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid user ID.'
                ], 400);
            }
            return redirect()->route('allUsers')->with('error', 'Invalid user ID.');
        }

        try {
            $id = $req->id;
            $user = User::find($id);
            
            if (!$user) {
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'User not found.'], 404);
                }
                return redirect()->route('allUsers')->with('error', 'User not found.');
            }
            
            // Prevent admin from deleting themselves
            if ($user->id === Auth::id()) {
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Cannot delete your own account.'], 400);
                }
                return redirect()->route('allUsers')->with('error', 'Cannot delete your own account.');
            }
            
            $userName = $user->name;
            User::destroy($id);

            if ($req->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "User '{$userName}' deleted successfully!"
                ]);
            }
            return redirect()->route('allUsers')->with('success', 'User deleted successfully!');
        } catch (Exception $e) {
            if ($req->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete user. They may have active records.'
                ], 500);
            }
            return redirect()->route('allUsers')->with('error', 'Failed to delete user. They may have active records.');
        }
    }
}