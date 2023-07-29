<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_title');
            $table->string('payment_amount');
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('language_id')->default(1);
            $table->boolean('open_amount')->default(false);
            $table->text('comment')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->string('max_amount')->nullable();
            $table->string('min_amount')->nullable();
            $table->unsignedBigInteger('manager_user_id');
            $table->unsignedBigInteger('profile_business_id');

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
        Schema::dropIfExists('payments');
    }
}
