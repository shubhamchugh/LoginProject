<?php

namespace App\Http\Controllers;

use App\Models\PostContent;
use Illuminate\Support\Facades\DB;

class ResetDatabaseController extends Controller
{
    public function is_google_results_reset()
    {
        PostContent::where('is_google_results', '1')
            ->update([
                'is_google_results' => 0,
            ]);
        echo "is_google_results reset to 0";
    }

    public function is_bing_video_reset()
    {
        PostContent::where('is_bing_video', '1')
            ->update([
                'is_bing_video' => 0,
            ]);
        echo "is_bing_video reset to 0";
    }

    public function is_bing_news_reset()
    {
        PostContent::where('is_bing_news', '1')
            ->update([
                'is_bing_news' => 0,
            ]);
        echo "is_bing_news reset to 0";
    }

    public function is_bing_images_reset()
    {
        PostContent::where('is_bing_images', '1')
            ->update([
                'is_bing_images' => 0,
            ]);
        echo "is_bing_images reset to 0";
    }

    public function is_thumbnail_images_reset()
    {
        PostContent::where('is_thumbnail_images', '1')
            ->update([
                'is_thumbnail_images' => 0,
            ]);
        echo "is_thumbnail_images reset to 0";
    }

    public function is_bing_results_reset()
    {
        PostContent::where('is_bing_results', '1')
            ->update([
                'is_bing_results' => 0,
            ]);
        echo "is_bing_results reset to 0";
    }

    public function is_scraped_reset()
    {
        DB::table('source_urls')->update([
            'is_scraped' => 'pending',
        ]);
        echo "is_scraped reset to pending";
    }
}
