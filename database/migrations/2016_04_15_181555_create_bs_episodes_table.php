<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bs_episodes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('series_id');
            $table->foreign('series_id')->references('id')->on('bs_series');
            $table->integer('season');
            $table->integer('episode');
            $table->string('german');
            $table->string('english');
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
        Schema::drop('bs_episodes');
    }
}
