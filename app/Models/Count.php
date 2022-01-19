<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Count extends Model
{
    use HasFactory;

    protected $fillable = [
        'count',
        'is_scrape',
    ];
}
