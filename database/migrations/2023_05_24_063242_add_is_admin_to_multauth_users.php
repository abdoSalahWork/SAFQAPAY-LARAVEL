<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAdminToMultauthUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('multauth_users', function (Blueprint $table) {
            $table->dropColumn('user_id');

        });
        Schema::table('multauth_users', function (Blueprint $table) {
            $table->boolean('is_admin');
            $table->unsignedBigInteger('id_admin_or_user');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('multauth_users', function (Blueprint $table) {
            //
        });
    }
}
