<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('provider_id')->constrained('users');
            $table->foreignId('offer_id')->constrained('offers');
            $table->foreignId('provider_service_id')->constrained('provider_services');
            $table->enum('status',['PENDING','COMPLETED','CANCELED','WAITING'])->default('PENDING');
            $table->float('price');
            $table->float('commission')->default(20);
            $table->foreignId('canceled_by')->nullable()->constrained('users')->default(Null);
            $table->text('canceled_reason')->nullable()->default(Null);
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
        Schema::dropIfExists('orders');
    }
}
