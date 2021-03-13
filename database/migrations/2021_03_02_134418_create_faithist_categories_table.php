<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaithistCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faithist_categories', function (Blueprint $table) {
            $table->integer('ch_fai_cat_ID', true);
            $table->string('ch_fai_cat_label', 10);
            $table->integer('ch_fai_cat_statut')->nullable();
            $table->dateTime('ch_fai_cat_date');
            $table->dateTime('ch_fai_cat_mis_jour');
            $table->integer('ch_fai_cat_nb_update');
            $table->string('ch_fai_cat_nom', 50)->nullable();
            $table->mediumText('ch_fai_cat_desc')->nullable();
            $table->string('ch_fai_cat_icon', 250)->nullable();
            $table->string('ch_fai_cat_couleur', 7)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faithist_categories');
    }
}
