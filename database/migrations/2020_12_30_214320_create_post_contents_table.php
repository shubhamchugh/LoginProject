<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('post_id')->unsigned();
            $table->bigInteger('fake_user_id')->unsigned()->nullable();
            $table->string('post_description')->nullable();
            $table->string('post_thumbnail')->nullable();
            $table->longText('bing_related_keywords')->nullable();
            $table->longText('google_related_keywords')->nullable();
            $table->longText('bing_news')->nullable();
            $table->longText('bing_videos')->nullable();
            $table->longText('bing_images')->nullable();
            $table->longText('bing_search_result')->nullable();
            $table->longText('bing_paa')->nullable();
            $table->longText('bing_rich_snippet')->nullable();
            $table->longText('bing_slider_faq')->nullable();
            $table->longText('bing_pop_faq')->nullable();
            $table->longText('bing_tab_faq')->nullable();
            $table->longText('google_faq')->nullable();
            $table->longText('google_rich_snippet')->nullable();
            $table->longText('google_search_result')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_contents');
    }
}
