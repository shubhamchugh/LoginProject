<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\WP_Posts;
use App\Models\PostContent;
use App\Models\WP_Term_Relationships;

class CreateWordPressPostController extends Controller
{
    public function create()
    {
        $post = Post::where('wordpress_transfer', '0')->orderBy('id', 'asc')->first();
        if (!empty($post)) {
            $post->update([
                'wordpress_transfer' => 1,
            ]);
            $postContent = PostContent::where('post_id', $post->id)->orderBy('id', 'asc')->get()->toArray();

            $postContent = $postContent[mt_rand(0, (count($postContent) - 1))];

            $author                  = $postContent['fake_user_id'];
            $bing_related_keywords   = (!empty($postContent['bing_related_keywords'])) ? unserialize($postContent['bing_related_keywords']) : array();
            $google_related_keywords = (!empty($postContent['bing_related_keywords'])) ? unserialize($postContent['google_related_keywords']) : array();
            $bing_news               = (!empty($postContent['bing_news'])) ? unserialize($postContent['bing_news']) : array();
            $bing_videos             = (!empty($postContent['bing_videos'])) ? unserialize($postContent['bing_videos']) : array();
            $bing_images             = (!empty($postContent['bing_images'])) ? unserialize($postContent['bing_images']) : array();
            $bing_search_result      = (!empty($postContent['bing_search_result'])) ? unserialize($postContent['bing_search_result']) : array();
            $bing_paa                = (!empty($postContent['bing_paa'])) ? unserialize($postContent['bing_paa']) : array();
            $bing_rich_snippet       = (!empty($postContent['bing_rich_snippet'])) ? unserialize($postContent['bing_rich_snippet']) : array();
            $bing_slider_faq         = (!empty($postContent['bing_slider_faq'])) ? unserialize($postContent['bing_slider_faq']) : array();
            $bing_pop_faq            = (!empty($postContent['bing_pop_faq'])) ? unserialize($postContent['bing_pop_faq']) : array();
            $bing_tab_faq            = (!empty($postContent['bing_tab_faq'])) ? unserialize($postContent['bing_tab_faq']) : array();
            $google_faq              = (!empty($postContent['google_faq'])) ? unserialize($postContent['google_faq']) : array();
            $google_rich_snippet     = (!empty($postContent['google_rich_snippet'])) ? unserialize($postContent['google_rich_snippet']) : array();
            $google_search_result    = (!empty($postContent['google_search_result'])) ? unserialize($postContent['google_search_result']) : array();
            $indexedArray            = array("new", "trend", "hot", "top", "best", "tip", "great", "recommended", "suggest", "worst", "excellent", "fabulous");
            $totalimages             = (!empty($bing_images['images'])) ? (count($bing_images['images']) - 1) : null;
            $totalvideos             = (!empty($bing_videos)) ? (count($bing_videos) - 1) : null;

            $post_content = view('wordpress-post-theme',
                [
                    'post'                    => $post,
                    'postContent'             => $postContent,
                    'author'                  => $author,
                    'bing_related_keywords'   => $bing_related_keywords,
                    'google_related_keywords' => $google_related_keywords,
                    'bing_news'               => $bing_news,
                    'bing_videos'             => $bing_videos,
                    'bing_images'             => $bing_images,
                    'bing_search_result'      => $bing_search_result,
                    'bing_paa'                => $bing_paa,
                    'bing_rich_snippet'       => $bing_rich_snippet,
                    'bing_slider_faq'         => $bing_slider_faq,
                    'bing_pop_faq'            => $bing_pop_faq,
                    'bing_tab_faq'            => $bing_tab_faq,
                    'google_faq'              => $google_faq,
                    'google_rich_snippet'     => $google_rich_snippet,
                    'google_search_result'    => $google_search_result,
                    'indexedArray'            => $indexedArray,
                    'totalimages'             => $totalimages,
                    'totalvideos'             => $totalvideos,
                ])->render();
            echo $post_content;

            $data = WP_Posts::create([
                'post_title'            => $post->post_title,
                'post_name'             => $post->slug,
                'post_author'           => 1,
                'post_date'             => Carbon::now(),
                'post_date_gmt'         => Carbon::now(),
                'post_content'          => $post_content,
                'post_excerpt'          => '',
                'post_status'           => 'publish',
                'comment_status'        => 'open',
                'ping_status'           => 'open',
                'post_password'         => '',
                'to_ping'               => '',
                'pinged'                => '',
                'post_modified'         => Carbon::now(),
                'post_modified_gmt'     => Carbon::now(),
                'post_content_filtered' => '',
                'post_parent'           => 0,
                'guid'                  => $post->slug,
                'menu_order'            => 0,
                'post_type'             => 'post',
                'post_mime_type'        => '',
                'comment_count'         => 0,
            ]);

            WP_Term_Relationships::create([
                'object_id'        => $data->id,
                'term_taxonomy_id' => config('constant.WORDPRESS_DB_CAT_ID'), // Please Insert wp-admin/term.php?taxonomy=category&tag_ID=3 (Id) from Edit Category Page
            ]);
            dd("Post Transfer to wordpress Successfully");
        } else {
            echo "No post fround for transfer to wordpress";
            dd();
        }
    }
}
