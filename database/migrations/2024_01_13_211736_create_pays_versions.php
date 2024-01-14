<?php

use Illuminate\Database\Migrations\Migration;
use Roxayl\MondeGC\Models\Pays;

class CreatePaysVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Pays::initializeVersions();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
