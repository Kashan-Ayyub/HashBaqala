<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
             protected function attemptLogin(Request $request)
{
    $credentials = $this->credentials($request);

    // Attempt to log in
    if ($this->guard()->attempt($credentials)) {
        // Check user_type after successful login
        $user = $this->guard()->user();
        if ($user->user_type == 1) {
            return true; // User type 1, allow login
        } else {
            // User type 2, logout and show invalid credentials error
            $this->guard()->logout();
            $request->session()->flash('error', 'Invalid email and password');
            return false;
        }
    }

    return false; // Invalid credentials
}


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}