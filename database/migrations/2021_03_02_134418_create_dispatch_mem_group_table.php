<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispatchMemGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispatch_mem_group', function (Blueprint $table) {
            $table->integer('ch_disp_MG_id', true);
            $table->string('ch_disp_MG_label', 10);
            $table->integer('ch_disp_group_id');
            $table->integer('ch_disp_mem_id');
            $table->tinyInteger('ch_disp_mem_statut');
            $table->dateTime('ch_disp_MG_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispatch_mem_group');
    }
}
