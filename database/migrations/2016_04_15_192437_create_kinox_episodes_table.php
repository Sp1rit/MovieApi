<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKinoxEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kinox_episodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('series_id');
            $table->foreign('series_id')->references('id')->on('kinox_series');
            $table->integer('season');
            $table->integer('episode');
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
        Schema::drop('kinox_episodes');
    }
}
