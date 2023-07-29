<?php

namespace App\Console\Commands;

use App\Models\Invoice\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ExpireInvoiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire:invoice';

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Invoice::where('status', 'pending')
            ->where('expiry_date', '<', Carbon::tomorrow())->update(['status' => 'unpaid']);
    }
}
