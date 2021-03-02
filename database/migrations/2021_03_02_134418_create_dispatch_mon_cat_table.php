<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispatchMonCatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispatch_mon_cat', function (Blueprint $table) {
            $table->integer('ch_disp_id', true);
            $table->string('ch_disp_mon_label', 10);
            $table->integer('ch_disp_cat_id');
            $table->integer('ch_disp_mon_id');
            $table->dateTime('ch_disp_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispatch_mon_cat');
    }
}
