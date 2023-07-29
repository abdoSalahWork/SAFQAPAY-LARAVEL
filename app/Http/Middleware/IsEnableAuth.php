<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\User;
use App\Services\OtpService;
use Closure;
use Illuminate\Http\Request;

class IsEnableAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    private $otpService;
    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }
    public function handle(Request $request, Closure $next)
    {
        $user =  User::where('email', $request->email)->first();
        $admin =  Admin::where('email', $request->email)->first();

        if ($user) {
            if (!$user->profileBusiness->is_enable) {
                return response()->json(['message' => 'The user’s profile Is Not Enable'], 404);
            } else if (!$user->is_enable) {
                return response()->json(['message' => 'The user Is Not Enable'], 404);
            } else if (!$user->confirm_email) {
                if (!$this->otpService->createEmail($user->email, $user->id, 0)) {
                    return response()->json(['message' => 'Check Your Email To Confirm It']);
                } else {
                    return $this->otpService->createEmail($user->email, $user->id, 0);
                }
            }
            return $next($request);
        } else if ($admin) {
            return $next($request);
        }
        return response()->json(['message' => 'This User Is Not Found'], 404);
    }
}
