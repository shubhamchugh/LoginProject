<?php

namespace App\Models;

use App\Casts\Json;
use App\Models\Post;
use App\Models\FakeUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JsonPostContent extends Model
{
    use HasFactory;

    protected $casts = [
        'bing_related_keywords'            => Json::class,

        'bing_videos'                      => Json::class,
        'bing_images'                      => Json::class,

        'bing_search_result'               => Json::class,

        'bing_news_title'                  => Json::class,
        'bing_news_description'            => Json::class,

        'bing_search_result_title'         => Json::class,
        'bing_search_result_description'   => Json::class,
        'bing_search_result_url'           => Json::class,

        'bing_paa_questions'               => Json::class,
        'bing_paa_answers'                 => Json::class,

        'bing_pop_faq_questions'           => Json::class,
        'bing_pop_faq_answers'             => Json::class,

        'bing_slider_faq_questions'        => Json::class,
        'bing_slider_faq_answers'          => Json::class,

        'bing_tab_faq_questions'           => Json::class,
        'bing_tab_faq_answers'             => Json::class,

        'google_related_keywords'          => Json::class,

        'google_faq_questions'             => Json::class,
        'google_faq_answers'               => Json::class,

        'google_search_result_title'       => Json::class,
        'google_search_result_description' => Json::class,
        'google_search_result_url'         => Json::class,
    ];

    protected $fillable = [
        'post_id',
        'fake_user_id',

        'post_description',
        'post_thumbnail',

        'bing_related_keywords',

        'bing_news_title',
        'bing_news_description',

        'bing_videos',
        'bing_images',

        'bing_search_result_title',
        'bing_search_result_description',
        'bing_search_result_url',

        'bing_paa_questions',
        'bing_paa_answers',

        'bing_pop_faq_questions',
        'bing_pop_faq_answers',

        'bing_rich_snippet_text',
        'bing_rich_snippet_link',

        'bing_slider_faq_questions',
        'bing_slider_faq_answers',

        'bing_tab_faq_questions',
        'bing_tab_faq_answers',

        'google_related_keywords',

        'google_faq_questions',
        'google_faq_answers',

        'google_rich_snippet',

        'google_search_result_title',
        'google_search_result_description',
        'google_search_result_url',

        'post_content_above',
        'post_content_middle',
        'post_content_after',

        'is_bing_results',
        'is_thumbnail_images',
        'is_bing_images',
        'is_bing_news',
        'is_bing_video',
        'is_google_results',

        'created_at',
        'updated_at',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function contentFakeAuthor()
    {
        return $this->belongsTo(FakeUser::class, 'fake_user_id');
    }
}
