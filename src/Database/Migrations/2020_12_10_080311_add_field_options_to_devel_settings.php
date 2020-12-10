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
            $table->string('field_options')->after('value')->nullable();
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
            $table->dropColumn('field_options');
        });
    }
}
