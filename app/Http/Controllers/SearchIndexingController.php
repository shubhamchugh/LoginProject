<?php

namespace App\Http\Controllers;

use App\Models\IndexResult;
use App\Models\Post;
use Carbon\Carbon;
use Google;
use Google_Service_Indexing;
use Google_Service_Indexing_UrlNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchIndexingController extends Controller
{

    public function google_indexing(Request $request)
    {
        if (empty(file_get_contents(storage_path('Google_account_file.json')))) {
            dd("Please Add Google Index Api to Google_account_file.json for Send Request to Google");
        }
        $urls        = array();
        $batch_count = (!empty($request->batch)) ? $request->batch : 10;

        try {
            $googleClient = new Google\Client();

            // Add here location to the JSON key file that you created and downloaded earlier.
            $googleClient->setAuthConfig(storage_path('Google_account_file.json'));
            $googleClient->setScopes(Google_Service_Indexing::INDEXING);
            $googleClient->setUseBatch(true);

            $service = new Google_Service_Indexing($googleClient);
            $batch   = $service->createBatch();

            $postBody = new Google_Service_Indexing_UrlNotification();

            $google_posts = Post::where('google_index', '0')->orderBy('id', 'asc')->limit($batch_count)->get();

            if (!empty($google_posts->count())) {
                $slug = (!empty(config('constant.POST_SLUG'))) ? '/' . config('constant.POST_SLUG') : config('constant.POST_SLUG');
                // Use URL_UPDATED for new or updated pages.
                // Use URL_DELETED for deleted pages.
                foreach ($google_posts as $google_post) {
                    $urls[url($slug . '/' . $google_post->slug)] = 'URL_UPDATED';
                    // testing urls
                    // $url        = 'https://apkbilli.com' . ($slug . '/' . $google_post->slug);
                    // $urls[$url] = 'URL_UPDATED';
                    Post::where('id', $google_post->id)->update(['google_index' => 1]);
                }

                foreach ($urls as $url => $type) {
                    $postBody->setUrl($url);
                    $postBody->setType($type);
                    $batch->add($service->urlNotifications->publish($postBody));
                }
                $results = $batch->execute();

                // If you want to loop trough the results of the each page, you can do it with
                // the example below.

                foreach ($results as $result) {
                    echo "<pre>";
                    echo $result->urlNotificationMetadata->latestUpdate["notifyTime"] . "\n";
                    echo $result->urlNotificationMetadata->latestUpdate["type"] . "\n";
                    echo $result->urlNotificationMetadata->latestUpdate["url"] . "\n";

                    IndexResult::create([
                        'search_engine' => 'google',
                        'notifyTime'    => $result->urlNotificationMetadata->latestUpdate["notifyTime"],
                        'type'          => $result->urlNotificationMetadata->latestUpdate["type"],
                        'url'           => $result->urlNotificationMetadata->latestUpdate["url"],
                    ]);

                }

            } else {
                echo "No Url found For Google to send Indexing request<br>";
            }
        } catch (\Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

    }

    public function bing_indexing(Request $request)
    {
        try {
            if (empty(config('constant.Bing_API_Key'))) {
                dd("Please Add Bing Index Api to Send Request to Bing");
            }
            $batch_count = (!empty($request->batch)) ? $request->batch : 10;

            $bing_posts = Post::where('bing_index', '0')->orderBy('id', 'asc')->limit($batch_count)->get();
            if (!empty($bing_posts->count())) {
                $slug = (!empty(config('constant.POST_SLUG'))) ? '/' . config('constant.POST_SLUG') : config('constant.POST_SLUG');

                foreach ($bing_posts as $bing_post) {
                    $bing         = 'https://www.bing.com/indexnow?url=' . url($slug . '/' . $bing_post->slug) . '&key=' . config('constant.Bing_API_Key');
                    $bing_request = Http::timeout(200)->get($bing);

                    Post::where('id', $bing_post->id)->update(['bing_index' => 1]);

                    IndexResult::create([
                        'search_engine' => 'Bing',
                        'notifyTime'    => Carbon::now(),
                        'type'          => 'URL_UPDATED',
                        'url'           => url($slug . '/' . $bing_post->slug),
                        'status_code'   => $bing_request->getStatusCode(),
                    ]);
                }
            } else {
                echo "No url found in Bing list send Indexing request<br>";
            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
