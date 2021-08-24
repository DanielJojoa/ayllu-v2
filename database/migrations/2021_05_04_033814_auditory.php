<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Auditory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auditory', function (Blueprint $table) {
            $table->id('pkidauditory');
            $table->string('action')->nullable();
            $table->string('target_model')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('fkiduser');
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
        Schema::dropIfExists('auditory');
    }
}
