<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembresGroupesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membres_groupes', function (Blueprint $table) {
            $table->integer('ch_mem_group_ID', true);
            $table->string('ch_mem_group_label', 10);
            $table->boolean('ch_mem_group_statut')->nullable();
            $table->dateTime('ch_mem_group_date');
            $table->dateTime('ch_mem_group_mis_jour');
            $table->integer('ch_mem_group_nb_update');
            $table->string('ch_mem_group_nom', 50)->nullable();
            $table->mediumText('ch_mem_group_desc')->nullable();
            $table->string('ch_mem_group_icon', 250)->nullable();
            $table->string('ch_mem_group_couleur', 7)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('membres_groupes');
    }
}
