<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;
use URL;

class SendActivationCode
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        $user = $event->user;
        $link = URL::route('account.activate', $user->hash);
        
        $this->mailer->send('emails.auth.activate', array('username' => $user->username, 'link' => $link), function($message) use ($user) {

            $message->to($user->email, $user->username)->subject('Активируйте ваш аккаунт');
                
        });
    }
}
