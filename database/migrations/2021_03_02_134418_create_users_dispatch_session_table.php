<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersDispatchSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_dispatch_session', function (Blueprint $table) {
            $table->integer('ch_users_session_dispatch_ID', true);
            $table->string('ch_users_session_dispatch_Key', 20);
            $table->integer('ch_users_session_dispatch_sessionID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_dispatch_session');
    }
}
