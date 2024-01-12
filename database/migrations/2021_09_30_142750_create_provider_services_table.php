<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('users');
            $table->foreignId('service_id')->nullable()->constrained('services');
            $table->foreignId('service_categories_id')->nullable()->constrained('service_categories');
            $table->foreignId('service_subcategories_id')->nullable()->constrained('service_subcategories');
            $table->string('title')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('gallery')->nullable();
            $table->text('description')->nullable();
            $table->enum('status',['REJECTED','ACCEPTED','PENDING'])->default('PENDING');
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
        Schema::dropIfExists('provider_services');
    }
}
