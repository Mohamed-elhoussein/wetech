<?php

use App\Models\MaintenanceRequest;
use App\Models\MaintenanceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMaintenanceRequestOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('maintenance_request_orders', 'maintenance_request_id')) {
            Schema::table('maintenance_request_orders', function (Blueprint $table) {
                $table->dropConstrainedForeignId('maintenance_request_id');
            });
        }

        Schema::table('maintenance_request_orders', function (Blueprint $table) {
            // $table->foreignIdFor(MaintenanceType::class)->nullable()->default(null)->constrained()->nullOnDelete();
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
            $table->dropConstrainedForeignId('maintenance_type_id');
            $table->dropColumn('maintenance_type_id');
        });
    }
}
