<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentOptionToMaintenanceRequestOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maintenance_request_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_option_id')->after('payment_method')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maintenance_request_orders', function (Blueprint $table) {
            $table->dropColumn('payment_option_id');
        });
    }
}
