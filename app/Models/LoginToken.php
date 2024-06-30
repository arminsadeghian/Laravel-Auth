<?php

namespace App\Models;

use App\Mail\SendMagicLink;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class LoginToken extends Model
{
    use HasFactory;

    private const TOKEN_EXPIRE_TIME = 120; // Per seconds

    protected $fillable = ['token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function send(array $options)
    {
        Mail::to($this->user->email)->send(new SendMagicLink($this, $options));
    }

    public function isExpired()
    {
        return $this->created_at->diffInSeconds(now()) > self::TOKEN_EXPIRE_TIME;
    }

    public function getRouteKeyName()
    {
        return 'token';
    }
}
