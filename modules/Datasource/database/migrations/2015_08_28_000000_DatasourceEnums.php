<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DatasourceEnums extends Migration
{
    public function up()
    {
        Schema::create('datasource_enums', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('field_id')->nullable()->index();
            $table->string('value')->index();
            $table->integer('position')->default(0);

            $table->unique(['field_id', 'value']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('datasource_enums');
    }
}
