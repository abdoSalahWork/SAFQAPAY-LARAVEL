<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public string $message;
    public $type,$profile_id,$user_id,$api;

    public function __construct($message,$api,$type,$profile_id,$user_id)
    {
        $this->message = $message;
        $this->profile_id = $profile_id;
        $this->api = $api;
        $this->type = $type;
        $this->user_id = $user_id;
    }
    public function broadcastAs()
    {
        return 'adminNotification';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel("admin_Notification");
    }
}
