<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotationTemperanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notation_temperance', function (Blueprint $table) {
            $table->integer('ch_not_temp_id', true);
            $table->string('ch_not_temp_label', 10);
            $table->dateTime('ch_not_temp_date');
            $table->string('ch_not_temp_juge', 250);
            $table->integer('ch_not_temp_temperance_id');
            $table->integer('ch_not_temp_q1')->nullable();
            $table->string('ch_not_temp_q1_com', 250)->nullable();
            $table->integer('ch_not_temp_q2')->nullable();
            $table->string('ch_not_temp_q2_com', 250)->nullable();
            $table->integer('ch_not_temp_q3')->nullable();
            $table->string('ch_not_temp_q3_com', 250)->nullable();
            $table->integer('ch_not_temp_q4')->nullable();
            $table->string('ch_not_temp_q4_com', 250)->nullable();
            $table->integer('ch_not_temp_q5')->nullable();
            $table->string('ch_not_temp_q5_com', 250)->nullable();
            $table->integer('ch_not_temp_q6')->nullable();
            $table->string('ch_not_temp_q6_com', 250)->nullable();
            $table->integer('ch_not_temp_q7')->nullable();
            $table->string('ch_not_temp_q7_com', 250)->nullable();
            $table->integer('ch_not_temp_q8')->nullable();
            $table->string('ch_not_temp_q8_com', 250)->nullable();
            $table->integer('ch_not_temp_q9')->nullable();
            $table->string('ch_not_temp_q9_com', 250)->nullable();
            $table->integer('ch_not_temp_q10')->nullable();
            $table->string('ch_not_temp_q10_com', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notation_temperance');
    }
}
