<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('payment_id')->nullable()->default(NULL)->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('provider_id')->constrained('users');
            $table->foreignId('offer_id')->constrained('offers');
            $table->string('method');
            $table->boolean('paid')->default(false);
            $table->float('amount')->nullable()->default(null);
            $table->string('currency')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
