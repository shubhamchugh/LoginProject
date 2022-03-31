<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Helpers\GeneralSettings;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    public function homeList(GeneralSettings $settings)
    {
        $last_id = Post::latest()->first('id');
        if (!empty($last_id)) {
            $theme_path_home = 'themes.' . config('constant.THEME_NAME') . '.content.home';

            $posts = Post::with('content')->inRandomOrder()->limit(config('constant.HOMEPAGE_POST_PAGINATION'))
                ->paginate(config('constant.HOMEPAGE_POST_PAGINATION'), ['id', 'post_title', 'slug', 'published_at', 'updated_at']);

            return view($theme_path_home, [
                'posts'    => $posts,
                'settings' => $settings,
            ]);
        } else {
            echo "<h1 align='center'>Post Not Found Please Do Some Scraping</h1>";
        }

    }

    public function sitemap($sitemap, GeneralSettings $settings)
    {

        $theme_path_sitemap = 'themes.' . config('constant.THEME_NAME') . '.content.sitemap';

        $sitemap = Post::published()->where("slug", "like", "$sitemap%")->paginate(config('constant.SITEMAP_PAGE_PAGINATION'));

        return view($theme_path_sitemap, [
            'sitemap'  => $sitemap,
            'settings' => $settings,
        ]);
    }

    public function search(Request $request, GeneralSettings $settings)
    {
        $theme_path_search = 'themes.' . config('constant.THEME_NAME') . '.content.search';
        return view($theme_path_search, [
            'settings' => $settings,
        ]);
    }
}
