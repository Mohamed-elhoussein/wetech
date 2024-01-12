<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceRequestCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_request_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(null);
            $table->double('value')->nullable()->default(null);
            $table->enum('type', ['discount', 'percentage'])->nullable()->default(null);
            $table->timestamp('expired_at')->nullable()->default(null);
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
        Schema::dropIfExists('maintenance_request_coupons');
    }
}
