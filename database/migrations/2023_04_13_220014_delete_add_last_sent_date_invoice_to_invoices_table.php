<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class DeleteAddLastSentDateInvoiceToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::table('invoices', function (Blueprint $table) {
            $table->dateTime('last_sent_date')->default(Carbon::now());
            
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('last_sent_date');
            
        });
      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
}
