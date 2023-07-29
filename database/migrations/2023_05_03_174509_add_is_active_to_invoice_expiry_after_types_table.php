<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActiveToInvoiceExpiryAfterTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_expiry_after_types', function (Blueprint $table) {
            $table->boolean('is_active');
            $table->dropColumn('default');

        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_expiry_after_types', function (Blueprint $table) {
            //
        });
    }
}
