<?php

namespace App\Models;

use App\Mail\SendTwoFactorCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class TwoFactor extends Model
{
    use HasFactory;

    public const CODE_EXPIRE_TIME = 60; //Seconds

    protected $fillable = ['user_id', 'code'];

    public static function generateCodeFor(User $user)
    {
        $user->code?->delete();

        return static::create([
            'user_id' => $user->id,
            'code' => mt_rand(10000, 99999)
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function send()
    {
        Mail::to($this->user->email)->send(new SendTwoFactorCode($this));
    }

    public function isExpired()
    {
        return $this->created_at->diffInSeconds(now()) > static::CODE_EXPIRE_TIME;
    }

    public function isEqualWith($code)
    {
        return $this->code == $code;
    }
}
