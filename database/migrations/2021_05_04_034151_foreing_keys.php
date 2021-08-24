<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForeingKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auditory', function (Blueprint $table) {
            $table->foreign('fkiduser')->references('pkiduser')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('fkidcountry')->references('pkidcountry')->on('countries')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('substrech', function (Blueprint $table) {
            $table->foreign('fkidstrech')->references('pkidstrech')->on('strech')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('strech', function (Blueprint $table) {
            $table->foreign('fkidcountry')->references('pkidcountry')->on('countries')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('areas', function (Blueprint $table) {
            $table->foreign('fkidsection')->references('pkidsection')->on('sections')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('affecttion_points', function (Blueprint $table) {
            $table->foreign('fkidvariable')->references('pkidvariable')->on('variables')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('fkidarea')->references('pkidarea')->on('areas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('fkidcoordinate')->references('pkidcoordinate')->on('coordinates')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('variables', function (Blueprint $table) {
            $table->foreign('fkidfactor')->references('pkidfactor')->on('factors')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('monitorings', function (Blueprint $table) {
            $table->foreign('fkidaffecttion_point')->references('pkidaffecttion_point')->on('affecttion_points')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('fkiduser')->references('pkiduser')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('tests', function (Blueprint $table) {
            $table->foreign('fkidaffecttion_point')->references('pkidaffecttion_point')->on('affecttion_points')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('answers', function (Blueprint $table) {
            $table->foreign('fkidaffecttion_point')->references('pkidaffecttion_point')->on('affecttion_points')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('fkidmonitoring')->references('pkidmonitoring')->on('monitorings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('fkiduser')->references('pkiduser')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
