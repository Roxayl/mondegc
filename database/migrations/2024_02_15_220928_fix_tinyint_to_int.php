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
        DB::statement('UPDATE `histoire` SET ch_his_date_fait = "0001-01-01 00:00:00"'
            . ' WHERE ch_his_date_fait IS NULL OR ch_his_date_fait = "0000-00-00 00:00:00"');
        DB::statement('UPDATE `histoire` SET ch_his_date_fait2 = NULL'
            . ' WHERE ch_his_date_fait2 = "0000-00-00 00:00:00"');

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
