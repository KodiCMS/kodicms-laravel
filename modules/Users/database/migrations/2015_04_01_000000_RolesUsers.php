<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RolesUsers extends Migration
{

    public function up()
    {
        Schema::create('roles_users', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('role_id');

            $table->primary(['user_id', 'role_id']);
        });
    }


    public function down()
    {
        Schema::dropIfExists('roles_users');
    }
}
