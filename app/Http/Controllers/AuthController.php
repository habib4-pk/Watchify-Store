<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginForm()
    {

        return view('auth.login');
    }

    public function registerForm()
    {

        return view('auth.register');
    }

  

    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        if (Auth::attempt([
            'email' => $email,
            'password' => $password
        ])) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended(route('adminDashboard'));
            }

            return redirect()->intended(route('home'));
        }

        return redirect()->back();
    }


    // public function login(Request $request){

    //     $email = $request->email;

    //     $password = $request->password;

    //     $user = User::where('email',$email)->first();

    //     if($user){

    //         $dbPass = $user->password;

    //         if(password_verify($password,$dbPass)){


    //             if($user->role == 'admin'){
    //                 return redirect()->route('adminDashboard');
    //             }else{

    //                 return redirect()->route('home');
    //             }

    //         }else{
    //             return redirect()->back();
    //         }


    //     }else{

    //         return redirect()->back();

    //     }


    // }

    public function register(Request $request)
    {


        $user = new User;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;

        $user->save();

        return redirect()->route('loginForm');
    }

    public function logout(Request $request){

        $role = Auth::user()->role;


        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if($role === 'admin'){
            return redirect()->route('login');
        }
        

        return redirect()->route('home');
    }
    
}
