<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfrastructuresOfficiellesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infrastructures_officielles', function (Blueprint $table) {
            $table->integer('ch_inf_off_id', true);
            $table->string('ch_inf_off_label', 10);
            $table->dateTime('ch_inf_off_date');
            $table->string('ch_inf_off_nom', 250);
            $table->mediumText('ch_inf_off_desc')->nullable();
            $table->string('ch_inf_off_icone', 250)->nullable();
            $table->integer('ch_inf_off_budget')->nullable();
            $table->integer('ch_inf_off_Industrie')->nullable();
            $table->integer('ch_inf_off_Commerce')->nullable();
            $table->integer('ch_inf_off_Agriculture')->nullable();
            $table->integer('ch_inf_off_Tourisme')->nullable();
            $table->integer('ch_inf_off_Recherche')->nullable();
            $table->integer('ch_inf_off_Environnement')->nullable();
            $table->integer('ch_inf_off_Education')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infrastructures_officielles');
    }
}
