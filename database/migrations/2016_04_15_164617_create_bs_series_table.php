<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bs_series', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name');
            $table->integer('end');
            $table->timestamps();
        });

        \DB::statement('ALTER TABLE bs_series ADD FULLTEXT full(name)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bs_series');
    }
}
