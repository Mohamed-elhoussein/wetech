<?php

use App\Models\MaintenanceRequestType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaintenanceRequestTypeIdToMaintenanceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('maintenance_request_type_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropColumn('maintenance_request_type_id');
        });
    }
}
