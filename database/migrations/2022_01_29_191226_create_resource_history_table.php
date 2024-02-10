<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Roxayl\MondeGC\Models\Enums\Resource;

class CreateResourceHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_history', function (Blueprint $table) {
            $table->id();
            $table->string('resourceable_type', 191)->index();
            $table->bigInteger('resourceable_id')->index();

            foreach (Resource::cases() as $resource) {
                $table->integer($resource->value);
            }

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resource_history');
    }
}
