<?php

use App\Models\PaymentOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePaymentOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_options', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable()->default(null);
            $table->string('label_en')->nullable()->default(null);
            $table->string('payment_gateway')->nullable()->default(null);
            $table->string('sub_text')->nullable()->default(null);
            $table->double('value')->nullable()->default(null);
            $table->string('type')->nullable()->default('default');
            $table->string('payment_type')->nullable()->default(null);
            $table->timestamps();
        });

        DB::unprepared(
            file_get_contents(
                database_path('sql/payment_options.sql')
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_options');
    }
}
