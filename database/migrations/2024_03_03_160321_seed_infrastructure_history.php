<?php

use Illuminate\Database\Migrations\Migration;
use Roxayl\MondeGC\Models\Infrastructure;

class SeedInfrastructureHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Infrastructure::initializeVersions();
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
