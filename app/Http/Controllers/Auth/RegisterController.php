<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

//    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validateForm($request);

        $user = $this->create($request->all());

        Auth::login($user);

        event(new UserRegistered($user));

        return redirect()->route('home')->with('success', 'Register successful');
    }

    private function validateForm(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'cellphone' => ['required', 'digits:11', 'unique:users,cellphone'],
            'email' => ['required', 'string', 'email', 'min:3', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => ['required', new Recaptcha()]
        ],

            [
                'g-recaptcha-response.required' => 'Please check recaptcha'
            ]
        );
    }

    private function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'cellphone' => $data['cellphone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
