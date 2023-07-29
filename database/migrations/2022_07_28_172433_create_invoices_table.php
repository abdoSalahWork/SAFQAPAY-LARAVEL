<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->integer('customer_mobile_code_id')->nullable();
            $table->string('customer_mobile')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_reference')->nullable();

            $table->boolean('is_open_invoice')->default(false);
            $table->boolean('discount_type')->default(false);
            $table->integer('discount_value')->default(0);
            $table->integer('min_invoice')->nullable();
            $table->integer('max_invoice')->nullable();
            $table->integer('invoice_value');
            $table->integer('invoice_display_value');
            $table->dateTime('expiry_date'); // ->default(Date);
            $table->string('attach_file')->nullable();
            $table->integer('remind_after')->default(0);
            $table->text('comments')->nullable();
            $table->text('terms_and_conditions')->nullable();

            $table->unsignedBigInteger('manager_user_id')->nullable();
            $table->unsignedBigInteger('profile_business_id');

            $table->integer('send_invoice_option_id')->default(1);
            $table->unsignedBigInteger('recurring_interval_id')->default(1);
            $table->date('recurring_start_date')->nullable();
            $table->date('recurring_end_date')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('language_id')->default(1);

            $table->string('status')->default('pending');
            $table->boolean('is_order')->default(false);
            $table->string('invoice_type');
            $table->string('civil_id')->nullable();
            


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
        Schema::dropIfExists('invoices');
    }
}
