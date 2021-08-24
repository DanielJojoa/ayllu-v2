<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Test extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id('pkidtest');
            $table->string('name_test1')->nullable();
            $table->string('name_test2')->nullable();
            $table->string('name_test3')->nullable();
            $table->date('date_monitoring')->nullable();
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
        Schema::dropIfExists('tests');
    }
}
