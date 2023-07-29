<?php

namespace App\Services;

use Exception;
use Vonage\Client\Credentials\Basic;
use Vonage\Client;
use Vonage\Verify\Verification;

class SendSmsService

{
    protected $vonage;

    public function __construct()
    {
        $basic = new Basic('ae17ec1d', 'IsO5URvB2A7ATxxC');
        $this->vonage = new Client($basic);
    }

    public function sendSMS($number, $Invoice_id, $text , $url)
    {
        $url = 'safqapay.com/payInvoice/' . $Invoice_id;

        try {
            $response = $this->vonage->sms()->send(
                new \Vonage\SMS\Message\SMS(
                    "$number",
                    'SAFQA',
                    "$text $url"
                )
            );
            $message = $response->current();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
