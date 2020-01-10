<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_role_permission', function (Blueprint $table) {
            $table->string('role');
            $table->string('permission');

            $table->foreign('role')
                ->references('key')->on('user_roles')
                ->onDelete('CASCADE');

            $table->foreign('permission')
                ->references('key')->on('user_permissions')
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
        Schema::dropIfExists('user_role_permission');
    }
}
