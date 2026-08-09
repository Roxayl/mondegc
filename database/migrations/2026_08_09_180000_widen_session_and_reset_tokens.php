<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WidenSessionAndResetTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Les jetons de session sont désormais stockés hachés en SHA-256 (64 caractères), et les
        // clefs de réinitialisation générées sur 32 octets.
        Schema::table('users_dispatch_session', function (Blueprint $table) {
            $table->string('ch_users_session_dispatch_Key', 64)->change();
        });

        Schema::table('users_provisoire', function (Blueprint $table) {
            $table->string('ch_use_prov_clef', 64)->nullable()->change();
        });

        // Les jetons existants sont en clair et ne correspondent plus au format attendu : les
        // sessions persistantes sont invalidées, les membres concernés devront se reconnecter.
        DB::table('users_dispatch_session')->delete();
        DB::table('users_session')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('users_dispatch_session')->delete();
        DB::table('users_session')->delete();

        Schema::table('users_dispatch_session', function (Blueprint $table) {
            $table->string('ch_users_session_dispatch_Key', 20)->change();
        });

        Schema::table('users_provisoire', function (Blueprint $table) {
            $table->string('ch_use_prov_clef', 10)->nullable()->change();
        });
    }
}
