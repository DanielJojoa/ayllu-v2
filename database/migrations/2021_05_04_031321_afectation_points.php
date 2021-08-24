<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AfectationPoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affecttion_points', function (Blueprint $table) {
            $table->id('pkidaffecttion_point');
            $table->unsignedBigInteger('fkidvariable');
            $table->unsignedBigInteger('fkidarea');
            $table->unsignedBigInteger('fkidcoordinate');
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
        Schema::dropIfExists('afectarion_pointss');
    }
}
