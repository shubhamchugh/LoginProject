<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SourceUrl;
use App\Models\JsonPostContent;

class QueryStatusController extends Controller
{

    public function __invoke()
    {
        $is_scraped_distinct = SourceUrl::select('is_scraped')->distinct()->get();

        foreach ($is_scraped_distinct as $distinct_record) {
            $count                                           = SourceUrl::where('is_scraped', $distinct_record->is_scraped)->count();
            $is_scraped_status[$distinct_record->is_scraped] = $count;
        }

        $is_bing_results = JsonPostContent::whereNull('bing_search_result_url')->where('is_bing_results', '0')->count();

        $is_thumbnail_images = JsonPostContent::whereNull('post_thumbnail')->where('is_thumbnail_images', '0')->count();

        $is_bing_images = JsonPostContent::whereNull('bing_images')->where('is_bing_images', '0')->count();

        $is_bing_news = JsonPostContent::whereNull('bing_news_title')->where('is_bing_news', '0')->count();

        $is_bing_video = JsonPostContent::whereNull('bing_videos')->where('is_bing_video', '0')->count();

        $is_google_results = JsonPostContent::whereNull('google_search_result_url')->where('is_google_results', '0')->count();

        /**************************
         * WORDPRESS INDEX STATUS *
         **************************/

        $wordpress_transfer_distinct = Post::select('wordpress_transfer')->distinct()->get();

        foreach ($wordpress_transfer_distinct as $wordpress_transfer_distinct_value) {

            $wordpress_transfer_count                                                          = Post::where('wordpress_transfer', $wordpress_transfer_distinct_value->wordpress_transfer)->count();
            $wordpress_transfer_status[$wordpress_transfer_distinct_value->wordpress_transfer] = $wordpress_transfer_count;
        }

        /***********************
         * GOOGLE INDEX STATUS *
         ***********************/

        $google_index_distinct = Post::select('google_index')->distinct()->get();

        foreach ($google_index_distinct as $google_index_distinct_value) {

            $google_index_count                                              = Post::where('google_index', $google_index_distinct_value->google_index)->count();
            $google_index_status[$google_index_distinct_value->google_index] = $google_index_count;
        }

        /*********************
         * BING INDEX STATUS *
         *********************/

        $bing_index_distinct = Post::select('bing_index')->distinct()->get();

        foreach ($bing_index_distinct as $bing_index_distinct_value) {

            $bing_index_count = Post::where('bing_index', $bing_index_distinct_value->bing_index)->count();

            $bing_index_status[$bing_index_distinct_value->bing_index] = $bing_index_count;
        }

        /***********************
         * FLARUM INDEX STATUS *
         ***********************/

        $Flarum_transfer_distinct = Post::select('Flarum_transfer')->distinct()->get();

        foreach ($Flarum_transfer_distinct as $Flarum_transfer_distinct_value) {

            $Flarum_transfer_count                                                    = Post::where('Flarum_transfer', $Flarum_transfer_distinct_value->Flarum_transfer)->count();
            $Flarum_transfer_status[$Flarum_transfer_distinct_value->Flarum_transfer] = $Flarum_transfer_count;
        }

        /********************************************************************
         * BING_POP_FAQ_QUESTIONS    &&  GOOGLE_FAQ_QUESTIONS BOTH ARE NULL *
         ********************************************************************/

        $blank_post = JsonPostContent::whereNull('bing_pop_faq_questions')
            ->whereNull('google_faq_questions')
            ->count();

        return view('status', [
            'is_scraped_status'         => $is_scraped_status,
            'is_bing_results'           => $is_bing_results,
            'is_thumbnail_images'       => $is_thumbnail_images,
            'is_bing_images'            => $is_bing_images,
            'is_bing_news'              => $is_bing_news,
            'is_bing_video'             => $is_bing_video,
            'is_google_results'         => $is_google_results,
            'wordpress_transfer_status' => $wordpress_transfer_status,
            'google_index_status'       => $google_index_status,
            'bing_index_status'         => $bing_index_status,
            'Flarum_transfer_status'    => $Flarum_transfer_status,
            'blank_post'                => $blank_post,
        ]);
    }

}
