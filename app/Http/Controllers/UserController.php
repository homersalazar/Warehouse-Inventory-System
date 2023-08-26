<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show_login(){
        return view('user.login');
    }

    public function show_register(){
        return view('user.register');
    }

    public function register(Request $request){
        // kulang ng isExistEmail
        $request->validate([
            'fullname' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required', 
        ]);
    
        User::create([
            'name' => $request->input('fullname'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => 1, // 0 - admin , 1 - user
            'status' => 1 // 0 - accepted , 1 - for approval
        ]);
        
        return redirect()->route('user.login')->with('success', 'You have signed up successfully. Please log in.');
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            session(['full_name' => $user->name]);
            session(['email' => $user->email]);
            if ($user->role == 1 && $user->status == 1) {
                return redirect()->route('dashboard.create')->with('success', 'Signed in');
            } elseif ($user->role == 0 && $user->status == 0) {
                return redirect()->route('dashboard.index')->withSuccess('Signed in'); // user - approved
            }else {
                Auth::logout();
                return redirect("login")->with('error', 'You do not have access to the dashboard');
            }
        }
        
        return redirect("login")->with('error', 'Login details are not valid');
    }

    public function logout() {
        Session::flush();
        Auth::logout();
        return Redirect('/');
    }
}
