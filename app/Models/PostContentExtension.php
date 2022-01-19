<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostContentExtension extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'related_keywords',
        'news',
        'videos',
        'faq',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

}
