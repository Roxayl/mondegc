<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('roleplay_id');
            $table->integer('order');
            $table->integer('user_id');
            $table->string('name');
            $table->dateTime('starting_date');
            $table->dateTime('ending_date')->nullable();
            $table->timestamps();

            $table->foreign('roleplay_id')->references('id')->on('roleplay')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('user_id')->references('ch_use_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapters');
    }
}
