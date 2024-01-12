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
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->foreignId('street_id')->nullable()->constrained('streets');
            $table->foreignId('product_category_id')->nullable()->constrained('product_categories');
            $table->foreignId('product_type_id')->nullable()->constrained('product_types');
            $table->foreignId('product_brand_id')->nullable()->constrained('product_brands');
            $table->string('name');
            $table->string('name_en');
            $table->string('images')->nullable();
            $table->string('color')->nullable();
            $table->string('disk_info')->nullable();
            $table->string('duration_of_use')->nullable();
            $table->boolean('guarantee')->default(false);
            $table->string('status')->default('NEW');
            $table->float('price');
            $table->boolean('is_offer')->default(false);
            $table->float('offer_price')->nullable();
            $table->string('description')->nullable();
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
