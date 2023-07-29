<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('profile_id');
            $table->string('payment_gateway');
            $table->integer('card_number');
            $table->string('expiration_date');
            $table->integer('security_code');
            $table->string('card_holder_name');

            $table->boolean('transaction_status');
            
            
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('authorization_id');
            $table->unsignedBigInteger('track_iD');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('errror')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
