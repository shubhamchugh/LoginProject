<?php

namespace App\Models;

use Carbon\Carbon;
use App\Casts\Json;
use App\Models\User;
use App\Models\FakeUser;
use App\Models\JsonPostContent;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;

    protected $dates = ['published_at'];

    protected $casts = [
        'bing_related_keywords_json' => Json::class,
    ];

    protected $fillable = [
        'post_title',
        'post_title_seo',
        'is_content',
        'post_type',
        'post_title',
        'slug',
        'source_value',
        'fake_user_id',
        'status',
        'view_count',
        'created_at',
        'updated_at',
        'published_at',
        'google_index',
        'bing_index',
        'wordpress_transfer',
        'Flarum_transfer',

    ];

    public function content()
    {
        return $this->hasMany(JsonPostContent::class, 'post_id');
    }

    public function FakeUser()
    {
        return $this->belongsTo(FakeUser::class, 'fake_user_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function scopePublished($query)
    {
        return $query->where("published_at", "<=", Carbon::now());
    }

    public function scopeScheduled($query)
    {
        return $query->where("published_at", ">", Carbon::now());
    }

    public function getDateAttribute($value)
    {
        return is_null($this->published_at) ? '' : $this->published_at->diffForHumans();
    }

    public function dateFormatted($showTimes = false)
    {
        $format = "d/m/Y";
        if ($showTimes) {
            $format = $format . " H:i:s";
        }

        return $this->created_at->format($format);
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source'            => 'post_title',
                'slugEngineOptions' => [
                    'lowercase' => false,
                ],
            ],
        ];
    }
}
