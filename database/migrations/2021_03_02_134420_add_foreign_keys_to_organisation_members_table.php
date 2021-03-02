<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrganisationMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organisation_members', function (Blueprint $table) {
            $table->foreign('organisation_id', 'organisation_members_organisation_id_fk')->references('id')->on('organisation')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('pays_id', 'organisation_members_pays_ch_pay_id_fk')->references('ch_pay_id')->on('pays')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organisation_members', function (Blueprint $table) {
            $table->dropForeign('organisation_members_organisation_id_fk');
            $table->dropForeign('organisation_members_pays_ch_pay_id_fk');
        });
    }
}
