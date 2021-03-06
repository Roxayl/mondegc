<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToInfrastructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('infrastructures', function (Blueprint $table) {
            $table->foreign('user_creator', 'infrastructures_users_ch_use_id_fk')->references('ch_use_id')->on('users')->onUpdate('SET NULL')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('infrastructures', function (Blueprint $table) {
            $table->dropForeign('infrastructures_users_ch_use_id_fk');
        });
    }
}
