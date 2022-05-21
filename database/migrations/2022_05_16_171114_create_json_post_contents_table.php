<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJsonPostContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('json_post_contents', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('post_id')->unsigned();
            $table->bigInteger('fake_user_id')->unsigned()->nullable();

            $table->text('post_description')->nullable();
            $table->text('post_thumbnail')->nullable();

            $table->longText('bing_related_keywords')->nullable();
            $table->longText('google_related_keywords')->nullable();

            $table->longText('bing_videos')->nullable();
            $table->longText('bing_images')->nullable();

            $table->longText('bing_news_title')->nullable();
            $table->longText('bing_news_description')->nullable();

            $table->longText('bing_search_result_title')->nullable();
            $table->longText('bing_search_result_description')->nullable();
            $table->longText('bing_search_result_url')->nullable();

            $table->longText('bing_paa_questions')->nullable();
            $table->longText('bing_paa_answers')->nullable();

            $table->longText('bing_pop_faq_questions')->nullable();
            $table->longText('bing_pop_faq_answers')->nullable();

            $table->longText('bing_rich_snippet_text')->nullable();
            $table->longText('bing_rich_snippet_link')->nullable();

            $table->longText('bing_slider_faq_questions')->nullable();
            $table->longText('bing_slider_faq_answers')->nullable();

            $table->longText('bing_tab_faq_questions')->nullable();
            $table->longText('bing_tab_faq_answers')->nullable();

            $table->longText('google_rich_snippet')->nullable();

            $table->longText('google_faq_questions')->nullable();
            $table->longText('google_faq_answers')->nullable();

            $table->longText('google_search_result_title')->nullable();
            $table->longText('google_search_result_description')->nullable();
            $table->longText('google_search_result_url')->nullable();

            $table->longText('post_content_above')->nullable();
            $table->longText('post_content_middle')->nullable();
            $table->longText('post_content_after')->nullable();

            $table->integer('is_bing_results')->default('0');
            $table->integer('is_thumbnail_images')->default('0');
            $table->integer('is_bing_images')->default('0');
            $table->integer('is_bing_news')->default('0');
            $table->integer('is_bing_video')->default('0');
            $table->integer('is_google_results')->default('0');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('json_post_contents');
    }
}
