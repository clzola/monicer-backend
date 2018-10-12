<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RoomJoinEvent implements  ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $roomCode;
    public $customerId;
    public $customerName;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($code, $cid, $cname)
    {
        $this->roomCode = $code;
        $this->customerId = $cid;
        $this->customerName = $cname;
    }

    public function broadcastAs()
    {
        return "rooms.join_" . $this->roomCode;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('test');
    }
}
