<?php

use App\Models\ProductTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductTypeToBuyerRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyer_requests', function (Blueprint $table) {
            $table->foreignIdFor(ProductTypes::class, 'product_type_id')->nullable()->default(null)->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyer_requests', function (Blueprint $table) {
            $table->dropColumn('product_type_id');
        });
    }
}
