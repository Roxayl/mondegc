<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituts', function (Blueprint $table) {
            $table->integer('ch_ins_ID', true);
            $table->string('ch_ins_label', 10);
            $table->string('ch_ins_lien_forum', 250);
            $table->dateTime('ch_ins_date_enregistrement')->nullable();
            $table->dateTime('ch_ins_mis_jour')->nullable();
            $table->integer('ch_ins_nb_update');
            $table->integer('ch_ins_user_ID');
            $table->decimal('ch_ins_coord_X', 14, 7)->nullable();
            $table->decimal('ch_ins_coord_Y', 14, 7)->nullable();
            $table->string('ch_ins_sigle', 10)->nullable();
            $table->string('ch_ins_nom', 250);
            $table->boolean('ch_ins_statut');
            $table->string('ch_ins_logo', 250)->nullable();
            $table->string('ch_ins_img', 250)->nullable();
            $table->mediumText('ch_ins_desc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instituts');
    }
}
