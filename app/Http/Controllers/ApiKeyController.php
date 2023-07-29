<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;

class ApiKeyController extends Controller
{
    public function index(){
        $profile_business_id = auth()->user()->profile_business_id;
        $api_key = ApiKey::where('profile_id' , $profile_business_id )->first();
        return response()->json(['api_key' =>$api_key ]);
    }
}
