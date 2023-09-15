<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // registration form
    public function show_register()
    {
        return view('user.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'fullname' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);
        $user = User::where('email', $request->input('email'))->first();

        if ($user) {
            return redirect()->back()->with('error', 'The email address is already registered.');
        }

        $newUser = User::create([
            'name' => $request->input('fullname'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => 1, // 0 - admin, 1 - user
            'status' => 1, // 0 - allowed, 1 - for approval
        ]);

        if ($newUser) {
            return redirect()->route('user.login')->with('success', 'You have signed up successfully. Please log in.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong while registering. Please try again.');
        }
    }

    // Login Form
    public function show_login()
    {
        return view('user.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->status == 0) {
                session(['role' => $user->role]);
                session(['ID' => $user->id]);
                session(['full_name' => $user->name]);
                session(['email' => $user->email]);
                return redirect()->route('dashboard.index')->with('success', 'Signed in');
            } else {
                Auth::logout();
                return redirect()->route('login')->with('info', 'You do not have access, please contact your administrator!');
            }
        }
        return redirect()->route('login')->with('error', 'Login details are not valid');
    }

    // Logout
    public function logout()
    {
        Session::flush();
        Auth::logout();
        return Redirect('/');
    }

    // user module for admin only
    public function index()
    {
        $user_activated = User::whereStatus(0)->with('location')->get();
        $user_deactivated = User::whereStatus(1)->get();
        $deactivated_count = User::whereStatus(1)->count();
        return view('user.index', compact('user_activated', 'user_deactivated', 'deactivated_count'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        $locations = Location::all();
        return view('user.edit', compact('user', 'locations'));
    }

    public function edit_password($id)
    {
        $user = User::find($id);
        return view('user.update', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_name' => 'required',
            'user_email' => 'required'
        ]);

        if ($validated) {
            $selectedSite = [];
            foreach ($request->input('loc_id') as $selected) {
                $selectedSite[] = $selected;
            }
            $user = User::findOrFail($id);
            $user->name = $request->input('user_name');
            $user->email = $request->input('user_email');
            $user->role = $user->role == 0 ? 0 : $request->input('user_role');
            $user->location_id = implode(", ", $selectedSite);
            $user->save();
            return redirect()->route('user.index')->with('success', 'User information updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Validation failed.');
        }
    }

    public function update_password(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->password = bcrypt($request->input('password'));
            $user->save();
            return redirect()->route('user.index')->with('success', 'User password updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the password.');
        }
    }

    public function deactivate(Request $request, $id)
    {
        $user = User::find($id);
        $user->status = 1;
        $user->save();
        return redirect()->route('user.index')
            ->with('success', ucwords($request->name) . ' has been updated successfully.');
    }

    public function reactivate(Request $request, $id)
    {
        $user = User::find($id);
        $user->status = 0;
        $user->save();
        return redirect()->route('user.index')
            ->with('success', ucwords($request->name) . ' has been updated successfully.');
    }


    // user only 
    public function profile($id)
    {
        $user = User::find($id);
        return view('user.profile', compact('user'));
    }

    public function profile_update(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->password = bcrypt($request->input('password'));
            $user->save();
            return redirect()->route('dashboard.index')->with('success', 'User password updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the password.');
        }
    }
}
