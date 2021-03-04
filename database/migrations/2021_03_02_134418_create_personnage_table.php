<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonnageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personnage', function (Blueprint $table) {
            $table->integer('id', true);
            $table->mediumText('entity')->nullable();
            $table->integer('entity_id')->nullable();
            $table->string('nom_personnage', 191)->nullable();
            $table->string('predicat', 191)->nullable();
            $table->string('prenom_personnage', 191)->nullable();
            $table->mediumText('biographie')->nullable();
            $table->string('titre_personnage', 191)->nullable();
            $table->string('lien_img', 191)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personnage');
    }
}
