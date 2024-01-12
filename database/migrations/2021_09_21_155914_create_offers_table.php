<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('users');
            $table->foreignId('provider_service_id')->constrained('provider_services');
            $table->enum('target', ['all', 'one']);
            $table->text('description')->nullable();
            $table->float('price')->nullable();
            $table->enum('status', ['ACCEPTED', 'REJECTED', 'CANCELED'])->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
