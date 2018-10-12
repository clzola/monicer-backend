<?php

namespace App\Events;

use App\Http\Resources\TransactionResource;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PaymentConfirmedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payCode;
    public $returnedAmount;
    public $transaction;

    /**
     * Create a new event instance.
     *
     * @param $newPayCode
     */
    public function __construct($newPayCode, $returnedAmount, $transaction)
    {
        $this->payCode = $newPayCode;
        $this->returnedAmount = $returnedAmount;
        $this->transaction = (new TransactionResource($transaction))->toArray(null);
    }

    public function broadcastAs()
    {
        return 'payment.confirmation';
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
