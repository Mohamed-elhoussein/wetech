<?php

use App\Models\Color;
use App\Models\MaintenanceRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColorMaintenanceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('color_maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MaintenanceRequest::class);
            $table->foreignIdFor(Color::class);
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
        Schema::dropIfExists('color_maintenance_requests');
    }
}
