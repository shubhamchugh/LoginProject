<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Helpers\GeneralSettings;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function show(Page $page, GeneralSettings $settings)
    {
        $theme_path_page = 'themes.' . config('constant.THEME_NAME') . '.content.page';
        return view($theme_path_page,
            [
                'page'     => $page,
                'settings' => $settings,
            ]);
    }
}
