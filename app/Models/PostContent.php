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

    protected $fillable = [
        'post_id',
        'fake_user_id',
        'content_dec',
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
