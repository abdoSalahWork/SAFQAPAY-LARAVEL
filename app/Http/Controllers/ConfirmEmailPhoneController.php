<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;


class ConfirmEmailPhoneController extends Controller
{
    private $otpService;
    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function sendCodePhone()
    {
        $validation = Validator::make(request()->all(), [
            'phone' => ['required', 'exists:users,phone_number_manager'],
        ]);

        if ($validation->fails()) {
            return response($validation->errors(), 404);
        }
        if (isset(request()->phone)) {
            $user = User::where('phone_number_manager', request()->phone)->first();
            if ($user) {
                $createSms =  $this->otpService->createSms($user->phoneNumberCode->code . $user->phone_number_manager, $user->id, 0);
                if ($createSms) {
                    return response()->json([
                        'message' => $createSms,
                    ], 404);
                } else {
                    return response()->json([
                        'message' => 'check your Sms to confirm your phone ',
                        'multiAuth' => true,
                    ]);
                }
            } else {
                return  response()->json(['message' => 'this phone is not found'], 404);
            }
        }
    }

    public function confirmCodePhone(Request $request)
    {

        $validation = Validator::make(request()->all(), [
            'verification_code' => ['required', 'numeric'],
            'phone' => ['required', 'exists:users,phone_number_manager'],
        ]);
        if ($validation->fails()) {
            return response($validation->errors(), 404);
        }
        $user_phone = User::Where('phone_number_manager', $request->phone)->with('multAuth')->first();

        try {

            /* Get credentials from .env */
            if ($user_phone) {
                $verification = $this->otpService->verifyOTP($user_phone->multAuth->otp, $request->verification_code);
                if ($verification) {
                    $user_phone->update([
                        'confirm_phone' => true
                    ]);
                    return response()->json(['message' => 'Phone Confirmed Success']);
                } else {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            } else {
                return response()->json(['message' => 'this user not found'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function confirmCodeEmail(Request $request)
    {

        $validation = Validator::make(request()->all(), [
            'verification_code' => ['required', 'numeric'],
            'email' => ['required', 'exists:users,email'],
        ]);
        if ($validation->fails()) {
            return response($validation->errors(), 404);
        }
        $user_email = User::Where('email', $request->email)->first();

        try {

            /* Get credentials from .env */
            if ($user_email) {
                $verification = $this->verificationChecks($request->email, $request->verification_code);

                if ($verification->valid) {
                    $user_email->update(['confirm_email' => true]);
                    return response()->json(['message' => 'Email Confirmed']);
                }
            } else {
                return response()->json(['message' => 'this user not found'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
    public function verificationChecks($otp, $verification_code)
    {
        // $token = getenv("TWILIO_AUTH_TOKEN");
        // $twilio_sid = getenv("TWILIO_SID");
        // $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        // $token = '2ad973ab2c62499b8fd279d525221d72';
        // $twilio_sid = 'ACc2673d7b5bd9b643bcdf18e6e7b9889a';
        // $twilio_verify_sid = 'VA66326fb9bf5bd19b336528561b7ed0c5';

        // This Twillo abdo salah
        $token = 'b8aeab50e2075d01d93b531082106d8e';
        $twilio_sid = 'AC1feea77843cfcaf5f2dbb420e65842fc';
        $twilio_verify_sid = 'VA069954f568fb58761daf70c946f02e83';
        $twilio = new Client($twilio_sid, $token);

        $verification = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create(
                [
                    "to" => $otp,
                    "code" => $verification_code,
                ]
            );
        return $verification;
    }
}
