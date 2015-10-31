<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Datasources extends Migration
{

    public function up()
    {
        Schema::create('datasources', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('folder_id')->default(0);
            $table->string('type')->index();
            $table->string('name');
            $table->text('description');

            $table->boolean('is_indexable')->default(false);
            $table->integer('created_by_id');
            $table->json('settings');

            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('datasources');
    }
}
