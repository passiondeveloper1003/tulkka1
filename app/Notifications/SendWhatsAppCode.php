<?php

namespace App\Notifications;

use App\Channels\WhatsAppChannel;
use Illuminate\Notifications\Notification;
use App\Channels\WhatsAppMessage;

class SendWhatsAppCode extends Notification
{
    private $notifiable;

    /**
     * Create a new notification instance.
     *
     * @param $notifiable
     */
    public function __construct($notifiable)
    {
        $this->notifiable = $notifiable;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WhatsAppChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     */

    public function toWhatsApp($notifiable)
    {
      $orderUrl = url("/orders/{$this->order->id}");
      $company = 'Acme';
      $deliveryDate = $this->order->created_at->addDays(4)->toFormattedDateString();


      return (new WhatsAppMessage)
          ->content("Your {$company} order of {$this->order->name} has shipped and should be delivered on {$deliveryDate}. Details: {$orderUrl}");
    }
    public function toTwilioSMS($notifiable)
    {
        return [
            'to' => $notifiable->mobile,
            'content' => $notifiable->code,
        ];
    }
}
