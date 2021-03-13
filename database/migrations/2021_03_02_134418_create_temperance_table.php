<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemperanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temperance', function (Blueprint $table) {
            $table->integer('ch_temp_id', true);
            $table->string('ch_temp_label', 10);
            $table->dateTime('ch_temp_date');
            $table->dateTime('ch_temp_mis_jour');
            $table->string('ch_temp_element', 10);
            $table->integer('ch_temp_element_id');
            $table->integer('ch_temp_statut');
            $table->integer('ch_temp_note')->nullable();
            $table->string('ch_temp_tendance', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temperance');
    }
}
