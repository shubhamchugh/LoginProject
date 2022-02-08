<?php

namespace App\Models;

use App\Models\Post;
use App\Models\FakeUser;
use App\Models\Metadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostContent extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'fake_user_id',
        'bing_related_keywords',
        'google_related_keywords',
        'news',
        'videos',
        'bing_search_result',
        'bing_paa',
        'bing_rich_snippet',
        'bing_slider_faq',
        'bing_pop_faq',
        'bing_tab_faq',
        'google_faq',
        'google_rich_snippet',
        'google_search_result',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function contentFakeAuthor()
    {
        return $this->belongsTo(FakeUser::class, 'fake_user_id');
    }

    public function postMeta()
    {
        return $this->hasOne(Metadata::class, 'content_id');
    }

}
