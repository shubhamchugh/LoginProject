<?php

namespace App\Models;

use App\Models\Post;
use App\Models\FakeUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostContent extends Model
{
    use HasFactory;

    // public $timestamps = false;

    protected $fillable = [
        'post_id',
        'fake_user_id',
        'is_json_transfer',
        'post_description',
        'post_thumbnail',
        'bing_related_keywords',
        'google_related_keywords',
        'bing_news',
        'bing_videos',
        'bing_images',
        'bing_search_result',
        'bing_paa',
        'bing_rich_snippet',
        'bing_slider_faq',
        'bing_pop_faq',
        'bing_tab_faq',
        'google_faq',
        'google_rich_snippet',
        'google_search_result',
        'is_bing_results',
        'is_thumbnail_images',
        'is_bing_images',
        'is_bing_news',
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
