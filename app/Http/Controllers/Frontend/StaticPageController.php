<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Helpers\GeneralSettings;
use App\Http\Controllers\Controller;

class StaticPageController extends Controller
{
    public function __invoke(GeneralSettings $settings)
    {

        $menusResponse = nova_get_menu_by_slug('header');
        $menus         = $menusResponse['menuItems'];

        $theme_path_staticpage = 'themes.' . config('constant.THEME_NAME') . '.content.staticpage.';
        return view($theme_path_staticpage . request()->segment(2), [
            'settings' => $settings,
            'menus'    => $menus,
        ]);
    }
}
