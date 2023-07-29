<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPremissionsToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->boolean('wallet')->default(false);
            $table->boolean('admins')->default(false);
            $table->boolean('profiles')->default(false);
            $table->boolean('invoices')->default(false);
            $table->boolean('refunds')->default(false);
            $table->boolean('addresses')->default(false);
            $table->boolean('languages')->default(false);
            $table->boolean('banks')->default(false);
            $table->boolean('business_categories')->default(false);
            $table->boolean('business_types')->default(false);
            $table->boolean('payment_methods')->default(false);
            $table->boolean('social_media')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            //
        });
    }
}
