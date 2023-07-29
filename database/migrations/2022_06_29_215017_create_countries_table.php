<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name_en')->uniqid();
            $table->string('name_ar')->uniqid();
            $table->string('short_name')->uniqid();
            $table->string('code')->uniqid();
            $table->string('nationality_en');
            $table->string('nationality_ar');
            $table->string('flag')->uniqid();
            $table->string('currency')->uniqid();
            $table->string('short_currency')->uniqid();
            $table->boolean('country_active')->default(false);
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
        Schema::dropIfExists('countries');
    }
}
