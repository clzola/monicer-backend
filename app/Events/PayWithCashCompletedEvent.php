<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PayWithCashCompletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $returnToCustomer;

    public function __construct($cashBashAmount)
    {
        $this->returnToCustomer = $cashBashAmount;
    }


    public function broadcastAs()
    {
        return "payment.cash_completed";
    }


    public function broadcastOn()
    {
        return new Channel('test');
    }
}
