<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\ResetPassword;
use App\Mail\VerificationEmail;
use App\Services\Auth\Traits\HasTwoFactor;
use App\Services\Auth\Traits\MagicallyAuthenticable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, MagicallyAuthenticable, HasTwoFactor;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cellphone',
        'provider',
        'provider_id',
        'avatar',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function sendEmailVerificationNotification()
    {
        Mail::to($this->email)->send(new VerificationEmail($this));
    }

    public function sendPasswordResetNotification($token)
    {
        Mail::to($this->email)->send(new ResetPassword($this, $token));
    }
}
