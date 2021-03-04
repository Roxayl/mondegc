<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pays', function (Blueprint $table) {
            $table->integer('ch_pay_id', true);
            $table->string('ch_pay_label', 10);
            $table->boolean('ch_pay_publication')->default(1);
            $table->string('ch_pay_continent', 250)->default('MondeGC');
            $table->integer('ch_pay_emplacement')->nullable();
            $table->string('ch_pay_lien_forum', 250)->nullable();
            $table->string('lien_wiki', 250)->nullable();
            $table->string('ch_pay_nom', 35)->nullable()->default('Territoire vierge');
            $table->string('ch_pay_devise', 100)->nullable()->default('Dans cette contrée, tout reste à  construire');
            $table->string('ch_pay_lien_imgheader', 250)->nullable()->default('http://www.generation-city.com/monde/assets/img/imagesdefaut//Imgheader.jpg');
            $table->string('ch_pay_lien_imgdrapeau', 250)->nullable()->default('http://www.generation-city.com/monde/assets/img/imagesdefaut//drapeau.jpg');
            $table->date('ch_pay_date')->nullable();
            $table->dateTime('ch_pay_mis_jour');
            $table->integer('ch_pay_nb_update');
            $table->string('ch_pay_forme_etat', 50)->nullable()->default('pas de forme définie');
            $table->string('ch_pay_capitale', 50)->nullable()->default('pas de capitale choisie');
            $table->string('ch_pay_langue_officielle', 50)->nullable()->default('dialectes');
            $table->string('ch_pay_monnaie', 50)->nullable()->default('troc seulement');
            $table->string('ch_pay_header_presentation', 250)->nullable();
            $table->mediumText('ch_pay_text_presentation')->nullable();
            $table->string('ch_pay_header_geographie', 250)->nullable();
            $table->mediumText('ch_pay_text_geographie')->nullable();
            $table->string('ch_pay_header_politique', 250)->nullable();
            $table->mediumText('ch_pay_text_politique')->nullable();
            $table->string('ch_pay_header_histoire', 250)->nullable();
            $table->mediumText('ch_pay_text_histoire')->nullable();
            $table->string('ch_pay_header_economie', 250)->nullable();
            $table->mediumText('ch_pay_text_economie')->nullable();
            $table->string('ch_pay_header_transport', 250)->nullable();
            $table->mediumText('ch_pay_text_transport')->nullable();
            $table->string('ch_pay_header_sport', 250)->nullable();
            $table->mediumText('ch_pay_text_sport')->nullable();
            $table->string('ch_pay_header_culture', 250)->nullable();
            $table->mediumText('ch_pay_text_culture')->nullable();
            $table->string('ch_pay_header_patrimoine', 250)->nullable();
            $table->mediumText('ch_pay_text_patrimoine')->nullable();
            $table->integer('ch_pay_budget_carte')->nullable()->default(0);
            $table->integer('ch_pay_industrie_carte')->nullable()->default(0);
            $table->integer('ch_pay_commerce_carte')->nullable()->default(0);
            $table->integer('ch_pay_agriculture_carte')->nullable()->default(0);
            $table->integer('ch_pay_tourisme_carte')->nullable()->default(0);
            $table->integer('ch_pay_recherche_carte')->nullable()->default(0);
            $table->integer('ch_pay_environnement_carte')->nullable()->default(0);
            $table->integer('ch_pay_education_carte')->nullable()->default(0);
            $table->integer('ch_pay_population_carte')->nullable()->default(0);
            $table->integer('ch_pay_emploi_carte')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pays');
    }
}
