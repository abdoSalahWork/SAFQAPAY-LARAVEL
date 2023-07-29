<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    // 'addressType_id', 'city_id', 'area_id',
    // 'block', 'avenue','street','bldgNo',
    // 'appartment','floor','instructions'

    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_business_id');
            $table->unsignedBigInteger('manager_user_id');
            $table->unsignedBigInteger('addressType_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('area_id');
            $table->string('block');
            $table->string('avenue');
            $table->string('street');
            $table->string('bldgNo')->nullable();
            $table->string('appartment')->nullable();
            $table->string('floor')->nullable();
            $table->string('instructions')->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
