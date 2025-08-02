<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RideStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rideId;
    public $status;
    public $driverInfo;
    public $estimatedArrival;

    /**
     * Create a new event instance.
     */
    public function __construct($rideId, $status, $driverInfo = null, $estimatedArrival = null)
    {
        $this->rideId = $rideId;
        $this->status = $status;
        $this->driverInfo = $driverInfo;
        $this->estimatedArrival = $estimatedArrival;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('ride.' . $this->rideId),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'ride_id' => $this->rideId,
            'status' => $this->status,
            'driver_info' => $this->driverInfo,
            'estimated_arrival' => $this->estimatedArrival,
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'status.updated';
    }
}
