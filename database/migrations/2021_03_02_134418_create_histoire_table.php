<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoireTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histoire', function (Blueprint $table) {
            $table->integer('ch_his_id', true);
            $table->string('ch_his_label', 10);
            $table->tinyInteger('ch_his_paysID')->index('fk_histoire_pays1_idx');
            $table->boolean('ch_his_statut')->default(2);
            $table->integer('ch_his_personnage')->default(1);
            $table->dateTime('ch_his_date')->nullable();
            $table->dateTime('ch_his_mis_jour')->nullable();
            $table->integer('ch_his_nb_update')->nullable();
            $table->dateTime('ch_his_date_fait');
            $table->dateTime('ch_his_date_fait2')->nullable();
            $table->string('ch_his_profession', 250)->nullable();
            $table->string('ch_his_nom', 250)->nullable();
            $table->string('ch_his_lien_img1', 250)->nullable();
            $table->string('ch_his_legende_img1', 250)->nullable();
            $table->mediumText('ch_his_description')->nullable();
            $table->mediumText('ch_his_contenu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histoire');
    }
}
