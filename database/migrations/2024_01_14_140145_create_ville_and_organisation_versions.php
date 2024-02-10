<?php

use Illuminate\Database\Migrations\Migration;
use Roxayl\MondeGC\Models\Organisation;
use Roxayl\MondeGC\Models\Ville;

class CreateVilleAndOrganisationVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Ville::initializeVersions();
        Organisation::initializeVersions();
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
