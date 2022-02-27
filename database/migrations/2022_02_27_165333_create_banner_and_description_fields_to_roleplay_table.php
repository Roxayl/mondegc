<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerAndDescriptionFieldsToRoleplayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roleplay', function (Blueprint $table) {
            $table->text('description')->after('user_id');
            $table->string('banner', 191)->after('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roleplay', function (Blueprint $table) {
            $table->dropColumn('banner');
        });
    }
}
