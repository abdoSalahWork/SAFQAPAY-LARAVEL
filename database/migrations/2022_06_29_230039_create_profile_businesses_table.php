<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_businesses', function (Blueprint $table) {
            $table->id();
            $table->integer('country_id');
            $table->integer('phone_number_code_id');
            $table->integer('business_type_id');
            $table->integer('category_id');
            $table->integer('invoice_expiry_after_number')->default(1);
            $table->integer('invoice_expiry_after_type_id')->default(1);
            $table->integer('language_id');
            $table->integer('deposit_terms_id')->default(1);

            $table->string('company_name');
            $table->string('name_en');
            $table->string('name_ar');

            $table->string('logo')->nullable(); // optional
            $table->string('website_url')->nullable();
            $table->string('work_email');
            $table->string('phone_number');
            $table->text('custom_sms_ar')->nullable();
            $table->text('custom_sms_en')->nullable();

            $table->text('terms_and_conditions')->nullable();
            $table->float('products_delivery_fees')->default(0);
            $table->string('promo_code')->nullable(); // not work

            $table->string('bank_account_name');
            $table->integer('bank_id');
            $table->string('account_number');
            $table->string('iban');
            $table->string('bank_account_letter')->nullable();

            // $table->string('others')->nullable();
            // $table->string('civil_id')->nullable();
            // $table->string('civil_id_back')->nullable();

            // $table->string('card_stripe_id');


            $table->string('theme_color')->nullable();
            $table->boolean('enable_new_design')->default(true);
            $table->boolean('show_all_currencies')->default(true);
            $table->boolean('enable_card_view')->default(true);

            $table->boolean('is_approval')->default(false);
            $table->boolean('is_enable')->default(true);
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
        Schema::dropIfExists('profile_businesses');
    }
}
