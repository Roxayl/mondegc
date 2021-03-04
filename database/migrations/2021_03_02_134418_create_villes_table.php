<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVillesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('villes', function (Blueprint $table) {
            $table->integer('ch_vil_ID', true);
            $table->integer('ch_vil_paysID')->index('fk_villes_pays_idx');
            $table->integer('ch_vil_user');
            $table->string('ch_vil_label', 10);
            $table->dateTime('ch_vil_date_enregistrement')->nullable();
            $table->dateTime('ch_vil_mis_jour')->nullable();
            $table->integer('ch_vil_nb_update')->nullable();
            $table->decimal('ch_vil_coord_X', 14, 7)->nullable();
            $table->decimal('ch_vil_coord_Y', 14, 7)->nullable();
            $table->string('ch_vil_type_jeu', 10)->nullable();
            $table->string('ch_vil_nom', 50)->nullable()->default('Ma ville');
            $table->string('ch_vil_armoiries', 250)->nullable();
            $table->boolean('ch_vil_capitale')->default(2);
            $table->integer('ch_vil_population')->nullable()->default(0);
            $table->string('ch_vil_specialite', 50)->nullable()->default('petit artisanat local');
            $table->string('ch_vil_lien_img1', 250)->nullable();
            $table->string('ch_vil_lien_img2', 250)->nullable();
            $table->string('ch_vil_lien_img3', 250)->nullable();
            $table->string('ch_vil_lien_img4', 250)->nullable();
            $table->string('ch_vil_lien_img5', 250)->nullable();
            $table->string('ch_vil_legende_img1', 50)->nullable();
            $table->string('ch_vil_legende_img2', 50)->nullable();
            $table->string('ch_vil_legende_img3', 50)->nullable();
            $table->string('ch_vil_legende_img4', 50)->nullable();
            $table->string('ch_vil_legende_img5', 50)->nullable();
            $table->mediumText('ch_vil_header')->nullable();
            $table->mediumText('ch_vil_contenu')->nullable();
            $table->mediumText('ch_vil_transports')->nullable();
            $table->mediumText('ch_vil_administration')->nullable();
            $table->mediumText('ch_vil_culture')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('villes');
    }
}
