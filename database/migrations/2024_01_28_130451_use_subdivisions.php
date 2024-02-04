<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UseSubdivisions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pays', function (Blueprint $table) {
            $table->boolean('use_subdivisions')->after('ch_pay_label')
                ->default(false);
        });

        Schema::table('villes', function (Blueprint $table) {
            $table->unsignedBigInteger('subdivision_id')->after('ch_vil_label')
                ->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('villes', 'subdivision_id');
        Schema::dropColumns('pays', 'use_subdivisions');
    }
}
