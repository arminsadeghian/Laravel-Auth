<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\MagicController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('auth/')->group(function () {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('auth.register.form');
    Route::post('register', [RegisterController::class, 'register'])->name('auth.register');
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('auth.login.form');
    Route::post('login', [LoginController::class, 'login'])->name('auth.login');
    Route::get('logout', [LoginController::class, 'logout'])->name('auth.logout');
    Route::get('email/send-verification', [VerificationController::class, 'send'])->name('auth.email.send.verification');
    Route::get('email/verify', [VerificationController::class, 'verify'])->name('auth.email.verify');
    Route::get('password/forgot', [ForgotPasswordController::class, 'showResetPasswordRequestForm'])->name('auth.password.forgot.form');
    Route::post('password/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('auth.password.forgot');
    Route::get('password/reset', [ResetPasswordController::class, 'showResetForm'])->name('auth.password.reset.form');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('auth.password.reset');
    Route::get('redirect/{provider}', [SocialController::class, 'redirectToProvider'])->name('auth.redirect.provider');
    Route::get('{provider}/callback', [SocialController::class, 'providerCallback'])->name('auth.provider.callback');
    Route::get('magic/login', [MagicController::class, 'showMagicForm'])->name('auth.magic.login.form');
    Route::post('magic/login', [MagicController::class, 'sendMagicLink'])->name('auth.magic.link.send');
    Route::get('magic/login/{token}', [MagicController::class, 'login'])->name('auth.magic.login');
    Route::get('two-factor/toggle', [TwoFactorController::class, 'showToggleForm'])->name('auth.two.factor.toggle.form');
    Route::get('two-factor/activate', [TwoFactorController::class, 'activate'])->name('auth.two.factor.activate');
    Route::get('two-factor/deactivate', [TwoFactorController::class, 'deactivate'])->name('auth.two.factor.deactivate');
    Route::get('two-factor/code', [TwoFactorController::class, 'showEnterCodeForm'])->name('auth.two.factor.code.form');
    Route::post('two-factor/code', [TwoFactorController::class, 'confirmCode'])->name('auth.two.factor.code');
    Route::get('login/code', [LoginController::class, 'showCodeForm'])->name('auth.login.code.form');
    Route::post('login/code', [LoginController::class, 'confirmCode'])->name('auth.login.code');
});
