<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public $site_name,
    $home_title,
    $google_forms_contact,
    $home_h1_title,
    $home_page_description,
    $header_code,
    $theme_color,
    $author_name,
    $bellow_title_ads,
    $about_us,
    $before_title,
        $after_title;

    public static function group(): string
    {
        return 'general';
    }
}
