<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('unit')->nullable()->default(Null);
            $table->string('code')->nullable();
            $table->string('country_code')->nullable();
            $table->enum('status',['ACTIVE','UNACTIVE']);
            $table->string('message')->nullable();
            $table->enum('pin',['PINED','UNPINED'])->default('UNPINED');
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
        Schema::dropIfExists('countries');
    }
}
