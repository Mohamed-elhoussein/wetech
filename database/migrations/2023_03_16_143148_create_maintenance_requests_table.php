<?php

use App\Models\Brand;
use App\Models\Cities;
use App\Models\Color;
use App\Models\Countries;
use App\Models\Issues;
use App\Models\Models;
use App\Models\Service;
use App\Models\Street;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Service::class)->nullable()->default(null)->constrained()->nullOnDelete();
            $table->foreignIdFor(Brand::class)->nullable()->default(null)->constrained()->nullOnDelete();
            $table->foreignIdFor(Models::class)->nullable()->default(null)->constrained()->nullOnDelete();
            $table->foreignIdFor(Color::class)->nullable()->default(null)->constrained()->nullOnDelete();
            $table->foreignIdFor(Issues::class)->nullable()->default(null)->constrained()->nullOnDelete();
            $table->foreignIdFor(Countries::class, 'country_id')->nullable()->default(null)->constrained()->nullOnDelete();
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
        Schema::dropIfExists('maintenance_requests');
    }
}
