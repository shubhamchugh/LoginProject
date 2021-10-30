<?php

namespace App\Http\Controllers\Backend\Fetch;

use App\Models\Post;
use App\Models\FakeUser;
use App\Models\SourceUrl;
use App\Models\PostContent;
use Illuminate\Http\Request;
use App\Models\ScrapingFailed;
use Spatie\Browsershot\Browsershot;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AskScraperController extends Controller
{
    public function AskScraper(Request $request)
    {

        $start  = (!empty($request->start)) ? $request->start : 0;
        $end    = (!empty($request->end)) ? $request->end : 999999999999999999;
        $refKey = (!empty($request->refKey)) ? $request->refKey : null;

        if (empty($refKey)) {
            dd("Please Enter '?&refKey=HereValue' <br>
            FullURL Example: http://domain.com/scrape/WithoutImage?&refKey=loginspy&start=1&end=100");
        }
        $totalFakeUser = FakeUser::count();
        if (empty($totalFakeUser)) {
            dd("Please Get Some Fake Users before Scrape Post Please  Help: 'example.com/insert?userCount=Value'");
        }

        $source_url = SourceUrl::where('is_scraped', 0)->whereBetween('id', [$start, $end])->orderBy('id', 'ASC')->first();

        if (!empty($source_url->url)) {
            //is_scraped Updated in database before insert
            $source_url->update(['is_scraped' => 1]);

            //duplicate check in database before insert

            $duplicate_check = Post::where('source_url', $source_url->url)->first();
            if (empty($duplicate_check)) {
                echo "$source_url->url";

                $response = Browsershot::url($source_url->url)->windowSize(1000, 1000)->waitUntilNetworkIdle(false)->userAgent('Mozilla / 5.0 (compatible; MSIE 7.0; Windows; U; Windows NT 10.0; Trident / 4.0)')->evaluate("document.documentElement.outerHTML");
                // $response = Browsershot::url($source_url->url)->base64Screenshot();

                echo $response;

                $pokemon_doc = new \DOMDocument();
                libxml_use_internal_errors(true); //disable libxml errors

                $pokemon_doc->loadHTML($response);
                libxml_clear_errors(); //remove errors for yucky html

                $pokemon_doc->preserveWhiteSpace = false;
                $pokemon_doc->saveHTML();

                $pokemon_xpath = new \DOMXPath($pokemon_doc);

                //get all the data with an id
                $titles      = $pokemon_xpath->query('/html/body/div[2]/div/main/div/div[1]/section/div/div[3]/div/div/div[*]/div[2]/a');
                $decs        = $pokemon_xpath->query('/html/body/div[2]/div/main/div/div[1]/section/div/div[3]/div/div/div[*]/div[3]/div/p');
                $urls        = $pokemon_xpath->query('/html/body/div[2]/div/main/div/div[1]/section/div/div[3]/div/div/div[*]/div[2]/a/@href');
                $posts_title = $pokemon_xpath->query('/html/body/div[1]/div/header/div/div[2]/div/div/form/input[5]/@value');

                if (1 == $posts_title->length) {
                    foreach ($posts_title as $post_title) {
                        $post_name[] = $post_title->nodeValue;
                    }
                    echo "<pre>";
                    print_r($post_name);

                    foreach ($titles as $title) {
                        $result_title2[] = $title->nodeValue;
                    }
                    echo "<pre>";
                    print_r($result_title2);
                    foreach ($decs as $dec) {
                        $result_dec[] = $dec->nodeValue;
                    }
                    echo "<pre>";
                    print_r($result_dec);

                    foreach ($urls as $url) {
                        $result_url[] = $url->nodeValue;
                    }

                    print_r($result_url);
                } else {

                    ScrapingFailed::create([
                        'source_url' => $source_url->url,
                        'error'      => '404 Not Found',
                    ]);

                    die("404 Data Not Found");
                }

                $post_name = str_replace('Login', ' ', $post_name[0]);

                $startdate = strtotime("2021-3-01 00:00:00");
                $enddate   = strtotime("2021-5-31 23:59:59");

                $randomDate = date("Y-m-d H:i:s", mt_rand($startdate, $enddate));

                $postStore = Post::create([
                    'is_content'   => '1',
                    'post_title'   => $post_name,
                    'source_url'   => $source_url->url,
                    'post_ref'     => $refKey,
                    'fake_user_id' => mt_rand(1, $totalFakeUser),
                    'published_at' => $randomDate,
                ]);

                for ($i = 0; $i < $urls->length; $i++) {

                    PostContent::create([
                        'post_id'       => $postStore->id,
                        'fake_user_id'  => mt_rand(1, $totalFakeUser),
                        'content_title' => $result_title2[$i],
                        'content_url'   => $result_url[$i],
                        'content_dec'   => $result_dec[$i],
                        'is_image'      => 0,
                    ]);
                }

            } else {
                ScrapingFailed::create([
                    'source_url' => $source_url->url,
                    'error'      => 'Duplicate Removed From DataBase Id:' . $source_url->id,
                ]);
            }

            die("scraped success");

        } else {
            die("No Record Found Please Stop Scraping");
        }
    }
}
