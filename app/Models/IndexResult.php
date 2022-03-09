<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IndexResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'search_engine',
        'notifyTime',
        'type',
        'url',
        'status_code',
    ];
}
