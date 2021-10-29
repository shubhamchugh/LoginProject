<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScrapingChunk extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'start',
        'end',
        'limit'
    ];
}
