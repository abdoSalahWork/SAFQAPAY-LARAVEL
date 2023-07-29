<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Notification;
use Illuminate\Support\Facades\Config;

class NotificationController extends Controller
{
    public function index()
    {
        $notificationPermissions = Config::get('permissions.notification');
        $user = auth()->user();
        foreach($notificationPermissions as $item)
        {
            if($user->$item)
            {
                $permissions [] = $item;
            }
        }
        $notifications = Notification::where('profile_id', $user->profile_business_id)
        ->whereIn('notificationType',$permissions)->get();
        return  response()->json(['data' => $notifications]);
    }

    public function index_admin(){
       $notifications = AdminNotification::get();
       return  response()->json(['data' => $notifications]);

    }
}
