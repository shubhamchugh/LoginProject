<?php

namespace App\Models;

use App\Models\FakeUser;
use App\Models\PostContent;
use App\Models\User;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;

    protected $dates = ['published_at'];

    protected $fillable = [
        'post_title',
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

    ];

    public function content()
    {
        return $this->hasMany(PostContent::class, 'post_id');
    }

    public function fakeAuthor()
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

    public function scopeDraft($query)
    {
        return $query->where("status", "=", "0");
    }
    public function scopeOnlyPost($query)
    {
        return $query->where("post_type", "=", "post");
    }

    public function publicationLabel()
    {
        if (!$this->published_at) {
            return '<span class="badge badge-pill badge-light-primary mr-1">Draft</span>';
        } elseif ($this->published_at && $this->published_at->isFuture()) {
            return '<span class="badge badge-pill badge-light-warning mr-1">Schedule</span>';
        } else {
            return '<span class="badge badge-pill badge-light-success mr-1">Published</span>';
        }
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
