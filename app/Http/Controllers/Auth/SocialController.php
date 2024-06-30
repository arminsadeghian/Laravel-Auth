<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirectToProvider($driver)
    {
        // Redirect to login with provider
        return Socialite::driver($driver)->redirect();
    }

    public function providerCallback($driver)
    {
        $user = Socialite::driver($driver)->user();

        Auth::login($this->findOrCreateUser($user, $driver));

        return redirect()->to('home');
    }

    private function findOrCreateUser($user, $driver)
    {
        return User::firstOrCreate([
            'email' => $user->getEmail()
        ], [
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'provider' => $driver,
            'provider_id' => $user->getId(),
            'avatar' => $user->getAvatar(),
            'email_verified_at' => now()
        ]);
    }
}
