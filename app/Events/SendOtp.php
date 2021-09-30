<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendOtp
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mobile,$message, $type;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($mobile,$message,$type)
    {
        $this->message=$message;
        $this->mobile=$mobile;
        $this->type=$type;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
