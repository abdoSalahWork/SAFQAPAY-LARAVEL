<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebHooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_hooks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id');
            $table->boolean('enable_webhook');
            $table->string('Endpoint');
            $table->boolean('enable_secret_key');
            $table->string('webhook_secret_key');
            $table->boolean('transaction_status_changed');
            $table->boolean('balance_transferred');
            $table->boolean('recurring_status_changed');
            $table->boolean('refund_status_changed');
            $table->boolean('supplier_status_changed');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_hooks');
    }
}
