<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfrastructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infrastructures', function (Blueprint $table) {
            $table->integer('ch_inf_id', true);
            $table->string('ch_inf_label', 20);
            $table->integer('ch_inf_off_id')->index('ch_inf_off_id__index');
            $table->integer('ch_inf_villeid')->index('ch_inf_villeid__index');
            $table->dateTime('ch_inf_date');
            $table->integer('ch_inf_statut');
            $table->string('nom_infra', 191)->default('');
            $table->string('ch_inf_lien_image', 250)->nullable();
            $table->string('ch_inf_lien_image2', 250)->nullable();
            $table->string('ch_inf_lien_image3', 250)->nullable();
            $table->string('ch_inf_lien_image4', 250)->nullable();
            $table->string('ch_inf_lien_image5', 250)->nullable();
            $table->string('ch_inf_lien_forum', 250)->nullable();
            $table->string('lien_wiki', 250)->nullable();
            $table->integer('user_creator')->nullable()->index('infrastructures_users_ch_use_id_fk');
            $table->mediumText('ch_inf_commentaire')->nullable();
            $table->integer('ch_inf_juge')->nullable();
            $table->mediumText('ch_inf_commentaire_juge')->nullable();
            $table->dateTime('judged_at')->nullable();
            $table->integer('infrastructurable_id')->nullable();
            $table->string('infrastructurable_type', 191)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infrastructures');
    }
}
