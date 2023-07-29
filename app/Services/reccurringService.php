<?php

namespace App\Services;

use App\Models\Invoice\Invoice;
use Illuminate\Support\Carbon;


use App\Models\Wallet;

class reccurringService
{

    function reccurring($invoice)
    {
        if ($invoice->status == 'pending' and $invoice->expiry_date > Carbon::now()->toDateString() ) {#and
            if($invoice->is_open_invoice == 0)
            {
                $invoice->update([
                    'status' => 'paid',
                    'amount_changable' => $invoice->invoice_display_value
                ]);
            }
            return $invoice;
        } else if (

            $invoice->status == 'paid' and $invoice->recurring_interval_id == 2 and
            $invoice->recurring_start_date < Carbon::now()->toDateString() and
            Carbon::now()->toDateString() < $invoice->recurring_end_date and
            Carbon::now()->toDateString() > $invoice->updated_at->addWeeks(1)
        ) {

            $invoice->update([
                'updated_at' => Carbon::parse()->now()
            ]);
            return $invoice;
        } else if (
            $invoice->status == 'paid' and
            $invoice->recurring_interval_id == 2 and
            $invoice->recurring_start_date < Carbon::now()->toDateString() and
            Carbon::now()->toDateString() < $invoice->recurring_end_date and
            Carbon::now()->toDateString() > $invoice->updated_at->addmonth(1)
        ) {

            $invoice->update([
                'updated_at' => Carbon::parse()->now()
            ]);
            return $invoice;
        }
        else if ($invoice->status == 'processing' and $invoice->expiry_date > Carbon::now()->toDateString()) {
            $invoice->update([
                'status' => 'paid'
            ]);
            return $invoice;
        }


        return null;
    }
}
