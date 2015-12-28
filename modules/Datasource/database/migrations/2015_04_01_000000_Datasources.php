<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Datasources extends Migration
{
    public function up()
    {
        Schema::create('datasources', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('folder_id')->default(0);
            $table->string('type')->index();
            $table->string('name');
            $table->text('description');
            $table->boolean('is_indexable')->default(false);
            $table->unsignedInteger('created_by_id');
            $table->text('settings');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('datasources');
    }
}
