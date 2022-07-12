<?php

namespace App\Http\Controllers\Transfer;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\JsonPostContent;
use App\Helpers\GeneralSettings;
use App\Models\Flarum\FlarumPost;
use App\Models\Flarum\FlarumUsers;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\Flarum\FlarumDiscussion;

class DatabaseToFlarum extends Controller
{
    public function create(GeneralSettings $settings)
    {

        $users = FlarumUsers::inRandomOrder()->first();

        $post = Post::where('Flarum_transfer', 'pending')
            ->where('post_type', 'post')
            ->with('content')
            ->first();

        if (empty($post)) {
            return "No post fround for transfer to flarum Forum";
        }

        $postContent = JsonPostContent::where('post_id', $post->id)
            ->where(function ($query) {
                $query->orWhereNotNull('bing_paa_questions')
                    ->orWhereNotNull('google_faq_questions');
            })
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();

        if (empty($postContent)) {
            $post->update([
                'Flarum_transfer' => 'content_not_found',
            ]);
            return "Post do not have any data Please refresh to send next post to wordpress";
        }
        $post->update([
            'Flarum_transfer' => 'transfer_start',
        ]);

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

        // SEOTools::setTitle($title);
        // SEOTools::setDescription($SEO_dec);
        // SEOTools::opengraph()->setUrl(URL::current())->addProperty('type', 'articles');
        // SEOMeta::addMeta('article:published_time', $updated_at->toW3CString(), 'property');
        // SEOTools::setCanonical(URL::current());
        // SEOTools::jsonLd()->addImage(json_decode($seo_image, true)['murl']);
        // SEOMeta::setKeywords($keywords);
        $url_current = URL::current();
        // SEO END FOR POST PAGE

        $post_content = view('flarum-post-theme',
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

        $flarum_post = FlarumDiscussion::firstOrCreate([
            'title'                => $post->post_title,
            'slug'                 => $post->slug,
            'is_approved'          => 1,
            'created_at'           => Carbon::now(),
            'best_answer_notified' => 0,
            'user_id'              => $users->id,
        ]);

        $flarum_post_content = FlarumPost::firstOrCreate([
            'discussion_id' => $flarum_post->id,
            'created_at'    => Carbon::now(),
            'user_id'       => $users->id,
            'type'          => 'comment',
            'is_approved'   => 1,
            'content'       => $post_content,
        ]);

        $post->update([
            'Flarum_transfer' => 'transferred',
        ]);

        dd("Post Transfer to wordpress Successfully");
    }
}
