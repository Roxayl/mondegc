<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfrastructuresOfficiellesGroupesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infrastructures_officielles_groupes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('ID_groupes')->nullable();
            $table->integer('ID_infra_officielle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infrastructures_officielles_groupes');
    }
}
