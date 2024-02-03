<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubdivisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subdivision_types', function (Blueprint $table) {
            $table->id();
            $table->integer('pays_id')->nullable();
            $table->string('type_name');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('pays_id')
                ->references('ch_pay_id')->on('pays')
                ->nullOnDelete()->cascadeOnUpdate();
        });

        Schema::create('subdivisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subdivision_type_id')->nullable();
            $table->string('name', 191);
            $table->longText('summary')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('subdivision_type_id')
                ->references('id')->on('subdivision_types')
                ->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subdivisions');
        Schema::dropIfExists('subdivision_types');
    }
}
