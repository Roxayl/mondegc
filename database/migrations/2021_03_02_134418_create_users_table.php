<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('ch_use_id', true);
            $table->boolean('ch_use_acces')->nullable()->default(1);
            $table->dateTime('ch_use_date')->nullable();
            $table->dateTime('ch_use_last_log')->nullable();
            $table->dateTime('last_activity')->nullable();
            $table->string('ch_use_login', 45)->nullable();
            $table->string('ch_use_password', 32)->nullable();
            $table->string('ch_use_mail', 250)->nullable();
            $table->integer('ch_use_paysID')->nullable();
            $table->boolean('ch_use_statut')->default(1);
            $table->string('ch_use_lien_imgpersonnage', 250)->nullable();
            $table->string('ch_use_predicat_dirigeant', 100)->nullable();
            $table->string('ch_use_titre_dirigeant', 250)->nullable();
            $table->string('ch_use_nom_dirigeant', 50)->nullable();
            $table->string('ch_use_prenom_dirigeant', 50)->nullable();
            $table->mediumText('ch_use_biographie_dirigeant')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
