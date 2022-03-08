<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMediaParametersAndResourceablesFieldToChapterEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chapter_entries', function (Blueprint $table) {
            $table->text('media_parameters')->nullable()->after('media_type');
            $table->unsignedBigInteger('roleplayable_id')->after('chapter_id');
            $table->string('roleplayable_type', 191)->after('chapter_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chapter_entries', function (Blueprint $table) {
            $table->dropColumn('media_parameters');
            $table->dropColumn('roleplayable_id');
            $table->dropColumn('roleplayable_type');
        });
    }
}
