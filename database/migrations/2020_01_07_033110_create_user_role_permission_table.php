<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
