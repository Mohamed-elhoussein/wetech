<?php

use App\Models\BuyerRequest;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCanceledBuyerRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canceled_buyer_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(BuyerRequest::class)->nullable()->default(null)->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class)->nullable()->default(null)->constrained()->nullOnDelete();
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
        Schema::dropIfExists('canceled_buyer_requests');
    }
}
