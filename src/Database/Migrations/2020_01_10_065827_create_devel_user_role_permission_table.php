<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevelUserRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devel_user_role_permission', function (Blueprint $table) {
            $table->string('role');
            $table->string('permission');

            $table->foreign('role')
                ->references('key')->on('devel_user_roles')
                ->onDelete('CASCADE');

            $table->foreign('permission')
                ->references('key')->on('devel_user_permissions')
                ->onDelete('CASCADE');

            $table->unique(['role', 'permission']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devel_user_role_permission');
    }
}
