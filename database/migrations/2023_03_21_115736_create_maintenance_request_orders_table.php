<?php

use App\Models\MaintenanceRequest;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceRequestOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_request_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('maintenance_type_id')->nullable()->default(null);
            $table->unsignedBigInteger('provider_id')->nullable()->default(null);
            $table->longText('note')->nullable()->default(null);
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
        Schema::dropIfExists('maintenance_request_orders');
    }
}
