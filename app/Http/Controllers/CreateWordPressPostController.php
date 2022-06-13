<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\WP_Posts;
use App\Models\JsonPostContent;
use App\Helpers\GeneralSettings;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\WP_Term_Relationships;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Config;
use Artesaos\SEOTools\Facades\SEOTools;

class CreateWordPressPostController extends Controller
{
    public function create(GeneralSettings $settings)
    {
        $post = Post::where('wordpress_transfer', '0')->orderBy('id', 'asc')->first();

        if (!empty($post)) {
            $post->update([
                'wordpress_transfer' => 1,
            ]);

            $postContent = JsonPostContent::where('post_id', $post->id)
                ->where(function ($query) {
                    $query->orWhereNotNull('bing_paa_questions')
                        ->orWhereNotNull('google_faq_questions');
                })
                ->orderBy('id', 'asc')
                ->get()
                ->toArray();

            if (empty($postContent)) {
                dd("Post do not have any data Please refresh to send next post to wordpress");
            }

            $postContent = $postContent[mt_rand(0, (count($postContent) - 1))];

            $author     = $postContent['fake_user_id'];
            $updated_at = Carbon::parse($postContent['updated_at']);

            $bing_rich_snippet_text = (!empty($postContent['bing_rich_snippet_text'])) ? ($postContent['bing_rich_snippet_text']) : null;
            $bing_rich_snippet_link = (!empty($postContent['bing_rich_snippet_link'])) ? ($postContent['bing_rich_snippet_link']) : null;

            $bing_related_keywords   = (!empty($postContent['bing_related_keywords'])) ? ($postContent['bing_related_keywords']) : null;
            $google_related_keywords = (!empty($postContent['bing_related_keywords'])) ? ($postContent['google_related_keywords']) : null;

            $bing_news_title       = (!empty($postContent['bing_news_title'])) ? ($postContent['bing_news_title']) : null;
            $bing_news_description = (!empty($postContent['bing_news_description'])) ? ($postContent['bing_news_description']) : null;

            $bing_videos = (!empty($postContent['bing_videos'])) ? ($postContent['bing_videos']) : null;
            $bing_images = (!empty($postContent['bing_images'])) ? ($postContent['bing_images']) : null;

            $bing_search_result_title       = (!empty($postContent['bing_search_result_title'])) ? ($postContent['bing_search_result_title']) : array();
            $bing_search_result_description = (!empty($postContent['bing_search_result_description'])) ? ($postContent['bing_search_result_description']) : array();
            $bing_search_result_url         = (!empty($postContent['bing_search_result_url'])) ? ($postContent['bing_search_result_url']) : array();

            $bing_paa_questions = (!empty($postContent['bing_paa_questions'])) ? ($postContent['bing_paa_questions']) : null;
            $bing_paa_answers   = (!empty($postContent['bing_paa_answers'])) ? ($postContent['bing_paa_answers']) : null;

            $bing_slider_faq_questions = (!empty($postContent['bing_slider_faq_questions'])) ? ($postContent['bing_slider_faq_questions']) : null;
            $bing_slider_faq_answers   = (!empty($postContent['bing_slider_faq_answers'])) ? ($postContent['bing_slider_faq_answers']) : null;

            $bing_pop_faq_questions = (!empty($postContent['bing_pop_faq_questions'])) ? ($postContent['bing_pop_faq_questions']) : null;
            $bing_pop_faq_answers   = (!empty($postContent['bing_pop_faq_answers'])) ? ($postContent['bing_pop_faq_answers']) : null;

            $bing_tab_faq_questions = (!empty($postContent['bing_tab_faq_questions'])) ? ($postContent['bing_tab_faq_questions']) : null;
            $bing_tab_faq_answers   = (!empty($postContent['bing_tab_faq_answers'])) ? ($postContent['bing_tab_faq_answers']) : null;

            $google_faq_questions = (!empty($postContent['google_faq_questions'])) ? ($postContent['google_faq_questions']) : null;
            $google_faq_answers   = (!empty($postContent['google_faq_answers'])) ? ($postContent['google_faq_answers']) : null;

            $google_rich_snippet  = (!empty($postContent['google_rich_snippet'])) ? ($postContent['google_rich_snippet']) : null;
            $google_search_result = (!empty($postContent['google_search_result'])) ? ($postContent['google_search_result']) : null;

            $indexedArray = config('constant.engagement_keywords_news');

            $total_images = (!empty($bing_images)) ? (count($bing_images) - 1) : null;
            $total_videos = (!empty($bing_videos)) ? (count($bing_videos) - 1) : null;

            $theme_path_post = 'themes.' . config('constant.THEME_NAME') . '.content.post';

            $bing_related_keywords_implode   = (!empty($bing_related_keywords)) ? implode(", ", $bing_related_keywords) : null;
            $google_related_keywords_implode = (!empty($google_related_keywords)) ? implode(", ", $google_related_keywords) : null;

            //SEO FOR POST PAGE
            $SEO_dec  = (!empty($google_rich_snippet[0])) ? strip_tags($google_rich_snippet[0]) : $postContent['post_description'];
            $keywords = (!empty($google_related_keywords)) ? $google_related_keywords_implode : $bing_related_keywords_implode;

            $seo_image = (!empty($bing_images[mt_rand(0, $total_images)])) ? $bing_images[mt_rand(0, $total_images)] : json_encode(['murl' => url('themes/DevBlog/assets/images/profile.png')]);

            $before_title = (!empty($settings->before_title)) ? $settings->before_title : null;
            $after_title  = (!empty($settings->after_title)) ? $settings->after_title : null;

            $title = (!empty($post_title_seo)) ? $post_title_seo : ($before_title . ' ' . ucwords($post->post_title) . ' ' . $after_title);

            SEOTools::setTitle($title);
            SEOTools::setDescription($SEO_dec);
            SEOTools::opengraph()->setUrl(URL::current())->addProperty('type', 'articles');
            SEOMeta::addMeta('article:published_time', $updated_at->toW3CString(), 'property');
            SEOTools::setCanonical(URL::current());
            SEOTools::jsonLd()->addImage(json_decode($seo_image, true)['murl']);
            SEOMeta::setKeywords($keywords);
            $url_current = URL::current();
            //SEO END FOR POST PAGE

            $post_content = view('wordpress-post-theme',
                [
                    'post'                           => $post,
                    'postContent'                    => $postContent,
                    'author'                         => $author,
                    'updated_at'                     => $updated_at,

                    'bing_rich_snippet_text'         => $bing_rich_snippet_text,
                    'bing_rich_snippet_link'         => $bing_rich_snippet_link,

                    'bing_related_keywords'          => $bing_related_keywords,
                    'google_related_keywords'        => $google_related_keywords,

                    'bing_news_title'                => $bing_news_title,
                    'bing_news_description'          => $bing_news_description,

                    'bing_videos'                    => $bing_videos,
                    'bing_images'                    => $bing_images,

                    'bing_search_result_title'       => $bing_search_result_title,
                    'bing_search_result_description' => $bing_search_result_description,
                    'bing_search_result_url'         => $bing_search_result_url,

                    'bing_paa_questions'             => $bing_paa_questions,
                    'bing_paa_answers'               => $bing_paa_answers,

                    'bing_slider_faq_questions'      => $bing_slider_faq_questions,
                    'bing_slider_faq_answers'        => $bing_slider_faq_answers,

                    'bing_pop_faq_questions'         => $bing_pop_faq_questions,
                    'bing_pop_faq_answers'           => $bing_pop_faq_answers,

                    'bing_tab_faq_questions'         => $bing_tab_faq_questions,
                    'bing_tab_faq_answers'           => $bing_tab_faq_answers,

                    'google_faq_questions'           => $google_faq_questions,
                    'google_faq_answers'             => $google_faq_answers,

                    'google_rich_snippet'            => $google_rich_snippet,

                    'google_search_result'           => $google_search_result,

                    'indexedArray'                   => $indexedArray,

                    'total_images'                   => $total_images,
                    'total_videos'                   => $total_videos,

                    'settings'                       => $settings,

                    'url_current'                    => $url_current,
                ])->render();
            echo $post_content;

            $data = WP_Posts::create([
                'post_title'            => ucfirst($post->post_title),
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
