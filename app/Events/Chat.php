<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Chat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $user_id;
    public $message;
    public $recivedUnreadedMessages;
    public $chat_user;
    public function __construct($id, $message, $recivedUnreadedMessages, $chat_user)
    {
        $this->user_id = $id;
        $this->message = $message;
        $this->recivedUnreadedMessages = $recivedUnreadedMessages;
        $this->chat_user = $message->froom_user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('Chat.'.$this->user_id);
    }

    public function broadcastAs()
    {
        return 'Chat';
    }
}
