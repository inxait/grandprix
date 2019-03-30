<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFulfillmentResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fulfillment_results', function (Blueprint $table) {
            $table->increments('id');
            $table->double('current_percent');
            $table->decimal('current_value', 10, 2);
            $table->string('reference')->nullable();
            $table->integer('fulfillment_id')->unsigned();
            $table->foreign('fulfillment_id')->references('id')->on('fulfillments');
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
        Schema::dropIfExists('fulfillment_results');
    }
}
