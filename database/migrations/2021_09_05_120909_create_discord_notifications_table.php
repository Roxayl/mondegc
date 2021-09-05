<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscordNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discord_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('channel');
            $table->string('type');
            $table->string('model_identifier');
            $table->string('uuid');
            $table->text('payload');
            $table->boolean('is_sent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discord_notifications');
    }
}
