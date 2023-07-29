<?php
namespace App\Services\Invoice;

use App\Models\Invoice\Invoice;
use App\Services\ConvertCurrencyService;
use App\Services\SendMailService;
use Carbon\Carbon;

class StoreProductStroreService
{
    private $sendMailServiceClass,$convertCurrencyServiceClass;
    function __construct(SendMailService $sendMailService,ConvertCurrencyService $convertCurrencyService)
    {
        $this->sendMailServiceClass = $sendMailService;
        $this->convertCurrencyServiceClass = $convertCurrencyService;

    }
    function store($request)
    {
        

    }
}