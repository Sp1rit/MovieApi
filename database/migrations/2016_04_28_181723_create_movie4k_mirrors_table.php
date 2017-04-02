<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovie4kMirrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie4k_mirrors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('media_id');
            $table->integer('season');
            $table->integer('episode');
            $table->string('hoster_id');
            $table->string('hoster');
            $table->integer('quality');
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
        Schema::drop('movie4k_mirrors');
    }
}
