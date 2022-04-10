<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDescriptionColumnOnChapterResourceableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chapter_resourceable', function (Blueprint $table) {
            $table->string('description', 191)->nullable()->after('resourceable_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chapter_resourceable', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}
