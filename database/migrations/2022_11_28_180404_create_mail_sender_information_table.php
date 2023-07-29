<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailSenderInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_sender_information', function (Blueprint $table) {
            $table->id();
            $table->string('transport');
            $table->string('host');
            $table->string('encryption');
            $table->string('username');
            $table->string('password');
            $table->string('address');
            $table->string('name');
            $table->integer('port');
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
        Schema::dropIfExists('mail_sender_information');
    }
}
