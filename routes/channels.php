<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


Broadcast::channel('user_notification.{type}.{profile_id}', function ($user, $type, $profile_id) {
    return $user->profile_business_id === (int) $profile_id && $user->$type == true;
});

Broadcast::channel('admin_Notification', function ($admin) {
    return Admin::where('email', $admin->email)->first();
});
