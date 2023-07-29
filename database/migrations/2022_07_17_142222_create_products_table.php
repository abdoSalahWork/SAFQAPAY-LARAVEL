<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            $table->string('name_en');
            $table->string('name_ar');
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('width')->nullable();
            $table->string('length')->nullable();
            $table->string('product_image')->nullable();

            $table->text('description_en');
            $table->text('description_ar');

            $table->integer('quantity');
            $table->integer('price');

            $table->unsignedBigInteger('currency_id');

            $table->boolean('in_store');
            $table->boolean('is_stockable');
            $table->boolean('disable_product_on_sold');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_shipping_product');

            $table->unsignedBigInteger('manager_user_id');
            $table->unsignedBigInteger('profile_business_id');

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');

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
        Schema::dropIfExists('products');
    }
}
