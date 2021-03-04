<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersProvisoireTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_provisoire', function (Blueprint $table) {
            $table->integer('ch_use_prov_ID', true);
            $table->string('ch_use_prov_login', 45)->nullable();
            $table->string('ch_use_prov_clef', 10)->nullable();
            $table->string('ch_use_prov_mail', 250)->nullable();
            $table->tinyInteger('ch_use_prov_paysID')->nullable();
            $table->boolean('ch_use_prov_statut')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_provisoire');
    }
}
