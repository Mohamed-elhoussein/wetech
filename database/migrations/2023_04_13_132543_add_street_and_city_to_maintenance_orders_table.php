<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStreetAndCityToMaintenanceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maintenance_request_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('city_id')->nullable()->default(null);
            $table->unsignedBigInteger('street_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maintenance_orders', function (Blueprint $table) {
            $table->dropColumn([
                'city_id',
                'street_id',
            ]);
        });
    }
}
