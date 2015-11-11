<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Pages\Model\FrontendPage;

class Pages extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->timestamps();
            $table->timestamp('published_at');

            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable()->index();

            $table->smallInteger('status')->index()->default(FrontendPage::STATUS_PUBLISHED);

            $table->string('behavior')->nullable();

            $table->string('title');
            $table->string('slug', 100)->index();
            $table->string('breadcrumb', 100);

            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();

            $table->string('robots', 100)->nullable();
            $table->string('layout_file')->nullable();

            $table->unsignedInteger('created_by_id')->nullable();
            $table->unsignedInteger('updated_by_id')->nullable();

            $table->smallInteger('position')->default(0);

            $table->boolean('is_redirect')->default(false);
            $table->string('redirect_url')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
