<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;

class AuthApiToken
{
    public function handle(Request $request, Closure $next)
    {
        $access_token = $request->header('access_token');
        $api_key = ApiKey::where('token' , $access_token )->first();
        if($api_key)
        {
            return $next($request);
        }
        return response()->json(['message'=>"This Token Is Not Found"],404);

    }
}
