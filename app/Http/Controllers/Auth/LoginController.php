<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    use ThrottlesLogins;

//    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateForm($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $this->incrementLoginAttempts($request);

        if ($this->attemptLogin($request)) {
            return $this->sendSuccessResponse();
        }

        return $this->sendFailedLoginResponse();
    }

    public function logout()
    {
        session()->invalidate();

        Auth::logout();

        return redirect()->to('/');
    }

    private function validateForm(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required'],
        ]);
    }

    private function attemptLogin(Request $request)
    {
        return Auth::attempt($request->only('email', 'password'), $request->filled('remember'));
    }

    private function sendSuccessResponse()
    {
        session()->regenerate();

        return redirect()->route('home');
    }

    private function sendFailedLoginResponse()
    {
        return back()->with('failed', 'Email or password is incorrect');
    }

    protected function username()
    {
        return 'email';
    }
}
