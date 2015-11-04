<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PageParts extends Migration
{
    public function up()
    {
        Schema::create('page_parts', function (Blueprint $table) {
            $table->timestamps();

            $table->increments('id');
            $table->unsignedInteger('page_id')->index();

            $table->string('name')->index();
            $table->string('wysiwyg')->nullable();

            $table->string('content');
            $table->string('content_html');

            $table->boolean('is_expanded')->default(true);
            $table->boolean('is_indexable')->default(true);
            $table->boolean('is_protected')->default(false);
            $table->smallInteger('position')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_parts');
    }
}
