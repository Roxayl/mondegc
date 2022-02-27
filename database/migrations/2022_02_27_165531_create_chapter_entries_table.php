<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChapterEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapter_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->references('id')->on('chapters')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->text('content');
            $table->string('media_type', 50)->nullable();
            $table->text('media_data')->nullable();
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
        Schema::dropIfExists('chapter_entries');
    }
}
