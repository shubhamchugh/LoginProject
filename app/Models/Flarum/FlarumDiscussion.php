<?php

namespace App\Models\Flarum;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlarumDiscussion extends Model
{
    use HasFactory;
    protected $connection = 'Flarum_mysql';
    protected $table      = 'Flarum_discussions';
    public $timestamps    = false;
    protected $fillable   = [
        'title',
        'slug',
        'is_approved',
        'best_answer_notified',
        'created_at',
        'user_id',
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source'            => 'title',
                'slugEngineOptions' => [
                    'lowercase' => false,
                ],
            ],
        ];
    }
}
