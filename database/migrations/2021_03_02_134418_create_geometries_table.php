<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeometriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geometries', function (Blueprint $table) {
            $table->integer('ch_geo_id', true);
            $table->integer('type_geometrie_id')->nullable()->index('geometries_type_geometries_id_fk');
            $table->mediumText('ch_geo_wkt');
            $table->integer('ch_geo_pay_id')->index('geometries_pays_ch_pay_id_fk');
            $table->integer('ch_geo_user');
            $table->integer('ch_geo_maj_user');
            $table->dateTime('ch_geo_date');
            $table->dateTime('ch_geo_mis_jour');
            $table->string('ch_geo_geometries', 100);
            $table->decimal('ch_geo_mesure', 20, 3);
            $table->string('ch_geo_type', 100)->nullable();
            $table->string('ch_geo_nom', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geometries');
    }
}
