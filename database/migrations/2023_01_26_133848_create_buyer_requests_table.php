<?php

use App\Models\Cities;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyerRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_requests', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable()->default(null);
            $table->string('date')->nullable()->default(null);
            $table->string('image')->nullable()->default(null);
            $table->longText('description')->nullable()->default(null);

            $table->foreignIdFor(Service::class)->nullable()->default(null)->constrained()->nullOnDelete();
            $table->foreignIdFor(ServiceType::class)->nullable()->default(null)->constrained()->nullOnDelete();
            $table->foreignIdFor(Cities::class, 'city_id')->nullable()->default(null)->constrained('cities')->nullOnDelete();
            $table->foreignIdFor(User::class, 'provider_id')->nullable()->default(null)->constrained('users')->nullOnDelete();
            $table->foreignIdFor(User::class, 'user_id')->nullable()->default(null)->constrained('users')->nullOnDelete();

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
        Schema::dropIfExists('buyer_requests');
    }
}
