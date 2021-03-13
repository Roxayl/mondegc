<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCommuniquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('communiques', function (Blueprint $table) {
            $table->foreign('ch_com_user_id', 'communiques_users_ch_use_id_fk')->references('ch_use_id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('communiques', function (Blueprint $table) {
            $table->dropForeign('communiques_users_ch_use_id_fk');
        });
    }
}
