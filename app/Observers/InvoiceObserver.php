<?php

namespace App\Observers;

use App\Models\Invoice\Invoice;
use App\Models\Invoice\Invoice as InvoiceInvoice;
use App\Models\Invoice\InvoiceItem;
use Illuminate\Http\Request;


class InvoiceObserver
{

    public function created(Invoice $invoice)
    {
        // if ($invoice) {
        //     foreach (request()->prductItems as $prductItem) {
        //         InvoiceItem::create([
        //             'invoice_id' => $invoice->id,
        //             'product_name' => $prductItem['product_name'],
        //             'product_quantity' => $prductItem['product_quantity'],
        //             'product_price' => $prductItem['product_price'],
        //         ]);
        //     }
        // }

    }

    /**
     * Handle the Invoice "updated" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function updated(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "deleted" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function deleted(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "restored" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function restored(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "force deleted" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function forceDeleted(Invoice $invoice)
    {
        //
    }
}
