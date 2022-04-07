<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBingResultUpdateFlagToPostContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_contents', function (Blueprint $table) {
            $table->integer('is_bing_results')->default('0');
            $table->integer('is_thumbnail_images')->default('0');
            $table->integer('is_bing_images')->default('0');
            $table->integer('is_bing_news')->default('0');
            $table->integer('is_bing_video')->default('0');
            $table->integer('is_google_results')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_contents', function (Blueprint $table) {
            $table->dropColumn([
                'is_bing_results',
                'is_thumbnail_images',
                'is_bing_images',
                'is_bing_images',
                'is_bing_news',
                'is_bing_video',
                'is_google_results',
            ]);
        });
    }
}
