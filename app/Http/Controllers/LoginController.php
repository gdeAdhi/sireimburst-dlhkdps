<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //

    public function index(){
        return view('auth.login');
    }

    public function login_process(Request $request)
    {
        $login = $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

       
        
        
        if (Auth::attempt($login)) {
            $request->session()->regenerate();
 
            return redirect()->intended('dashboard');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);

        // $2y$10$51cgelCP7RjGrllPmCzotuIFUie9Y0MK01H43bQmLmWRbzpbw4952
    }
}
