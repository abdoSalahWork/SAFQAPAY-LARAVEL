<?php

namespace App\Console\Commands;

use App\Models\Invoice\Invoice;
use App\Services\SendMailService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecurringIntervalInvoiceCommand extends Command
{
  

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private   $sendMailService;

    public function __construct(SendMailService $sendMailService)
    {
        $this->sendMailService = $sendMailService;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $invoices = Invoice::where('status', '!=', 'unpaid')->where('recurring_interval_id', '!=', 1)
            ->where('recurring_end_date', '>=', Carbon::today())->get();
        foreach ($invoices as $invoice) {
            if ($invoice->recurring_interval_id == 2 && Carbon::parse($invoice->last_sent_date)->addWeek()->toDateString() == Carbon::now()->toDateString() && $invoice->recurring_start_date <= Carbon::now()->toDateString()) ## && Carbon::parse($invoice->last_sent_date)->addWeek()->toDateString() == Carbon::now()->toDateString()
            {

                $invoice->last_sent_date->update(
                    [
                        'last_sent_date' => Carbon::now()
                    ]
                );

            } else if ($invoice->recurring_interval_id == 3 && Carbon::parse($invoice->last_sent_date)->addMonth()->toDateString() == Carbon::now()->toDateString() && $invoice->recurring_start_date <= Carbon::now()->toDateString()) {
                $invoice->last_sent_date->update(
                    [
                        'last_sent_date' => Carbon::now()
                    ]
                );
            }
            $this->sendMailService->send_email_recurring_interval_invoice($invoice);

        }
        // ->where('expiry_date', '<', Carbon::tomorrow())->update(['status' => 'unpaid']);
    }
}
