<?php

use App\Models\Notification;
use App\Models\ProfileBusiness;
use App\Models\User;

function notification($item_id, $profile_business_id, $text, $api, $column)
{
    $users = User::where('profile_business_id',  $profile_business_id)
        ->where($column, true)
        ->get();
    foreach ($users as $user) {
        $data_notification = array(
            "invoice_id" => $item_id,
            "creator_name" => auth()->user()->full_name,
        );
        $data_notification = json_encode($data_notification);
        Notification::create([
            'data' => $data_notification,
            'user_id' => $user->id,
            'text' => $text,
            'api' => $api
        ]);
    }
}

function check_user($profile = null)
{
    if (auth()->guard('admin')->user()) {
        if (!$profile) {
            return false;
        }
        return ProfileBusiness::find($profile);
    }
    return ProfileBusiness::find(auth()->user()->profile_business_id);
}
