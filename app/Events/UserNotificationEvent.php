<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserNotificationEvent implements ShouldBroadcast
{
    // NotificationEvent
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public string $message;
    public $type,$profile_id,$userName,$api;

    public function __construct($message,$api,$type,$profile_id,$userName)
    {
        $this->message = $message;
        $this->profile_id = $profile_id;
        $this->type = $type;
    }
    public function broadcastAs()
    {
        return 'userNotification';
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel("user_notification.$this->type.$this->profile_id");
    }
}
