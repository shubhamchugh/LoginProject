<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Helpers\GeneralSettings;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function show(Page $page, GeneralSettings $settings)
    {

        $menusResponse = nova_get_menu_by_slug('header');
        $menus         = $menusResponse['menuItems'];

        $theme_path_page = 'themes.' . config('constant.THEME_NAME') . '.content.page';
        return view($theme_path_page,
            [
                'page'     => $page,
                'settings' => $settings,
                'menus'    => $menus,
            ]);
    }
}
