<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatrimoineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patrimoine', function (Blueprint $table) {
            $table->integer('ch_pat_id', true);
            $table->string('ch_pat_label', 10);
            $table->integer('ch_pat_statut');
            $table->tinyInteger('ch_pat_paysID');
            $table->integer('ch_pat_villeID')->index('fk_patrimoine_villes1_idx');
            $table->dateTime('ch_pat_date')->nullable();
            $table->dateTime('ch_pat_mis_jour')->nullable();
            $table->integer('ch_pat_nb_update')->nullable();
            $table->decimal('ch_pat_coord_X', 14, 7)->nullable();
            $table->decimal('ch_pat_coord_Y', 14, 7)->nullable();
            $table->string('ch_pat_nom', 250)->nullable();
            $table->string('ch_pat_lien_img1', 250)->nullable();
            $table->string('ch_pat_lien_img2', 250)->nullable();
            $table->string('ch_pat_lien_img3', 250)->nullable();
            $table->string('ch_pat_lien_img4', 250)->nullable();
            $table->string('ch_pat_lien_img5', 250)->nullable();
            $table->string('ch_pat_legende_img1', 50)->nullable();
            $table->string('ch_pat_legende_img2', 50)->nullable();
            $table->string('ch_pat_legende_img3', 50)->nullable();
            $table->string('ch_pat_legende_img4', 50)->nullable();
            $table->string('ch_pat_legende_img5', 50)->nullable();
            $table->mediumText('ch_pat_description')->nullable();
            $table->mediumText('ch_pat_commentaire')->nullable();
            $table->integer('ch_pat_juge')->nullable();
            $table->mediumText('ch_pat_commentaire_juge')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patrimoine');
    }
}
