<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Code;
use App\Models\User;
use App\Rules\Recaptcha;
use App\Services\Auth\TwoFactorAuthentication;
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
    public function __construct(private TwoFactorAuthentication $twoFactor)
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showCodeForm()
    {
        return view('auth.two-factor.login-code');
    }

    public function login(Request $request)
    {
        $this->validateForm($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        if (!$this->isValidCredentials($request)) {
            $this->incrementLoginAttempts($request);
            $this->sendFailedLoginResponse();
        }

        $user = $this->getUser($request);

        if ($user->hasTwoFactor()) {
            $this->twoFactor->requestCode($user);
            return $this->sendHasTwoFactorResponse();
        }

        Auth::login($user, $request->remember);

        return $this->sendSuccessResponse();
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
            'g-recaptcha-response' => ['required', new Recaptcha()]
        ],
            [
                'g-recaptcha-response.required' => 'Please check recaptcha'
            ]
        );
    }

    public function confirmCode(Code $request)
    {
        $response = $this->twoFactor->login();

        return $response == $this->twoFactor::AUTHENTICATED
            ? $this->sendSuccessResponse()
            : back()->with('failed', 'Invalid code');
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

    private function isValidCredentials(Request $request)
    {
        return Auth::validate($request->only(['email', 'password']));
    }

    private function getUser(Request $request)
    {
        return User::where('email', $request->email)->firstOrFail();
    }

    private function sendHasTwoFactorResponse()
    {
        return redirect()->route('auth.login.code.form');
    }
}
