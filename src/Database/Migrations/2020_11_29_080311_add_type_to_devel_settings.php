<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToDevelSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devel_settings', function (Blueprint $table) {
            $table->string('type', 10)->after('key')->default('string');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
    */
    public function down()
    {
        Schema::table('devel_settings', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
