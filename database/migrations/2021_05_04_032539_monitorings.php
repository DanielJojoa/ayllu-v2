<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Monitorings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitorings', function (Blueprint $table) {
            $table->id('pkidmonitoring');
            $table->string('repercussions_monitoring');
            $table->string('origin_monitoring');
            $table->string('concept');
            $table->unsignedBigInteger('fkiduser');
            $table->unsignedBigInteger('fkidaffecttion_point');
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
        Schema::dropIfExists('monitorings');
    }
}
