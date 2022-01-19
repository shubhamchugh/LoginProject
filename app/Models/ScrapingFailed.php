<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScrapingFailed extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_value',
        'error',
    ];
}
