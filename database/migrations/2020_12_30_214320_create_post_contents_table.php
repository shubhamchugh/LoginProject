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
            $table->text('bing_related_keywords')->nullable();
            $table->text('google_related_keywords')->nullable();
            $table->text('news')->nullable();
            $table->text('videos')->nullable();
            $table->text('bing_search_result')->nullable();
            $table->text('bing_paa')->nullable();
            $table->text('bing_rich_snippet')->nullable();
            $table->text('bing_slider_faq')->nullable();
            $table->text('bing_pop_faq')->nullable();
            $table->text('bing_tab_faq')->nullable();
            $table->text('google_faq')->nullable();
            $table->text('google_rich_snippet')->nullable();
            $table->text('google_search_result')->nullable();
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
