<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Helpers\GeneralSettings;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use App\Http\Controllers\Backend\Update\AutoUpdatePostController;

class PostController extends Controller
{
    public function show(Post $post, GeneralSettings $settings)
    {
        $postContent = $post->content->toArray();
        if (empty($postContent)) {
            return response(404);
        }
        //if Post Content not found then first get the post content and refresh the page
        if (empty($postContent)) {
            if (config('constant.Auto_Update_And_create')) {
                AutoUpdatePostController::update_and_create($post->id, $post->post_title);
                return redirect()->route('post.show', ['post' => $post->slug]);
            }
        }

        $postContent             = $postContent[mt_rand(0, (count($postContent) - 1))];
        $author                  = $postContent['fake_user_id'];
        $updated_at              = Carbon::parse($postContent['updated_at']);
        $bing_related_keywords   = (!empty($postContent['bing_related_keywords'])) ? unserialize($postContent['bing_related_keywords']) : array();
        $google_related_keywords = (!empty($postContent['bing_related_keywords'])) ? unserialize($postContent['google_related_keywords']) : array();
        $bing_news               = (!empty($postContent['bing_news'])) ? unserialize($postContent['bing_news']) : array();
        $bing_videos             = (!empty($postContent['bing_videos'])) ? unserialize($postContent['bing_videos']) : array();
        $bing_images             = (!empty($postContent['bing_images'])) ? unserialize($postContent['bing_images']) : array();
        $bing_search_result      = (!empty($postContent['bing_search_result'])) ? unserialize($postContent['bing_search_result']) : array();
        $bing_paa                = (!empty($postContent['bing_paa'])) ? unserialize($postContent['bing_paa']) : array();
        $bing_rich_snippet       = (!empty($postContent['bing_rich_snippet'])) ? unserialize($postContent['bing_rich_snippet']) : array();
        $bing_slider_faq         = (!empty($postContent['bing_slider_faq'])) ? unserialize($postContent['bing_slider_faq']) : array();
        $bing_pop_faq            = (!empty($postContent['bing_pop_faq'])) ? unserialize($postContent['bing_pop_faq']) : array();
        $bing_tab_faq            = (!empty($postContent['bing_tab_faq'])) ? unserialize($postContent['bing_tab_faq']) : array();
        $google_faq              = (!empty($postContent['google_faq'])) ? unserialize($postContent['google_faq']) : array();
        $google_rich_snippet     = (!empty($postContent['google_rich_snippet'])) ? unserialize($postContent['google_rich_snippet']) : array();
        $google_search_result    = (!empty($postContent['google_search_result'])) ? unserialize($postContent['google_search_result']) : array();
        $indexedArray            = array("new", "trend", "hot", "top", "best", "tip", "great", "recommended", "suggest", "worst", "excellent", "fabulous");
        $totalimages             = (!empty($bing_images['images'])) ? (count($bing_images['images']) - 1) : null;
        $totalvideos             = (!empty($bing_videos)) ? (count($bing_videos) - 1) : null;

        $theme_path_post = 'themes.' . config('constant.THEME_NAME') . '.content.post';

        //SEO FOR POST PAGE
        $SEO_dec  = (!empty($google_rich_snippet[0])) ? strip_tags($google_rich_snippet[0]) : $postContent['post_description'];
        $keywords = (!empty($google_related_keywords)) ? implode(", ", $google_related_keywords) : implode(", ", $bing_related_keywords);

        $seo_image = (!empty($bing_images['images'][mt_rand(0, $totalimages)])) ? $bing_images['images'][mt_rand(0, $totalimages)] : json_encode(['murl' => url('themes/DevBlog/assets/images/profile.png')]);

        $before_title = (!empty($settings->before_title)) ? $settings->before_title : "";
        $after_title  = (!empty($settings->after_title)) ? $settings->after_title : "";

        SEOTools::setTitle($before_title . ' ' . ucwords($post->post_title) . ' ' . $after_title);
        SEOTools::setDescription($SEO_dec);
        SEOTools::opengraph()->setUrl(URL::current());
        SEOMeta::addMeta('article:published_time', $updated_at->toW3CString(), 'property');
        SEOTools::setCanonical(URL::current());
        SEOTools::opengraph()->addProperty('type', 'articles');
        SEOTools::jsonLd()->addImage(json_decode($seo_image, true)['murl']);
        SEOMeta::setKeywords($keywords);
        //SEO END FOR POST PAGE

        return view($theme_path_post,
            [
                'post'                    => $post,
                'postContent'             => $postContent,
                'author'                  => $author,
                'updated_at'              => $updated_at,
                'bing_related_keywords'   => $bing_related_keywords,
                'google_related_keywords' => $google_related_keywords,
                'bing_news'               => $bing_news,
                'bing_videos'             => $bing_videos,
                'bing_images'             => $bing_images,
                'bing_search_result'      => $bing_search_result,
                'bing_paa'                => $bing_paa,
                'bing_rich_snippet'       => $bing_rich_snippet,
                'bing_slider_faq'         => $bing_slider_faq,
                'bing_pop_faq'            => $bing_pop_faq,
                'bing_tab_faq'            => $bing_tab_faq,
                'google_faq'              => $google_faq,
                'google_rich_snippet'     => $google_rich_snippet,
                'google_search_result'    => $google_search_result,
                'indexedArray'            => $indexedArray,
                'totalimages'             => $totalimages,
                'totalvideos'             => $totalvideos,
                'settings'                => $settings,
            ]);

    }

    public function cid(Request $request, GeneralSettings $settings)
    {
        $url            = $request->url;
        $title          = $request->title;
        $dec            = $request->dec;
        $slug           = $request->slug;
        $theme_path_cid = 'themes.' . config('constant.THEME_NAME') . '.content.cid';

        return view($theme_path_cid,
            [
                'url'      => $url,
                'title'    => $title,
                'dec'      => $dec,
                'slug'     => $slug,
                'settings' => $settings,
            ]);
    }
}
