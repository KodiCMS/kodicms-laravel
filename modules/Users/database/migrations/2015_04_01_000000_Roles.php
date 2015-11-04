<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Roles extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 32)->unique();
            $table->string('description');
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
