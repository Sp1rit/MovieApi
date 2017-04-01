<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKinoxMirrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kinox_mirrors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('media_id');
            $table->integer('season');
            $table->integer('episode');
            $table->integer('hoster_id');
            $table->string('hoster');
            $table->integer('count');
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
        Schema::drop('kinox_mirrors');
    }
}
