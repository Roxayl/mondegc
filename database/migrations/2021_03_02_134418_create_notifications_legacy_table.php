<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsLegacyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications_legacy', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('recipient_id')->nullable()->index('notifications_recipient_id_index');
            $table->string('type_notif', 25);
            $table->integer('element')->nullable();
            $table->boolean('unread')->default(1);
            $table->dateTime('created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications_legacy');
    }
}
