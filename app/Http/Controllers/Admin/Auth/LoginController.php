<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
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
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    //reescribo este método para poder cambiar las vistas de auth de directorio
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    //reescribo este método para cambiar el redirect, y tal vez un poco el comportamiento
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush(); //dejar así?

        $request->session()->regenerate(); //dejar así?

        return redirect()->route('login');
    }
}
