<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Answer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id('pkidanswer');
            $table->timestamp('answer_date')->nullable();
            $table->timestamp('answer_time')->nullable();
            $table->string('budget')->nullable();
            $table->double('resources')->nullable();
            $table->string('knownledge_answer')->nullable();
            $table->unsignedBigInteger('fkidaffecttion_point');
            $table->unsignedBigInteger('fkidmonitoring');
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
        Schema::dropIfExists('answers');
    }
}
