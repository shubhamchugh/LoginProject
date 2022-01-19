<?php

namespace App\Http\Controllers\Backend\scrape;

use App\Http\Controllers\Controller;
use App\Models\FakeUser;
use App\Models\Post;
use App\Models\PostContent;
use App\Models\PostContentExtension;
use App\Models\ScrapingFailed;
use App\Models\SourceUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Spatie\Browsershot\Browsershot;

class BingSerpScrapeController extends Controller
{
    public function bingScrape(Request $request)
    {
        $count          = (!empty($request->count)) ? $request->count : 20;
        $start          = (!empty($request->start)) ? $request->start : 0;
        $end            = (!empty($request->end)) ? $request->end : 999999999999999999;
        $refKey         = (!empty($request->refKey)) ? $request->refKey : 'slave';
        $scrapingStatus = (!empty($request->ScrapingStatus)) ? $request->ScrapingStatus : false;

        if (empty($request->where)) {
            dd("Please Add &where=value");
        }

        if (!empty($scrapingStatus)) {
            $unique = SourceUrl::select('is_scraped')->distinct()->get();
            foreach ($unique as $key => $value) {
                $count = SourceUrl::where('is_scraped', $value->is_scraped)->count();
                echo "$value->is_scraped : $count<br>";
            }
            dd();
        }

        if (empty($refKey)) {
            dd("Please Enter '?&refKey=HereValue' <br>
            FullURL Example: http://domain.com/scrape/bing-serp?&refKey=loginspy&start=1&end=100");
        }

        $totalFakeUser = FakeUser::count();

        if (empty($totalFakeUser)) {
            dd("Please Get Some Fake Users before Scrape Post Please  Help: 'example.com/insert?userCount=Value'");
        }

        $keyword = SourceUrl::where('is_scraped', $request->where)->whereBetween('id', [$start, $end])->orderBy('id', 'ASC')->first();

        if (!empty($keyword->value)) {
            //is_scraped Updated in database before insert
            $keyword->update(['is_scraped' => 'processing']);
            $duplicate_check = Post::where('source_value', $keyword->value)->first();

            if (empty($duplicate_check)) {
                $keyword->update(['is_scraped' => 'scraping_start']);
                echo "Keyword: $keyword->value <br>";

                $url = 'https://www.bing.com/search?q=' . str_replace(' ', '+', $keyword->value) . '&count=' . $count;
                echo "Url: $url<br>";
                $response = Browsershot::url($url)->windowSize(1000, 1000)->waitUntilNetworkIdle()->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582')->evaluate("document.documentElement.outerHTML");

                $dom_document = new \DOMDocument();
                libxml_use_internal_errors(true); //disable libxml errors

                $dom_document->loadHTML($response);
                libxml_clear_errors(); //remove errors for yucky html

                $dom_document->preserveWhiteSpace = false;
                $dom_document->saveHTML();

                $document_xpath = new \DOMXPath($dom_document);

                //get all the data of top results
                $titles      = $document_xpath->query('//li[@class="b_algo"]/h2/a');
                $decs        = $document_xpath->query('//div[@class="b_caption"]/p');
                $urls        = $document_xpath->query('//li[@class="b_algo"]/h2/a/@href');
                $posts_title = $document_xpath->query('//*[@id="sb_form_q"]/@value');

                //related keywords xpath
                $related_keywords = $document_xpath->query('//ul[@class="b_vList b_divsec"]/li/a');

                //faq xpath
                $faq_question = $document_xpath->query('//div[@data-exptype="ModuleExpansionHead"]/div');
                $faq_answer   = $document_xpath->query('//div[@class="rwrl rwrl_small rwrl_padref"]');

                if (1 == $posts_title->length) {
                    foreach ($posts_title as $post_title) {
                        $post_name[] = $post_title->nodeValue;
                    }
                    echo "Main keyword: ";
                    echo "<pre>";
                    print_r($post_name);

                    foreach ($titles as $title) {
                        $result_title2[] = $title->nodeValue;
                    }

                    echo "result titles: ";
                    print_r($result_title2);
                    foreach ($decs as $dec) {
                        $result_dec[] = $dec->nodeValue;
                    }
                    echo "result descriptions: ";
                    print_r($result_dec);

                    foreach ($urls as $url) {
                        $result_url[] = $url->nodeValue;
                    }
                    echo "result urls";
                    print_r($result_url);

                } else {
                    $keyword->update(['is_scraped' => 'top_results_notFound']);

                    ScrapingFailed::create([
                        'source_value' => $keyword->value,
                        'error'        => '404 Not Found',
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
                    'source_value' => $keyword->value,
                    'post_ref'     => $refKey,
                    'fake_user_id' => mt_rand(1, $totalFakeUser),
                    'published_at' => $randomDate,
                ]);
                $keyword->update(['is_scraped' => 'post_created']);

                //Faq Insert in PostContentExtensions
                if (!empty($faq_question) && $faq_question->length >= 1) {

                    foreach ($faq_question as $question) {
                        $questions['question'][] = (!empty($question->nodeValue)) ? $question->nodeValue : 'NotFound';
                    }
                }
                if (!empty($faq_answer) && $faq_answer->length >= 1) {
                    foreach ($faq_answer as $answer) {
                        $answers['answers'][] = (!empty($answer->nodeValue)) ? $answer->nodeValue : 'NotFound';
                    }
                }
                if (!empty($questions) && !empty($answers)) {
                    $faq = array_merge($questions, $answers);
                    echo "faq";
                    print_r($faq);
                    $faq = (!empty($faq)) ? serialize($faq) : serialize([]);
                } else {
                    $faq = (!empty($faq)) ? serialize($faq) : serialize([]);
                }

                $post_content_extension = PostContentExtension::create([
                    'post_id' => $postStore->id,
                    'faq'     => $faq,

                ]);
                $keyword->update(['is_scraped' => 'faq_updated']);
                //Top Results Insert in PostContent
                for ($i = 0; $i < $urls->length; $i++) {

                    PostContent::create([
                        'post_id'       => $postStore->id,
                        'fake_user_id'  => mt_rand(1, $totalFakeUser),
                        'content_title' => (!empty($result_title2[$i])) ? $result_title2[$i] : 'Data Not Available',
                        'content_url'   => (!empty($result_url[$i])) ? $result_url[$i] : 'Data Not Available',
                        'content_dec'   => (!empty($result_dec[$i])) ? $result_dec[$i] : 'Data Not Available',
                        'is_image'      => 0,
                    ]);
                }
                $keyword->update(['is_scraped' => 'post_content_update']);

                if (!empty($related_keywords) && $related_keywords->length >= 1) {

                    foreach ($related_keywords as $related_keyword) {
                        $related_k[] = $related_keyword->nodeValue;
                    }

                    echo "Related keywords: ";
                    print_r($related_k);

                    $related_k = (!empty($related_k)) ? serialize($related_k) : serialize([]);

                    $post_content_extension->update([
                        'related_keywords' => $related_k,
                    ]);

                    $keyword->update(['is_scraped' => 'relatedKeywordsUpdated']);
                } else {
                    $keyword->update(['is_scraped' => 'relatedKeywordsNotUpdated']);
                }

                $newsUrl  = 'https://www.bing.com/news/search?q=' . str_replace(' ', '+', $keyword->value);
                $newsHtml = http::get($newsUrl);
                $newsHtml = $newsHtml->body();

                $dom_document_news = new \DOMDocument();
                libxml_use_internal_errors(true); //disable libxml errors

                $dom_document_news->loadHTML($newsHtml);
                libxml_clear_errors(); //remove errors for yucky html

                $dom_document_news->preserveWhiteSpace = false;
                $dom_document_news->saveHTML();

                $document_xpath_news = new \DOMXPath($dom_document_news);

                //News Xpath to get Data
                $news_titles       = $document_xpath_news->query('//a[@class="title"]');
                $news_descriptions = $document_xpath_news->query('//div[@class="snippet"]');

                if (!empty($newsHtml) && (1 <= $news_titles->length)) {
                    foreach ($news_titles as $news_title) {
                        $news_t['title'][] = (!empty($news_title->nodeValue)) ? $news_title->nodeValue : "NotFound";
                    }
                    foreach ($news_descriptions as $news_description) {
                        $news_d['description'][] = (!empty($news_description->nodeValue)) ? $news_description->nodeValue : 'NotFound';
                    }
                    if (!empty($news_t) && !empty($news_d)) {
                        $news = array_merge($news_t, $news_d);
                        echo "news";
                        print_r($news);
                        $news = (!empty($news)) ? serialize($news) : serialize([]);
                    } else {
                        $news = (!empty($news)) ? serialize($news) : serialize([]);
                    }

                    $post_content_extension->update([
                        'news' => $news,
                    ]);

                    $keyword->update(['is_scraped' => 'newsUpdated']);
                } else {
                    $keyword->update(['is_scraped' => 'FailAtFetchingNews']);
                }

                $videoUrl  = 'https://www.bing.com/videos/search?q=' . str_replace(' ', '+', $keyword->value) . '&qft=+filterui%3amsite-youtube.com';
                $videoHtml = http::get($videoUrl);
                $videoHtml = $videoHtml->body();

                $dom_document_video = new \DOMDocument();
                libxml_use_internal_errors(true); //disable libxml errors

                $dom_document_video->loadHTML($videoHtml);
                libxml_clear_errors(); //remove errors for yucky html

                $dom_document_video->preserveWhiteSpace = false;
                $dom_document_video->saveHTML();

                $document_xpath_video = new \DOMXPath($dom_document_video);

                //News Xpath to get Data
                $videos_json = $document_xpath_video->query('//div[@class="vrhdata"]/@vrhm');

                if (!empty($videoHtml) && (1 <= $videos_json->length)) {
                    foreach ($videos_json as $video_json) {
                        $video_j[] = (!empty($video_json->nodeValue)) ? $video_json->nodeValue : "NotFound";
                    }
                    echo "videos: ";
                    print_r($video_j);
                    $video_j = (!empty($video_j)) ? serialize($video_j) : serialize([]);

                    $post_content_extension->update([
                        'videos' => $video_j,

                    ]);
                    $keyword->update(['is_scraped' => 'videoUpdated']);
                } else {
                    $keyword->update(['is_scraped' => 'failAtVideoUpdated']);
                }

                $keyword->update(['is_scraped' => 'success']);
                echo "Top $urls->length result scraped";

            } else {
                $keyword->update(['is_scraped' => 'duplicate']);
                ScrapingFailed::create([
                    'source_value' => $keyword->value,
                    'error'        => 'Duplicate Removed From DataBase Id:' . $keyword->id,
                ]);
                dd("Duplicate Record Found");
            }

        } else {
            dd("No Keywords Found. Please Add Keywords To scrape or STOP Scraping");
        }

    }
}
