<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('role_id');
            $table->integer('profile_business_id');
            $table->string('phone_number_code_manager_id');
            $table->string('phone_number_manager')->unique();
            $table->string('email')->unique();
            $table->string('full_name');
            $table->integer('nationality_id');
            $table->boolean('is_enable')->default(true);
            // $table->boolean('is_main')->default(false);


            $table->string('avatar')->nullable();

            $table->boolean('enable_bell_sound')->default(true);
            $table->boolean('confirm_email')->default(false);
            $table->boolean('confirm_phone')->default(false);
            $table->boolean('batch_invoices')->default(false);
            $table->boolean('deposits')->default(false);
            $table->boolean('payment_links')->default(false);
            $table->boolean('profile')->default(false);

            $table->boolean('users')->default(false);
            $table->boolean('refund')->default(false);
            $table->boolean('show_all_invoices')->default(false);
            $table->boolean('customers')->default(false);
            $table->boolean('invoices')->default(false);
            $table->boolean('products')->default(false);
            $table->boolean('commissions')->default(false);
            $table->boolean('account_statements')->default(false);

            $table->boolean('orders')->default(false);
            $table->boolean('suppliers')->default(false);
            $table->boolean('notification_create_invoice')->default(false);
            $table->boolean('notification_invoice_paid')->default(false);
            $table->boolean('notification_new_order')->default(false);
            $table->boolean('notification_create_batch_invoice')->default(false);
            $table->boolean('notification_deposit')->default(false);
            $table->boolean('notification_create_recurring_invoice')->default(false);

            $table->boolean('notification_refund_transfered')->default(false);
            $table->boolean('notification_notifications_service_request')->default(false);
            $table->boolean('notification_notifications_hourly_deposit_rejected')->default(false);
            $table->boolean('notification_approve_vendor_account')->default(false);
            $table->boolean('notification_create_shipping_invoice')->default(false);

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
