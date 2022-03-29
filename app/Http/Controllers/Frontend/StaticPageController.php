<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Helpers\GeneralSettings;
use App\Http\Controllers\Controller;

class StaticPageController extends Controller
{
    public function __invoke(GeneralSettings $settings)
    {
        $theme_path_staticpage = 'themes.' . config('constant.THEME_NAME') . '.content.staticpage.';
        return view($theme_path_staticpage . request()->segment(2), [
            'settings' => $settings,
        ]);
    }
}
