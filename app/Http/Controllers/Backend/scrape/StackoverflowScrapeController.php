<?php

namespace App\Http\Controllers\Backend\scrape;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\FakeUser;
use App\Models\SourceUrl;
use App\Models\PostContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class StackoverflowScrapeController extends Controller
{
    public function stackScrape(Request $request)
    {
        $count          = (!empty($request->count)) ? $request->count : 20;
        $start          = (!empty($request->start)) ? $request->start : 1;
        $end            = (!empty($request->end)) ? $request->end : 999999999999999999;
        $domain         = (!empty($request->domain)) ? $request->domain : 'https://stackoverflow.com';
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

        $totalFakeUser = FakeUser::count();

        if (empty($totalFakeUser)) {
            dd("Please Get Some Fake Users before Scrape Post Please  Help: 'example.com/insert?userCount=Value'");
        }

        $slug = SourceUrl::where('is_scraped', $request->where)->whereBetween('id', [$start, $end])->orderBy('id', 'ASC')->first();
        if (!empty($slug)) {
            $slug->update([
                'is_scraped' => 'scraping_start',
            ]);

            $url_to_scrape = $slug->value;
            echo $url_to_scrape;

            $duplicate_check = Post::where('source_value', $url_to_scrape)->first();

            if (!empty($duplicate_check)) {
                $slug->update([
                    'is_scraped' => 'duplicate_found',
                ]);
            } else {

                $response = Http::get($url_to_scrape);
                $response = $response->body();

                $dom_document = new \DOMDocument();
                libxml_use_internal_errors(true); //disable libxml errors

                $dom_document->loadHTML($response);
                libxml_clear_errors(); //remove errors for yucky html

                $dom_document->preserveWhiteSpace = false;
                $dom_document->saveHTML();

                $document_xpath = new \DOMXPath($dom_document);

                //News Xpath to get Data
                $questions = $document_xpath->query('//h1/a[@class="question-hyperlink"]');
                $answers   = $document_xpath->query('//div[@class="s-prose js-post-body"]');
                $tags      = $document_xpath->query('//a[@rel="tag"]');

                foreach ($questions as $question) {
                    $stack_q[] = $question->nodeValue;
                }
                echo "<pre>";
                print_r($stack_q);

                foreach ($answers as $answer) {
                    $stack_a[] = $answer->c14n();
                }
                print_r($stack_a);

                if (!empty($tags)) {
                    foreach ($tags as $tag) {
                        $tag_list[] = $tag->nodeValue;
                    }
                    $tag_list = (!empty($tag_list)) ? $tag_list : "noTag";
                } else {
                    $tag_list = (!empty($tag_list)) ? $tag_list : "noTag";
                }

                $stack_q = (!empty($stack_q)) ? $stack_q[0] : null;

                if (empty($stack_q)) {
                    echo "404 Found or Site Block the ip";
                    $slug->update([
                        'is_scraped' => 'blocked_or_404_Found',
                    ]);
                    dd();
                }

                $startdate = strtotime("2021-3-01 00:00:00");

                $randomDate = date("Y-m-d H:i:s", mt_rand($startdate, strtotime(Carbon::now())));

                $postStore = Post::create([
                    'is_content'   => '1',
                    'post_title'   => $stack_q,
                    'source_value' => $url_to_scrape,
                    'fake_user_id' => mt_rand(1, $totalFakeUser),
                    'published_at' => $randomDate,
                ]);

                $postStore->tag($tag_list);

                $slug->update([
                    'is_scraped' => 'title_scraped',
                ]);
                $stack_a = (!empty($stack_a)) ? $stack_a : null;

                if (!empty($stack_a)) {

                    for ($i = 0; $i < count($stack_a); $i++) {

                        PostContent::create([
                            'post_id'      => $postStore->id,
                            'fake_user_id' => mt_rand(1, $totalFakeUser),
                            'content_dec'  => $stack_a[$i],
                        ]);
                    }
                    $slug->update([
                        'is_scraped' => 'success',
                    ]);
                } else {
                    echo "Answers Not Found";
                    $slug->update([
                        'is_scraped' => 'answers_not_found',
                    ]);
                    dd();
                }
            }
        } else {
            echo "No Record Found In DataBase to Scrape";
        }

    }
}
