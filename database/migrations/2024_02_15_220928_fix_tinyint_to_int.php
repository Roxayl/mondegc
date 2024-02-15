<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->tinyInteger('ch_his_paysID')->change();
        });

        Schema::table('patrimoine', function (Blueprint $table) {
            $table->tinyInteger('ch_pat_paysID')->change();
        });
    }
};
