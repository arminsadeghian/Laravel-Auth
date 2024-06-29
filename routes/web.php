<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
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
});
