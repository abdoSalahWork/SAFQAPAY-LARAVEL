<?php

namespace App\Services;

use Vonage\Client\Credentials\Basic;
use Vonage\Client;
use Vonage\Verify\Verification;

use App\Models\MultAuth;
use Exception;
// use Twilio\Rest\Client;
use Illuminate\Support\Facades\Mail;



class OtpService
{


    // use RegistersUsers;
    private $SendMailService;
    protected $vonage;

    public function __construct(SendMailService $SendMailService,)
    {
        $basic = new Basic('ae17ec1d', 'IsO5URvB2A7ATxxC');
        $this->vonage = new Client($basic);
        $this->SendMailService = $SendMailService;
    }



    public function createSms($phone, $id, $is_admin)
    {

        // $token = env('TWILIO_AUTH_TOKEN');
        // $twilio_sid = env("TWILIO_SID");
        // $twilio_verify_sid = env("TWILIO_VERIFY_SID");

        // $token = '2ad973ab2c62499b8fd279d525221d72';
        // $twilio_sid = 'ACc2673d7b5bd9b643bcdf18e6e7b9889a';
        // $twilio_verify_sid = 'VA66326fb9bf5bd19b336528561b7ed0c5';

        // // // // // // // // This Twillo abdo salah
        // $token = 'b8aeab50e2075d01d93b531082106d8e';
        // $twilio_sid = 'AC1feea77843cfcaf5f2dbb420e65842fc';
        // $twilio_verify_sid = 'VA069954f568fb58761daf70c946f02e83';
        // $twilio = new Client($twilio_sid, $token);

        // $twilio->verify->v2->services($twilio_verify_sid)
        //     ->verifications
        //     ->create($phone, "sms");



        /////////////////////vonage///////////////////
        // $to = '+201147753351';
        try {

            $verification = new Verification($phone, 'Safqa');
            $response = $this->vonage->verify()->start($verification);

            if ($response->getResponseData()['status'] == '0') {
                MultAuth::where('id_admin_or_user', $id)->where('is_admin', $is_admin)->update(
                    [
                        'otp' => $response->getResponseData()['request_id'],
                    ]
                );
                return;
            }

            return 'sms otp failed';
        } catch (Exception $e) {
            return  response()->json(['message' => $e->getMessage()], 404);
        };
    }

    public function createEmail($email, $id, $is_admin)
    {
        try {
            // $token = getenv("TWILIO_AUTH_TOKEN");
            // $twilio_sid = getenv("TWILIO_SID");
            // $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");

            // $token = '2ad973ab2c62499b8fd279d525221d72';
            // $twilio_sid = 'ACc2673d7b5bd9b643bcdf18e6e7b9889a';
            // $twilio_verify_sid = 'VA66326fb9bf5bd19b336528561b7ed0c5';

            // This Twillo abdo salah
            // $token = 'b8aeab50e2075d01d93b531082106d8e';
            // $twilio_sid = 'AC1feea77843cfcaf5f2dbb420e65842fc';
            // $twilio_verify_sid = 'VA069954f568fb58761daf70c946f02e83';

            // $twilio = new Client($twilio_sid, $token);

            // $twilio->verify->v2->services($twilio_verify_sid)
            //     ->verifications
            //     ->create($email, "email");
            $data['email'] = $email;
            $data['title'] = "safqa";
            $data['otp'] = rand(100000, 999999);
            $this->SendMailService->getInfoMailConfig();
            Mail::send('sendEmailOtp', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
            $multAuth = MultAuth::where('id_admin_or_user', $id)->where('is_admin', $is_admin)->first();
         
            $multAuth->update(
                ['otp' => $data['otp'],]
            );


            return;
        } catch (Exception $e) {
            return  response()->json(['message' => $e->getMessage()], 404);
        }
    }

    // public function sendOTPSms($to)
    // {
    //     try {

    //         $verification = new Verification($to, 'Safqa');
    //         $response = $this->vonage->verify()->start($verification);

    //         if ($response->getResponseData()['status'] == '0') {
    //             // dd($response->getResponseData()['request_id']);
    //             return $response->getResponseData()['request_id'];
    //         }

    //         return null;
    //     } catch (Exception $e) {
    //         return  response()->json(['message' => $e->getMessage()], 404);
    //     }
    // }

    public function verifyOTP($requestId, $code)
    {
        try {
            $response = $this->vonage->verify()->check($requestId, $code);

            return $response->getResponseData()['status'] == '0';
        } catch (Exception $e) {
            return  response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
