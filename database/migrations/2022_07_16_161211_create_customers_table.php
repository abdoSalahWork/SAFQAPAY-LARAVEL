<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('customer_reference')->nullable();
            $table->unsignedBigInteger('phone_number_code_id');
            $table->string('phone_number');
            $table->integer('bank_id')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('iban')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
