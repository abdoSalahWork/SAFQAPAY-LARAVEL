<?php

namespace App\Services;

use App\Events\AdminNotificationEvent;
use App\Events\UserNotificationEvent;
use App\Models\AdminNotification;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\SendPushNotification;

class NotificationService

{
    function notification($item_id, $profile_business_id, $text, $api, $column, $creator_name = NULL)
    {
        $userName = $creator_name ? $creator_name : auth()->user()->full_name;


        Notification::create([
            "notificationType" => $column,
            "type_id" => $item_id,
            "creator_name" => $userName,
            'profile_id' => $profile_business_id,
            'text' => $text,
            'api' => $api
        ]);

        event(new UserNotificationEvent($text, $api, $column, $profile_business_id, $userName));
    }

    function adminNotification($item_id, $profile_business_id, $text, $api, $column,$user_id)
    {
        AdminNotification::create([
            "notificationType" => $column,
            "type_id" => $item_id,
            'profile_id' => $profile_business_id,
            'text' => $text,
            'api' => $api,
            'user_id' => $user_id
        ]);

        event(new AdminNotificationEvent($text, $api, $column, $profile_business_id,$user_id));
    }
}
