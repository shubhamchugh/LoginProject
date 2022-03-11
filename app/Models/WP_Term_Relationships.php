<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WP_Term_Relationships extends Model
{
    use HasFactory;
    protected $connection = 'wordpress_mysql';
    protected $table      = 'wp_term_relationships';
    public $timestamps    = false;

    protected $fillable = [
        'object_id',
        'term_taxonomy_id',
    ];
}
