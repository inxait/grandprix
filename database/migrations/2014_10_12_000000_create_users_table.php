<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('agile_id')->nullable();
            $table->string('first_name', 90)->nullable();
            $table->string('last_name', 90)->nullable();
            $table->string('surname', 90)->nullable();
            $table->string('identification', 30)->unique();
            $table->string('email', 250);
            $table->string('password');
            $table->string('cellphone', 20)->nullable();
            $table->string('address')->nullable();
            $table->string('zone', 80)->nullable();
            $table->integer('city_id')->unsigned()->nullable();
            $table->foreign('city_id')->references('id')->on('cities');
            $table->string('gender', 10)->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->boolean('updated_data')->default(false);
            $table->boolean('approved_sent')->default(false);
            $table->dateTime('data_update_date')->nullable();
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
