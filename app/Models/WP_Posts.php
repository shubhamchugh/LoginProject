<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WP_Posts extends Model
{
    use HasFactory;
    protected $connection = 'wordpress_mysql';
    protected $table      = 'wp_posts';
    public $timestamps    = false;

    protected $fillable = [
        'post_title',
        'post_name',
        'post_author',
        'post_date',
        'post_date_gmt',
        'post_content',
        'post_excerpt',
        'post_status',
        'comment_status',
        'ping_status',
        'post_password',
        'to_ping',
        'pinged',
        'post_modified',
        'post_modified_gmt',
        'post_content_filtered',
        'post_parent',
        'guid',
        'menu_order',
        'post_type',
        'post_mime_type',
        'comment_count',
    ];
}
