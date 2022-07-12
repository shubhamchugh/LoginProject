<?php

namespace App\Models\Flarum;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlarumUsers extends Model
{
    use HasFactory;

    protected $connection = 'Flarum_mysql';
    protected $table      = 'Flarum_users';
    public $timestamps    = false;

    protected $fillable = [
        'username',
        'email',
        'password',
    ];
}
