<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOcgcVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ocgc_votes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('ID_proposal')->nullable();
            $table->integer('ID_pays')->nullable();
            $table->integer('reponse_choisie')->nullable()->comment('ID de la réponse. NULL = abstention ; 0 = vote blanc ; 1 à 5 = réponses');
            $table->dateTime('created')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ocgc_votes');
    }
}
