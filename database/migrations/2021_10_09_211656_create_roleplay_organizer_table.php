<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateRoleplayOrganizerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('roleplay_users');

        Schema::create('roleplay_organizers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('roleplay_id');
            $table->string('organizer_type');
            $table->integer('organizer_id');
            $table->timestamps();

            $table->foreign('roleplay_id')->references('id')->on('roleplay')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roleplay_organizers');

        Artisan::call(
            'php artisan migrate --path=/database/migrations/2021_09_27_154822_create_roleplay_users_table.php'
        );
    }
}
