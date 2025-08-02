<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RideLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rideId;
    public $latitude;
    public $longitude;
    public $speed;
    public $bearing;

    /**
     * Create a new event instance.
     */
    public function __construct($rideId, $latitude, $longitude, $speed = null, $bearing = null)
    {
        $this->rideId = $rideId;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->speed = $speed;
        $this->bearing = $bearing;
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
            'location' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'speed' => $this->speed,
                'bearing' => $this->bearing,
                'timestamp' => now()->toISOString()
            ]
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'location.updated';
    }
}
