<?php

namespace App\Helpers;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public $site_name,
    $home_title,
    $home_heading,
    $home_description,
    $header_code,
        $search_bar_text;

    public static function group(): string
    {
        return 'general';
    }
}
