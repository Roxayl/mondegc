<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommuniquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communiques', function (Blueprint $table) {
            $table->integer('ch_com_ID', true);
            $table->string('ch_com_label', 10);
            $table->integer('ch_com_statut');
            $table->string('ch_com_categorie', 30)->nullable();
            $table->integer('ch_com_element_id');
            $table->integer('ch_com_user_id')->index('communiques_users_ch_use_id_fk');
            $table->dateTime('ch_com_date')->nullable();
            $table->dateTime('ch_com_date_mis_jour')->nullable();
            $table->string('ch_com_titre', 100)->nullable();
            $table->mediumText('ch_com_contenu');
            $table->integer('ch_com_pays_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('communiques');
    }
}
