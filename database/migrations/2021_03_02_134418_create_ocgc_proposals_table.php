<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOcgcProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ocgc_proposals', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('ID_pays')->nullable();
            $table->mediumText('question')->nullable();
            $table->enum('type', ['IRL', 'RP'])->nullable()->default('RP');
            $table->enum('type_reponse', ['dual', 'multiple'])->nullable()->default('dual')->comment('Pour/Contre = \'dual\' ; vote multiple = \'multiple\'');
            $table->mediumText('reponse_1')->nullable();
            $table->mediumText('reponse_2')->nullable();
            $table->mediumText('reponse_3')->nullable();
            $table->mediumText('reponse_4')->nullable();
            $table->mediumText('reponse_5')->nullable();
            $table->float('threshold', 3)->nullable()->default(0.50);
            $table->tinyInteger('is_valid')->nullable()->default(1)->comment('0 = rejeté ; 1 = en attente ; 2 = accepté');
            $table->mediumText('motive')->nullable()->comment('Expliquer pourquoi la proposition est validée ou pas par l\'OCGC.');
            $table->dateTime('debate_start')->nullable();
            $table->dateTime('debate_end')->nullable();
            $table->mediumText('link_debate')->nullable();
            $table->mediumText('link_debate_name')->nullable();
            $table->mediumText('link_wiki')->nullable();
            $table->mediumText('link_wiki_name')->nullable();
            $table->integer('res_year')->nullable();
            $table->integer('res_id')->nullable();
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->unique(['res_year', 'res_id'], 'proposal_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ocgc_proposals');
    }
}
