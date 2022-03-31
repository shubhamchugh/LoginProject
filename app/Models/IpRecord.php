<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IpRecord extends Model
{
    use HasFactory;
    protected $connection = 'NODE_SCRAPER_IP_MANAGER_MYSQL';
    protected $table      = 'ip_records';

    protected $fillable = [
        'status',
        'ip_address',
    ];
}
