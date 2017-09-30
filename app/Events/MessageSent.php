<?php

namespace App\Events;

use App\User;
use App\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * User that sent the message
     *
     * @var Sender
     */
    public $sender;

    /**
     * User that receives the message
     *
     * @var Receiver
     */
    public $receiver;

    /**
     * Message body
     *
     * @var Body
     */
    public $body;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $sender, User $receiver, Message $body)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->body = $body;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat');
    }
}
