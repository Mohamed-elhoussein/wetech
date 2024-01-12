<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaymentOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payment_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable()->default(null);
            $table->unsignedBigInteger('payment_option_id')->nullable()->default(null);
            $table->double('value')->nullable()->default(null);
            $table->string('type')->nullable()->default(null);
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
        Schema::dropIfExists('order_payment_options');
    }
}
