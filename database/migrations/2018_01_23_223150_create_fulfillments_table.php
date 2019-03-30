<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFulfillmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fulfillments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100);
            $table->decimal('goal', 10, 2)->nullable();
            $table->decimal('min_value', 10, 2)->nullable();
            $table->string('reward')->nullable();
            $table->decimal('overcompliance_value', 10, 2)->nullable();
            $table->string('overcompliance_reward')->nullable();
            $table->date('overcompliance_updated_at')->nullable();
            $table->string('period', 45);
            $table->integer('month')->nullable();
            $table->integer('year');
            $table->boolean('active')->default(true);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('measure_id')->unsigned();
            $table->foreign('measure_id')->references('id')->on('measures');
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
        Schema::dropIfExists('fulfillments');
    }
}
