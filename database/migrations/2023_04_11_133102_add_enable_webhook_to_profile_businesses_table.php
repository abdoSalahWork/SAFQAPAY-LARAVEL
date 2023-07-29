<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnableWebhookToProfileBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('web_hooks', function (Blueprint $table) {
            $table->dropColumn('enable_webhook');
        });
        Schema::table('profile_businesses', function (Blueprint $table) {
            $table->boolean('enable_webhook')->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_businesses', function (Blueprint $table) {
            //
        });
    }
}
