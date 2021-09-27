<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChapterResourceableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapter_resourceable', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chapter_id');
            $table->string('resourceable_type');
            $table->integer('resourceable_id');

            foreach(config('enums.resources') as $resource) {
                $table->float($resource);
            }

            $table->timestamps();

            $table->foreign('chapter_id')->references('id')->on('chapters')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->index('resourceable_type');
            $table->index('resourceable_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapter_resourceable');
    }
}
