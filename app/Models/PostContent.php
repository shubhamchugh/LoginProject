<?php

namespace App\Models;

use App\Models\FakeUser;
use App\Models\Metadata;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostContent extends Model
{
    use HasFactory;

    protected $dates = ['published_at'];

    protected $fillable = [
        'post_id',
        'fake_user_id',
        'content_title',
        'content_url',
        'content_dec',
        'content_image',
        'is_image',
        'is_metadata',
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
