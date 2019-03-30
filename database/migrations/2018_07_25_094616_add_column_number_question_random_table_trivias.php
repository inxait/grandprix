<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnNumberQuestionRandomTableTrivias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('trivias', function (Blueprint $table) {
          $table->integer('number_questions_random')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('trivias', function (Blueprint $table) {
          $table->dropColumn('number_questions_random');
      });
    }
}
