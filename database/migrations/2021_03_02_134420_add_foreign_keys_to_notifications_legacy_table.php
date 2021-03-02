<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToNotificationsLegacyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications_legacy', function (Blueprint $table) {
            $table->foreign('recipient_id', 'notifications_users_ch_use_id_fk')->references('ch_use_id')->on('users')->onUpdate('SET NULL')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications_legacy', function (Blueprint $table) {
            $table->dropForeign('notifications_users_ch_use_id_fk');
        });
    }
}
