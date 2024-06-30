<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginToken;
use App\Services\Auth\MagicAuthentication;
use Illuminate\Http\Request;

class MagicController extends Controller
{
    public function __construct(private MagicAuthentication $auth)
    {
    }

    public function showMagicForm()
    {
        return view('auth.magic-login');
    }

    public function sendMagicLink(Request $request)
    {
        $this->validateForm($request);

        $this->auth->requestLink();

        return back()->with('success', 'Magic link sent');
    }

    private function validateForm(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email']
        ]);
    }

    public function login(LoginToken $token)
    {
        return $this->auth->authenticate($token) == $this->auth::AUTHENTICATED
            ? redirect()->route('home')
            : redirect()->route('auth.magic.login.form')->with('success', 'Invalid token');
    }
}
