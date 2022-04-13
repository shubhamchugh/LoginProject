<?php

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CacheClearController;
use App\Http\Controllers\ResetDatabaseController;
use App\Http\Controllers\SqlDataUpdateController;
use App\Http\Controllers\SearchIndexingController;
use App\Http\Controllers\UpgradeSoftwareController;
use App\Http\Controllers\NotWorkingIpCheckController;
use App\Http\Controllers\CreateWordPressPostController;
use App\Http\Controllers\Frontend\StaticPageController;
use App\Http\Controllers\Backend\Update\DedicatedColumnUpdateController;
use App\Http\Controllers\Backend\Update\CountCreatePostContentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('upgrade', UpgradeSoftwareController::class)->name('upgrade');

if (config('constant.RESET_SCRAPING')) {
    //Update Existing Post Content
    Route::get('is_google_results_reset', [ResetDatabaseController::class, 'is_google_results_reset']);
    Route::get('is_bing_video_reset', [ResetDatabaseController::class, 'is_bing_video_reset']);
    Route::get('is_bing_images_reset', [ResetDatabaseController::class, 'is_bing_images_reset']);
    Route::get('is_thumbnail_images_reset', [ResetDatabaseController::class, 'is_thumbnail_images_reset']);
    Route::get('is_bing_results_reset', [ResetDatabaseController::class, 'is_bing_results_reset']);
    Route::get('is_scraped_reset', [ResetDatabaseController::class, 'is_scraped_reset']);

}

Route::get('count-create-post-content', CountCreatePostContentController::class)->name('count_create');

//cache clear
Route::get('clear', [CacheClearController::class, 'clear'])->name('clear');

Route::get('google-index', [SearchIndexingController::class, 'google_indexing'])->name('google-index');
Route::get('bing-index', [SearchIndexingController::class, 'bing_indexing'])->name('bing-index');
Route::get('wordpress-post-create', [CreateWordPressPostController::class, 'create']);

Route::get('settings', [SettingsController::class, 'show'])->name('settings.show');
Route::post('settings/update', [SettingsController::class, 'update'])->name('settings.update');
Route::post('settings/image/update', [SettingsController::class, 'imageUpdate'])->name('settings.image.update');

Route::get('sql-update', [SqlDataUpdateController::class, 'updateSql'])->name('sql-update');
Route::get('ip-update', [NotWorkingIpCheckController::class, 'check_ip'])->name('ip-update');
Route::get('is_bing_results', [DedicatedColumnUpdateController::class, 'is_bing_results'])->name('is_bing_results.update');
Route::get('is_thumbnail_images', [DedicatedColumnUpdateController::class, 'is_thumbnail_images'])->name('is_thumbnail_images.update');
Route::get('is_bing_images', [DedicatedColumnUpdateController::class, 'is_bing_images'])->name('is_bing_images.update');
Route::get('is_bing_news', [DedicatedColumnUpdateController::class, 'is_bing_news'])->name('is_bing_news.update');
Route::get('is_bing_video', [DedicatedColumnUpdateController::class, 'is_bing_video'])->name('is_bing_video.update');
Route::get('is_google_results', [DedicatedColumnUpdateController::class, 'is_google_results'])->name('is_google_results.update');

//sitemap
Route::get('createsitemap', function () {
    ini_set('memory_limit', '-1');
    // create new sitemap object
    $sitemap = App::make('sitemap');

    // get all products from db (or wherever you store them)
    $products = DB::table('posts')->orderBy('created_at', 'desc')->get();

    // counters
    $counter        = 0;
    $sitemapCounter = 0;

    // add every product to multiple sitemaps with one sitemap index
    foreach ($products as $p) {
        if (50000 == $counter) {
            // generate new sitemap file
            $sitemap->store('xml', 'sitemap-' . $sitemapCounter);
            // add the file to the sitemaps array
            $sitemap->addSitemap(url('sitemap-' . $sitemapCounter . '.xml'));
            // reset items array (clear memory)
            $sitemap->model->resetItems();
            // reset the counter
            $counter = 0;
            // count generated sitemap
            $sitemapCounter++;
        }

        $slug = (!empty(config('constant.POST_SLUG'))) ? '/' . config('constant.POST_SLUG') : config('constant.POST_SLUG');

        // add product to items array
        $sitemap->add(url($slug . '/' . $p->slug), $p->published_at, '1.0', 'Weekly');
        // count number of elements
        $counter++;
    }

    // you need to check for unused items
    if (!empty($sitemap->model->getItems())) {
        // generate sitemap with last items
        $sitemap->store('xml', 'sitemap-' . $sitemapCounter);
        // add sitemap to sitemaps array
        $sitemap->addSitemap(url('sitemap-' . $sitemapCounter . '.xml'));
        // reset items array
        $sitemap->model->resetItems();
    }

    // generate new sitemapindex that will contain all generated sitemaps above
    $sitemap->store('sitemapindex', 'sitemap');
});

# ########################################################## #
# ##################### API & Scraping settings Route ##################### #
# ########################################################## #
// API Controller ResetCount
Route::get('reset', [
    'uses' => 'App\Http\Controllers\ResetCountCheckController@reset',
    'as'   => 'reset.index',
]);

// API Controller scrape Data
Route::get('hit', [
    'uses' => 'App\Http\Controllers\ApiDataScrapeController@hit',
    'as'   => 'hit.index',
]);

// Insert Fake database
Route::get('insert', [
    'uses' => 'App\Http\Controllers\FakeDataInsert@insert',
    'as'   => 'insert.index',
]);

// API Controller Links
Route::get('api/{api} ', [
    'uses' => 'App\Http\Controllers\ApiController@show',
    'as'   => 'api.show',
]);

# ########################################################## #
# ##################### Frontend Route ##################### #
# ########################################################## #

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

//search page
Route::get('/search', [
    'uses' => 'App\Http\Controllers\Frontend\HomeController@search',
    'as'   => 'search.show',
]);

//Frontend Product Page
Route::get(config('constant.POST_SLUG') . '/{post}', [
    'uses' => 'App\Http\Controllers\Frontend\PostController@show',
    'as'   => 'post.show',
])->middleware('checkdate');

//cid Page
Route::get(config('constant.CID') . '/{id}', [
    'uses' => 'App\Http\Controllers\Frontend\PostController@cid',
    'as'   => 'post.cid',
]);

if (config('constant.Update_Post_Link')) {
//Update Existing Post Content
    Route::get('/update-post-content/{post_content_id}/{keyword}', [
        'uses' => 'App\Http\Controllers\Backend\Update\AutoUpdatePostController@update_existing',
        'as'   => 'post_content.update_existing',
    ]);
}

//Frontend Home Page
Route::get('/', [
    'uses' => 'App\Http\Controllers\Frontend\HomeController@homeList',
    'as'   => 'index',
]);

///Frontend Home Page
// Route::get('/page/{page} ', [
//     'uses' => 'App\Http\Controllers\Frontend\PageController@show',
//     'as'   => 'page.show',
// ]);

Route::get('/sitemap/{sitemap}', [
    'uses' => 'App\Http\Controllers\Frontend\HomeController@sitemap',
    'as'   => 'sitemap.show',
]);

Route::get('/docs/{page}', StaticPageController::class)->name('docs')->where('page', 'about|contact|terms|privacy|dmca');

# ######################################################### #
# ##################### Backend Route ##################### #
# ######################################################### #

// Back End Page Routes //
Route::resource('/content', 'App\Http\Controllers\Backend\PageManagement\PageController');

Route::put('/content/restore/{content}', [
    'uses' => 'App\Http\Controllers\Backend\PageManagement\PageController@restore',
    'as'   => 'content.restore',
]);
Route::delete('/content/force-destroy/{content}', [
    'uses' => 'App\Http\Controllers\Backend\PageManagement\PageController@forceDestroy',
    'as'   => 'content.force-destroy',
]);

// Back End Post Routes //
Route::resource('logins', 'App\Http\Controllers\Backend\PostManagement\PostController');

Route::get('/postcontent/add/{post_id} ', [
    'uses' => 'App\Http\Controllers\Backend\PostManagement\PostContentController@AddPostContent',
    'as'   => 'postcontent.add',
]);

Route::resource('postcontent', 'App\Http\Controllers\Backend\PostManagement\PostContentController');

Route::put('/logins/restore/{logins}', [
    'uses' => 'App\Http\Controllers\Backend\PostManagement\PostController@restore',
    'as'   => 'logins.restore',
]);

Route::delete('/logins/force-destroy/{logins}', [
    'uses' => 'App\Http\Controllers\Backend\PostManagement\PostController@forceDestroy',
    'as'   => 'logins.force-destroy',
]);

// Back End user Routes //
Route::resource('user', 'App\Http\Controllers\Backend\User\UsersController');

Route::get('user/confirm/{users}', [
    'uses' => 'App\Http\Controllers\Backend\User\UsersController@confirm',
    'as'   => 'user.confirm',
]);

# ######################################################### #
# ##################### scraping Route ##################### #
# ######################################################### #

Route::resource('scraping', 'App\Http\Controllers\Backend\Settings\ScrapingPageController');

Route::get('scrape/bing-serp', [
    'uses' => 'App\Http\Controllers\Backend\scrape\BingSerpScrapeController@bingScrape',
    'as'   => 'scrape.bing',
]);

Route::get('scrape/faq', [
    'uses' => 'App\Http\Controllers\Backend\Scrape\FaqScrapeController@FaqScrape',
    'as'   => 'scrape.faq',
]);

Route::get('related/{keyword}', [
    'uses' => 'App\Http\Controllers\Backend\Update\RelatedKeywordUpdateController@relatedKeywords',
    'as'   => 'scrape.keyword.update',
]);
