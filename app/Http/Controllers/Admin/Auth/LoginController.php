<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Administrador;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
        $this->middleware('guest')->except('logout');
        $this->middleware('admin')->only('logout');
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

    // reescribo el método login para validar equipo del usuario.
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $request->only('email', 'password');

        // Validar credenciales. Si no pasan, sumo un intento de login y devuelvo el error.
        if (! Auth::validate($credentials)) {
            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);
        }

        // Si llegó aquí es que las credenciales pasaron. Uso attemptWhen para loguear al usuario, sumando nuevas condiciones con errores personalizados.
        if (Auth::attemptWhen($credentials, function (Administrador $admin) use ($request) {
            
            // Si el usuario no se encuentra habilitado.
            if (! $admin->visible) 
                $this->sendFailedLoginResponse($request, "Acceso denegado. No podés ingresar al panel.");
            
            // Si es un usuario global (sin equipo asignado), ya considero logueado al usuario.
            if ($admin->soloApi())
                $this->sendFailedLoginResponse($request, "Acceso denegado. No podés ingresar al panel.");

            return true;
        }));
        
        // El usuario pasó todas las validaciones correctamente y fue autenticado.
        return $this->sendLoginResponse($request);
    }

    // Sobreescribimos el metodo sendFailedLoginResponse, pudiendo especificarle el mensaje de error.

    protected function sendFailedLoginResponse(Request $request, $custom_message = null)
    {
        throw ValidationException::withMessages([
            $this->username() => [ $custom_message ?? trans('auth.failed')],
        ]);
    }
}
