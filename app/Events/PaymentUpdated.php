<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentUpdated implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $invoiceId;
    /**
     * Create a new event instance.
     */
    public function __construct($invoiceId) {
        $this->invoiceId = $invoiceId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn() {
        return new Channel('payment-channel');
    }

    // public function broadcastAs() {
    //     return 'App.Events.PaymentUpdated';
    // }

    // public function broadcastAs() {
    //     return 'payment.updated';
    // }
}
