<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeGeometriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_geometries', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('group_id')->index('type_geometries_type_geometries_group_id_fk');
            $table->string('label');
            $table->string('type_geometrie', 50)->nullable();
            $table->float('coef_budget', 8, 5)->default(1.00000);
            $table->float('coef_industrie', 8, 5)->default(1.00000);
            $table->float('coef_commerce', 8, 5)->default(1.00000);
            $table->float('coef_agriculture', 8, 5)->default(1.00000);
            $table->float('coef_tourisme', 8, 5)->default(1.00000);
            $table->float('coef_recherche', 8, 5)->default(1.00000);
            $table->float('coef_environnement', 8, 5)->default(1.00000);
            $table->float('coef_education', 8, 5)->default(1.00000);
            $table->float('coef_population', 8, 5)->default(1.00000);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_geometries');
    }
}
