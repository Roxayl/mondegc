<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonumentCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monument_categories', function (Blueprint $table) {
            $table->integer('ch_mon_cat_ID', true);
            $table->string('ch_mon_cat_label', 10)->nullable();
            $table->integer('ch_mon_cat_statut');
            $table->dateTime('ch_mon_cat_date');
            $table->dateTime('ch_mon_cat_mis_jour');
            $table->integer('ch_mon_cat_nb_update');
            $table->string('ch_mon_cat_nom', 50)->nullable();
            $table->mediumText('ch_mon_cat_desc')->nullable();
            $table->string('ch_mon_cat_icon', 250)->nullable();
            $table->string('ch_mon_cat_couleur', 7)->nullable();
            $table->integer('ch_mon_cat_industrie')->nullable();
            $table->integer('ch_mon_cat_commerce')->nullable();
            $table->integer('ch_mon_cat_agriculture')->nullable();
            $table->integer('ch_mon_cat_tourisme')->nullable();
            $table->integer('ch_mon_cat_recherche')->nullable();
            $table->integer('ch_mon_cat_environnement')->nullable();
            $table->integer('ch_mon_cat_education')->nullable();
            $table->integer('ch_mon_cat_budget')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monument_categories');
    }
}
