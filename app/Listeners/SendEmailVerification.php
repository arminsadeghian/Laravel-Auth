<?php

namespace App\Listeners;

use App\Events\UserRegistered;

class SendEmailVerification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $event->user->sendEmailVerificationNotification();
    }
}
