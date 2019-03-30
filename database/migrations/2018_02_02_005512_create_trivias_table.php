<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriviasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trivias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('finish_date');
            $table->boolean('active');
            $table->integer('total_val')->nullable();
            $table->boolean('points_per_answer')->default(false);
            $table->boolean('allow_percent_points')->default(false);
            $table->integer('points_all_correct')->nullable();
            $table->integer('points_some_correct')->nullable();
            $table->text('study_information')->nullable();
            $table->mediumText('historic')->nullable();
            $table->integer('reward_id')->unsigned()->nullable();
            $table->foreign('reward_id')->references('id')->on('rewards');
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
        Schema::dropIfExists('trivias');
    }
}
