<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevelUserPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devel_user_permission', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->string('permission');

            $table->foreign('permission')
                ->references('key')->on('devel_user_permissions')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devel_user_permission');
    }
}
