<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexJsonPostContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('json_post_contents', function (Blueprint $table) {
            $table->index('is_bing_results');
            $table->index('is_thumbnail_images');
            $table->index('is_bing_images');
            $table->index('is_bing_news');
            $table->index('is_bing_video');
            $table->index('is_google_results');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('json_post_contents', function (Blueprint $table) {
            //
        });
    }
}
