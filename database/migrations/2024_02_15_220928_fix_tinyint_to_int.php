<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixTinyintToInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('histoire', function (Blueprint $table) {
            $table->integer('ch_his_paysID')->change();
        });

        Schema::table('patrimoine', function (Blueprint $table) {
            $table->integer('ch_pat_paysID')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('histoire', function (Blueprint $table) {
            $table->smallInteger('ch_his_paysID')->change();
        });

        Schema::table('patrimoine', function (Blueprint $table) {
            $table->smallInteger('ch_pat_paysID')->change();
        });
    }
}
