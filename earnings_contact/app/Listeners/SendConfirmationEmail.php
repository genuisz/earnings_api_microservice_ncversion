<?php

namespace App\Listeners;

use App\Event\RegisteredEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\GeneratedVerifiedURL;
use App\Mailable\SendRegisterConfimationMailable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
class SendConfirmationEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $connection = 'rabbitmq';

    public $queue = 'contact_queue';
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(RegisteredEvent $event)
    {
        //

        dump($event->data);
        Mail::to($event->data['email'])->send(new SendRegisterConfimationMailable($event->data));
        // VerifyEmail::createUrlUsing(function($notifiable)use ($event){
        //     return env('API_GATEWAY_HOST').$event->data;
        // });
        // VerifyEmail::toMailUsing(function ($notifiable,$url){
        //     $mail = new MailMessage;
        //     $from_address = config('mail.from.address');
        //     $from_name = ('test');
        //     $mail->from($from_address, $from_name);
        //     $mail->subject('test');
        //     $mail->line($url);
        //     return $mail;
        // });
        // $user = new User();
        // $user->sendEmailVerificationNotification();
    }
}
