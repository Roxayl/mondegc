<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfluenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('influence', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('influencable_type', 191);
            $table->integer('influencable_id')->nullable();
            $table->integer('budget')->default(0);
            $table->integer('agriculture')->default(0);
            $table->integer('commerce')->default(0);
            $table->integer('education')->default(0);
            $table->integer('environnement')->default(0);
            $table->integer('industrie')->default(0);
            $table->integer('recherche')->default(0);
            $table->integer('tourisme')->default(0);
            $table->dateTime('generates_influence_at');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->index(['influencable_type', 'influencable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('influence');
    }
}
