<?php
namespace App\Channels;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class WhatsAppChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toWhatsApp($notifiable);

        $twilio_number = getenv("TWILIO_WHATSAPP_FROM");
        $to = $notifiable->routeNotificationFor('WhatsApp');
        $from = $twilio_number;

        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($account_sid, $auth_token);

        $message = null;
        try{
          $twilio->messages->create('whatsapp:' . $to, [
            "from" => 'whatsapp:' . $from,
            "body" => $message->content
        ]);
        }catch(Exception $ex){

        }

        return $message;
    }
}
