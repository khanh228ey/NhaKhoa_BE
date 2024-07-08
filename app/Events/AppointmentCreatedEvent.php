<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AppointmentCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
        Log::info('AppointmentCreatedEvent fired for appointment ID: ' . $appointment->id);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('appointment-created-channel');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->appointment->id,
            'name' => $this->appointment->name,
            'phone' => $this->appointment->phone,
            'date' => $this->appointment->date,
            'time' => $this->appointment->time,
        ];
    }

    public function broadcastAs()
    {
        return 'appointment-created';
    }
}
