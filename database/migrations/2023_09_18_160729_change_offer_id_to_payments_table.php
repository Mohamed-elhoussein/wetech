<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOfferIdToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ALTER TABLE `payments` CHANGE `offer_id` `offer_id` BIGINT UNSIGNED NULL DEFAULT NULL;
        Schema::table('payments', function (Blueprint $table) {
            // $table->foreignId('offer_id')->constrained('offers')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // $table->foreignId('offer_id')->constrained('offers')->change();
        });
    }
}
