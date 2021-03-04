<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToGeometriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('geometries', function (Blueprint $table) {
            $table->foreign('ch_geo_pay_id', 'geometries_pays_ch_pay_id_fk')->references('ch_pay_id')->on('pays')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('type_geometrie_id', 'geometries_type_geometries_id_fk')->references('id')->on('type_geometries')->onUpdate('SET NULL')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('geometries', function (Blueprint $table) {
            $table->dropForeign('geometries_pays_ch_pay_id_fk');
            $table->dropForeign('geometries_type_geometries_id_fk');
        });
    }
}
