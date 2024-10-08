<?php

namespace App\Events;

use App\Http\Resources\NotiResource;
use App\Models\Notification;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $notification;

    public function __construct(Notification $noti)
    {
        $this->notification = $noti;
        // Log::info('Lịch hẹn đã được tạo :' . $noti);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('notifications');
    }

    public function broadcastWith()
    {
         $data =[
            'id' => (int)$this->notification->id,
            'title' => $this->notification->title,
            'url' => $this->notification->url,
            'message' => $this->notification->message,
            'status' => (int)$this->notification->status,
        ];
        return $data;
       
    }

    public function broadcastAs()
    {
        return 'notice';
    }
}
