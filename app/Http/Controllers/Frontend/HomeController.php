<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Helpers\GeneralSettings;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOTools;

class HomeController extends Controller
{

    public function homeList(GeneralSettings $settings)
    {

        $last_id = Post::latest()->first('id');
        if (!empty($last_id)) {
            $theme_path_home = 'themes.' . config('constant.THEME_NAME') . '.content.home';

            $posts = Post::with('content')

            // ->inRandomOrder()
                ->orderBy('id', 'ASC')
                ->limit(config('constant.HOMEPAGE_POST_PAGINATION'))
                ->paginate(config('constant.HOMEPAGE_POST_PAGINATION'), ['id', 'post_title', 'slug', 'published_at', 'updated_at']);

            //SEO FOR HOME PAGE
            SEOTools::setTitle($settings->home_title . ' | Page' . $posts->currentPage());
            SEOTools::setDescription($settings->home_page_description);
            SEOTools::opengraph()->setUrl(URL::current());
            SEOTools::setCanonical(URL::current());
            SEOTools::opengraph()->addProperty('type', 'articles');
            SEOTools::jsonLd()->addImage(asset('themes/DevBlog/assets/images/profile.png'));

            //SEO FOR HOME PAGE END
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
        $sitemap_letter     = $sitemap;
        $theme_path_sitemap = 'themes.' . config('constant.THEME_NAME') . '.content.sitemap';

        $sitemap = Post::published()->where("slug", "like", "$sitemap%")->paginate(config('constant.SITEMAP_PAGE_PAGINATION'));

        return view($theme_path_sitemap, [
            'sitemap'        => $sitemap,
            'settings'       => $settings,
            'sitemap_letter' => $sitemap_letter,
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
