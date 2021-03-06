<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispatchFaitHisCatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispatch_fait_his_cat', function (Blueprint $table) {
            $table->integer('ch_disp_FH_id', true);
            $table->string('ch_disp_FH_label', 10);
            $table->integer('ch_disp_fait_hist_cat_id');
            $table->integer('ch_disp_fait_hist_id');
            $table->dateTime('ch_disp_FH_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispatch_fait_his_cat');
    }
}
