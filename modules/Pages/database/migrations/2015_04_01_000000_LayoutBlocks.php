<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class LayoutBlocks extends Migration
{
    public function up()
    {
        Schema::create('layout_blocks', function (Blueprint $table) {
            $table->string('layout_name', 100);
            $table->string('block', 32);
            $table->integer('position');

            $table->primary(['layout_name', 'block']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('layout_blocks');
    }
}
