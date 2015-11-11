<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Users extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('email', 127)->unique();
            $table->string('username', 32)->unique();

            $table->char('password', 64);

            $table->integer('logins')->default(0);
            $table->integer('last_login')->nullable();

            $table->string('locale', 5)->default(config('app.locale'));

            $table->string('avatar', 100)->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
