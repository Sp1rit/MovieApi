<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsMirrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bs_mirrors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('media_id');
            $table->integer('season');
            $table->integer('episode');
            $table->string('hoster');
            $table->integer('mirror_id');
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
        Schema::drop('bs_mirrors');
    }
}
