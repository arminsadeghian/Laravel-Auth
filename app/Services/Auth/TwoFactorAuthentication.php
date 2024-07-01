<?php

namespace App\Services\Auth;

use App\Models\TwoFactor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthentication
{
    public const CODE_SENT = 'code.sent';
    public const INVALID_CODE = 'code.invalid';
    public const ACTIVATED = 'activated';
    public const AUTHENTICATED = 'authenticated';

    private $code;

    public function __construct(private Request $request)
    {
    }

    public function requestCode(User $user)
    {
        $code = TwoFactor::generateCodeFor($user);

        $this->setSession($code);

        $code->send();

        return static::CODE_SENT;
    }

    public function activate()
    {
        if (!$this->isValidCode()) return static::INVALID_CODE;

        $this->getToken()->delete();

        $this->getUser()->activateTwoFactor();

        $this->forgetSession();

        return static::ACTIVATED;
    }

    public function deactivate(User $user)
    {
        return $user->deactivateTwoFactor();
    }

    public function login()
    {
        if (!$this->isValidCode()) return static::INVALID_CODE;

        $this->getToken()->delete();

        Auth::login($this->getUser(), session('remember'));

        $this->forgetSession();

        return static::AUTHENTICATED;
    }

    public function resend()
    {
        return $this->requestCode($this->getUser());
    }

    private function setSession(TwoFactor $code)
    {
        session([
            'code_id' => $code->id,
            'user_id' => $code->user_id,
            'remember' => $this->request->remember
        ]);
    }

    private function forgetSession()
    {
        session(['user_id', 'code_id', 'remember']);
    }

    private function isValidCode()
    {
        return !$this->getToken()->isExpired() and $this->getToken()->isEqualWith($this->request->code);
    }

    private function getToken()
    {
        return $this->code ?? $this->code = TwoFactor::findOrFail(session('code_id'));
    }

    private function getUser()
    {
        return User::findOrFail(session('user_id'));
    }
}
