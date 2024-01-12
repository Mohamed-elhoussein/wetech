<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('friend_number')->nullable();
            $table->string('number_phone');
            $table->string('role');
            $table->text('identity')->nullable()->default(NULL);
            $table->float('balance')->nullable()->default(NULL);
            $table->string('avatar')->nullable()->default('/images/avatars/default.png');
            $table->foreignId('city_id')->nullable()->constrained('cities')->default(NULL);
            $table->foreignId('country_id')->nullable()->constrained('countries')->default(NULL);
            $table->foreignId('street_id')->nullable()->constrained('streets')->default(NULL);
            $table->text('about')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('is_blocked')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('verified')->default(false);
            $table->string('password')->nullable();
            $table->string('device_token')->nullable();
            $table->json('permissions')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
