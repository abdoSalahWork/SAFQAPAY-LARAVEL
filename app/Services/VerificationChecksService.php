<?php

namespace App\Services;

use Twilio\Rest\Client;

class VerificationChecksService
{
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