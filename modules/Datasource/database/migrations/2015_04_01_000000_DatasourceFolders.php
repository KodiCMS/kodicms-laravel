<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DatasourceFolders extends Migration
{
    public function up()
    {
        Schema::create('datasource_folders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('icon');
            $table->integer('position')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('datasource_folders');
    }
}
