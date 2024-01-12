<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodToMaintenanceRequestOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maintenance_request_orders', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'epay', 'paypal']);
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
            $table->dropColumn('payment_method');
        });
    }
}
