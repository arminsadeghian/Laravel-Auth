<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\TwoFactorAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function __construct(private TwoFactorAuthentication $twoFactor)
    {
        $this->middleware('auth');
    }

    public function showToggleForm()
    {
        return view('auth.two-factor.toggle');
    }

    public function activate()
    {
        $response = $this->twoFactor->requestCode(Auth::user());

        return $response == $this->twoFactor::CODE_SENT
            ? redirect()->route('auth.two.factor.code.form')->with('success', 'Two factor code sent to your email')
            : back()->with('failed', 'Cant send code!');
    }

    public function deactivate()
    {
        $this->twoFactor->deactivate(Auth::user());

        return back()->with('success', 'Two-Factor deactivated');
    }

    public function showEnterCodeForm()
    {
        return view('auth.two-factor.enter-code');
    }

    public function confirmCode(Request $request)
    {
        $this->validateForm($request);

        $response = $this->twoFactor->activate();

        return $response == $this->twoFactor::ACTIVATED
            ? redirect()->route('home')->with('success', 'Two-Factor activated')
            : back()->with('failed', 'Activation failed');
    }

    private function validateForm(Request $request)
    {
        $request->validate([
            'code' => ['required', 'numeric', 'digits:5', 'exists:two_factors,code']
        ], [
            'code.digits' => 'Invalid code'
        ]);
    }
}
