<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTypeGeometriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('type_geometries', function (Blueprint $table) {
            $table->foreign('group_id', 'type_geometries_type_geometries_group_id_fk')->references('id')->on('type_geometries_group')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('type_geometries', function (Blueprint $table) {
            $table->dropForeign('type_geometries_type_geometries_group_id_fk');
        });
    }
}
