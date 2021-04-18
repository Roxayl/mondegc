<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCategoriesMonumentBgImageUrl extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monument_categories', function($table) {
            $table->string('bg_image_url', 191)->nullable();
        });
    }

    /*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monument_categories', function($table) {
            $table->dropColumn('bg_image_url');
        });
    }
}