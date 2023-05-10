<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmail
{

    public function __construct()
    {
        //
    }


    public function handle(UserRegistered $event): void
    {
        Mail::send('emails.verify', ['user' => $event->user], function ($message) use ($event) {
            $message->to($event->user->email);
            $message->subject('Verify your email address');
        });
    }
}
