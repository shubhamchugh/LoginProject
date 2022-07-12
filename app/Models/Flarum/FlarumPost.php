<?php

namespace App\Models\Flarum;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlarumPost extends Model
{
    use HasFactory;
    protected $connection = 'Flarum_mysql';
    protected $table      = 'Flarum_posts';
    public $timestamps    = false;

    protected $fillable = [
        'discussion_id',
        'created_at',
        'user_id',
        'type',
        'content',
        'is_approved',

    ];

}
