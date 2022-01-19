<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    private $limit = 50;

    public function homeList()
    {
        $last_id = Post::orderBy('id', 'DESC')->published()->first();

        $theme_path_home = 'themes.' . config('app.THEME_NAME') . '.content.home';

        $posts = Post::wherein('id', (getRandomNumberArray(1, $last_id->id, config('app.HOMEPAGE_POST_COUNT'))))
            ->published()
            ->paginate(config('app.HOMEPAGE_POST_PAGINATION'));

        return view($theme_path_home, [
            'posts' => $posts,
        ]);
    }

    public function sitemap($sitemap)
    {

        $theme_path_sitemap = 'themes.' . config('app.THEME_NAME') . '.content.sitemap';

        $sitemap = Post::published()->where("slug", "like", "$sitemap%")->paginate($this->limit);

        return view($theme_path_sitemap, [
            'sitemap' => $sitemap,
        ]);
    }

    public function search(Request $request)
    {
        $theme_path_search = 'themes.' . config('app.THEME_NAME') . '.content.search';
        return view($theme_path_search);
    }
}
