<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Widgets extends Migration
{
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->timestamps();
            $table->increments('id');
            $table->string('name', 100);
            $table->text('description');
            $table->string('type', 100);
            $table->string('template', 100)->nullable();
            $table->text('settings');
        });
    }

    public function down()
    {
        Schema::dropIfExists('widgets');
    }
}
