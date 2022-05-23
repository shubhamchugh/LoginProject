<?php

namespace App\Http\Controllers\Backend\Scrape;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\FakeUser;
use App\Models\IpRecord;
use App\Models\SourceUrl;
use Illuminate\Http\Request;
use App\Models\ScrapingFailed;
use App\Models\JsonPostContent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class FaqScrapeController extends Controller
{
    public function FaqScrape(Request $request)
    {
        echo "<pre>";
        $count          = (!empty($request->count)) ? $request->count : 20;
        $start          = (!empty($request->start)) ? $request->start : 0;
        $end            = (!empty($request->end)) ? $request->end : 999999999999999999;
        $refKey         = (!empty($request->refKey)) ? $request->refKey : 'slave';
        $scrapingStatus = (!empty($request->ScrapingStatus)) ? $request->ScrapingStatus : false;
        $where_request  = (!empty($request->where)) ? $request->where : 'pending';

        if (!empty($scrapingStatus)) {
            $unique = SourceUrl::select('is_scraped')->distinct()->get();
            foreach ($unique as $key => $value) {
                $count = SourceUrl::where('is_scraped', $value->is_scraped)->count();
                echo "$value->is_scraped : $count<br>";
            }
            dd();
        }

        $ip = IpRecord::where('status', 'OK')->inRandomOrder()->first();
        if (empty($ip->ip_address)) {
            dd("Please Add New ip in DataBase to Scrape");
        }
        $ip->update([
            'status' => 'SCRAPING',
            'ERROR'  => request()->getHost(),
        ]);

        if (empty($refKey)) {
            dd("Please Enter '?&refKey=HereValue' <br>
            FullURL Example: http://domain.com/scrape/bing-serp?&refKey=loginspy&start=1&end=100");
        }

        $totalFakeUser = FakeUser::count();

        if (empty($totalFakeUser)) {
            dd("Please Get Some Fake Users before Scrape Post Please  Help: 'example.com/insert?userCount=Value'");
        }

        $keyword = SourceUrl::where('is_scraped', $where_request)->whereBetween('id', [$start, $end])->orderBy('id', 'ASC')->first();

        if (!empty($keyword->value)) {
            echo "keyword: $keyword->value<br>";
            //is_scraped Updated in database before insert
            $keyword->update(['is_scraped' => 'processing_start']);
            $duplicate_check = Post::where('source_value', $keyword->value)->first();

            if (empty($duplicate_check)) {
                $keyword->update(['is_scraped' => 'duplicate_check_pass']);
                $post = Post::create([
                    'post_title'   => $keyword->value,
                    'source_value' => $keyword->value,
                    'fake_user_id' => mt_rand(1, $totalFakeUser),
                    'published_at' => Carbon::today()->subDays(rand(0, 365)),
                ]);
                $keyword->update(['is_scraped' => 'post_create']);
                $post_content = JsonPostContent::create([
                    'post_id'      => $post->id,
                    'fake_user_id' => mt_rand(1, $totalFakeUser),
                ]);
                $keyword->update(['is_scraped' => 'fake_user_created']);

                // * try to save thumbnail_images in database start
                try {
                    $Bing_image = 'http://' . $ip->ip_address . ':3000/bing-thumb?url=https://www.' . config('constant.bing_url') . '/images/search?q=' . str_replace(' ', '+', $keyword->value) . '+' . config('constant.bing_query') . '&qft=+filterui:aspect-wide&first=1&tsc=ImageBasicHover';

                    $thumbnail = Http::timeout(90)->get($Bing_image)->body();

                    $thumbnail = (!empty($thumbnail)) ? $thumbnail : "default.jpg";

                    echo "<br>Thumbnail: <br>";
                    echo "$thumbnail<br>";

                    //Updating thumbnail_images in database
                    try {
                        $post_content->update([
                            'post_thumbnail' => $thumbnail,
                        ]);
                        $keyword->update(['is_scraped' => 'bing_thumbnail_images_updated']);
                    } catch (\Throwable $th) {
                        echo "Fail to store Bing thumbnail In database check: $Bing_image<br>";
                        $keyword->update(['is_scraped' => 'bing_thumbnail_images_update_fail']);
                    }

                } catch (\Throwable $th) {
                    echo "Something bad With thumbnail_images Please check: $Bing_image <br>";
                    $keyword->update(['is_scraped' => 'bing_thumbnail_images_hit_fail']);

                }
                //~ try to save images for bing_images end

                //* try to save images for bing_images start
                try {
                    $Bing_image_url = 'http://' . $ip->ip_address . ':3000/bing-images?url=https://www.' . config('constant.bing_url') . '/images/search?q=' . str_replace(' ', '+', $keyword->value) . '+' . config('constant.bing_query');
                    $Bing_image     = Http::timeout(90)->get($Bing_image_url)->body();
                    $Bing_image     = json_decode($Bing_image, true);

                    if (!empty($Bing_image['images'][0])) {
                        $images = (!empty($Bing_image)) ? $Bing_image : null;
                    } else {
                        $images = (!empty($Bing_image)) ? $Bing_image : null;
                    }

                    echo "<br>Images: <br>";
                    print_r($images);

                    //Updating images in database
                    try {
                        $post_content->update([
                            'bing_images' => $images['images'],
                        ]);

                        $keyword->update(['is_scraped' => 'bing_images_updated']);
                    } catch (\Throwable $th) {

                        echo "Fail to store Bing images In database check:$Bing_image_url<br>";
                        $keyword->update(['is_scraped' => 'bing_images_update_fail']);
                    }
                } catch (\Throwable $th) {

                    echo "Something bad With Bing images Please check: $Bing_image_url <br>";
                    $keyword->update(['is_scraped' => 'bing_images_hit_fail']);
                }
                //~ try to save images for bing_images end

                //* try to update New From bing News search start
                try {
                    $newsUrl   = 'http://' . $ip->ip_address . ':3000/bing-news?url=https://www.' . config('constant.bing_url') . '/news/search?q=' . str_replace(' ', '+', $keyword->value) . '+' . config('constant.bing_query');
                    $bing_news = Http::timeout(90)->get($newsUrl)->body();
                    $bing_news = json_decode($bing_news, true);

                    echo "<br>bing news<br>";
                    print_r($bing_news);

                    $bing_news = (!empty($bing_news)) ? $bing_news : null;

                    //Updating news in database
                    try {
                        $post_content->update([
                            'bing_news_title'       => $bing_news['title'],
                            'bing_news_description' => $bing_news['description'],

                        ]);
                        $keyword->update(['is_scraped' => 'bing_news_updated']);
                    } catch (\Throwable $th) {
                        echo "Fail to store Bing News In database check: $newsUrl<br>";
                        $keyword->update(['is_scraped' => 'bing_news_update_fail']);
                    }

                } catch (\Throwable $th) {
                    echo "Something bad With Bing News Please check: $newsUrl <br>";
                    $keyword->update(['is_scraped' => 'bing_news_hit_fail']);
                }
                //~ try to update New From bing News search end

                //* try to update video from bing search start
                try {

                    $videoUrl    = 'http://' . $ip->ip_address . ':3000/bing-videos?url=https://www.' . config('constant.bing_url') . '/videos/search?q=' . str_replace(' ', '+', $keyword->value) . '+' . config('constant.bing_query') . '&qft=+filterui:msite-youtube.com';
                    $bing_videos = Http::timeout(90)->get($videoUrl)->body();
                    $bing_videos = json_decode($bing_videos, true);

                    echo "<br>Bing videos<br>";
                    print_r($bing_videos);

                    $bing_videos = (!empty($bing_videos)) ? ($bing_videos) : null;

                    //Updating Videos in database
                    try {
                        $post_content->update([
                            'bing_videos' => $bing_videos,
                        ]);

                        $keyword->update(['is_scraped' => 'bing_video_updated']);
                    } catch (\Throwable $th) {
                        echo "Fail to store Bing Video in Database please chec: $videoUrl<br>";
                        $keyword->update(['is_scraped' => 'bing_video_update_fail']);
                    }
                } catch (\Throwable $th) {
                    echo "some thing bad with Bing Video Search Please check: $videoUrl <br>";
                    $keyword->update(['is_scraped' => 'bing_video_hit_fail']);
                }
                //~ try to update video from bing search end

                //* Hit to google api and get data for google faq,rich_snippet,search results start
                try {
                    $api_url_google = 'http://' . $ip->ip_address . ':3000/google?url=https://www.' . config('constant.google_url') . '/search?q=' . str_replace(' ', '+', $keyword->value);
                    echo "Google APi Url: $api_url_google<br>";
                    $api_data_google = Http::timeout(90)->get($api_url_google)->body();

                    $google_data = json_decode($api_data_google, true);

                    echo "Google Api Data:<br>";
                    print_r($google_data);

                    $google_related_keywords = (!empty($google_data['relatedKeywordsGoogle'])) ? ($google_data['relatedKeywordsGoogle']) : null;
                    $google_rich_snippet     = (!empty($google_data['richSnippetGoogle'][0])) ? ($google_data['richSnippetGoogle'][0]) : null;

                    $keyword->update(['is_scraped' => 'google_api_hit_success']);
                } catch (\Throwable $th) {
                    echo "Something bad with google_com Please check: $api_url_google <br>";
                    $keyword->update(['is_scraped' => 'google_api_hit_fail']);
                }
                //~ Hit to google api and get data for google faq,rich_snippet,search results end

                //* updating google_related_keywords in PostContent start
                try {
                    $post_content->update([
                        'google_related_keywords' => $google_related_keywords,
                    ]);
                    $keyword->update(['is_scraped' => 'google_related_keywords_updated']);
                } catch (\Throwable $th) {
                    echo "Something bad with google_related_keywords Please Check:  $api_url_google<br>";
                    $keyword->update(['is_scraped' => 'google_related_keywords _update_fail']);
                }
                //~ updating google_related_keywords in PostContent end

                //* updating google_rich_snippet in PostContent start
                try {
                    $post_content->update([
                        'google_rich_snippet' => $google_rich_snippet,
                    ]);
                    $keyword->update(['is_scraped' => 'google_rich_snippet_updated']);
                } catch (\Throwable $th) {
                    echo "Something bad with google_related_keywords Please Check:  $api_url_google<br>";
                    $keyword->update(['is_scraped' => 'google_rich_snippet_update_fail']);
                }
                //~ updating google_rich_snippet in PostContent end

                //* updating google_faq in PostContent start
                $google_faq_questions['questions'] = (!empty($google_data['questions'])) ? $google_data['questions'] : null;
                $google_faq_answers['answers']     = (!empty($google_data['answers'])) ? $google_data['answers'] : null;

                try {
                    $post_content->update([
                        'google_faq_questions' => $google_faq_questions['questions'],
                        'google_faq_answers'   => $google_faq_answers['answers'],
                    ]);
                    $keyword->update(['is_scraped' => 'google_faq_updated']);
                } catch (\Throwable $th) {
                    echo "Something bad with People Also Ask Please Check:  $api_url_google<br>";
                    $keyword->update(['is_scraped' => 'google_faq_update_fail']);
                }
                //~ updating google_faq in PostContent end

                //* updating google_search_result in PostContent start
                $google_result_title['title']             = (!empty($google_data['resultTitleGoogle'])) ? $google_data['resultTitleGoogle'] : null;
                $google_result_description['description'] = (!empty($google_data['resultDescriptionGoogle'])) ? $google_data['resultDescriptionGoogle'] : null;
                $google_result_url['url']                 = (!empty($google_data['resultUrlGoogle'])) ? $google_data['resultUrlGoogle'] : null;

                try {
                    $post_content->update([
                        'google_search_result_title'       => $google_result_title['title'],
                        'google_search_result_description' => $google_result_description['description'],
                        'google_search_result_url'         => $google_result_url['url'],
                    ]);
                    $keyword->update(['is_scraped' => 'google_search_result_update']);
                } catch (\Throwable $th) {
                    echo "Something bad with google_search_result Please Check: $api_url_google <br>";
                    $keyword->update(['is_scraped' => 'google_search_result_update_fail']);
                }
                // ~ updating google_search_result in PostContent end

                // * hit to Bing Api Start
                try {
                    $api_url_bing = 'http://' . $ip->ip_address . ':3000/bing?url=https://www.' . config('constant.bing_url') . '/search?q=' . str_replace(' ', '+', $keyword->value) . '+' . config('constant.bing_query');
                    echo "Bing Api Url: $api_url_bing<br>";
                    $api_data = Http::timeout(90)->get($api_url_bing)->body();

                    $bing_data = json_decode($api_data, true);

                    echo "Bing APi Data :<br>";
                    print_r($bing_data);

                    $bing_related_keywords = (!empty($bing_data['relatedKeywords'])) ? ($bing_data['relatedKeywords']) : null;
                    $keyword->update(['is_scraped' => 'bing_api_hit_success']);

                } catch (\Throwable $th) {
                    echo "Bing Api not responding properly Please check api manually:  $api_url_bing <br>";
                    $keyword->update(['is_scraped' => 'bing_api_hit_fail']);
                    $ip->update([
                        'status' => 'NOT_WORKING',
                        'ERROR'  => 'Ip Not Opened Proper- ' . $api_url_bing,
                    ]);
                    echo "Please Check ip Carefully something bad with this: .$api_url_bing";
                }
                //~ hit to Bing Api end

                //* updating Related Keywords start
                try {
                    $post_content->update([
                        'bing_related_keywords' => $bing_related_keywords,
                    ]);
                    $keyword->update(['is_scraped' => 'bing_related_keyword_updated']);
                } catch (\Throwable $th) {
                    echo "fail to update Bing_keywords<br>";
                    $keyword->update(['is_scraped' => 'bing_related_keyword_fail_to_update']);
                }
                //~ updating Related Keywords end

                //* updating bing_search_result,post_description in PostContent start
                $result_title['result_title']             = (!empty($bing_data['resultTitle'])) ? $bing_data['resultTitle'] : null;
                $result_description['result_description'] = (!empty($bing_data['resultDescription'])) ? $bing_data['resultDescription'] : null;
                $result_url['result_url']                 = (!empty($bing_data['resultUrl'])) ? $bing_data['resultUrl'] : null;

                $post_description = (!empty($result_description['result_description'][0])) ? $result_description['result_description'][0] : null;

                try {
                    $post_content->update([
                        'bing_search_result_title'       => $result_title['result_title'],
                        'bing_search_result_description' => $result_description['result_description'],
                        'bing_search_result_url'         => $result_url['result_url'],
                        'post_description'               => $post_description,
                    ]);
                    $keyword->update(['is_scraped' => 'bing_search_result_updated']);
                    $ip->update([
                        'status'       => 'OK',
                        'ERROR'        => null,
                        'scrape_count' => DB::raw('scrape_count + 1'),
                    ]);
                } catch (\Throwable $th) {
                    echo "Something bad with search results Please check Api: $api_url_bing<br>";
                    $ip->update([
                        'status' => 'NOT_WORKING',
                        'ERROR'  => 'DATA NOT FOUND- ' . $api_url_bing,
                    ]);
                    $keyword->update(['is_scraped' => 'bing_search_result_update_fail']);
                }
                //~ updating bing_search_result,post_description in PostContent end

                //* updating bing_paa in PostContent start
                $bing_paa_questions['paa_questions'] = (!empty($bing_data['mainQuestions'])) ? $bing_data['mainQuestions'] : null;
                $bing_paa_answers['paa_Answers']     = (!empty($bing_data['mainAnswers'])) ? $bing_data['mainAnswers'] : null;

                try {
                    $post_content->update([
                        'bing_paa_questions' => $bing_paa_questions['paa_questions'],
                        'bing_paa_answers'   => $bing_paa_answers['paa_Answers'],
                    ]);
                    $keyword->update(['is_scraped' => 'bing_paa_updated']);
                } catch (\Throwable $th) {
                    echo "Something bad with People Also Ask Please check Api: $api_url_bing<br>";
                    $keyword->update(['is_scraped' => 'bing_pass_update_fail']);
                }
                //~ updating bing_paa in PostContent end

                // * updating bing_rich_snippet in PostContent start
                $bing_rich_snippet_text['bing_rich_snippet_text'] = (!empty($bing_data['richSnippet'][0])) ? preg_replace('/<img[^>]+\>/i', ' ', $bing_data['richSnippet'][0]) : null;
                $bing_rich_snippet_link['bing_rich_snippet_link'] = (!empty($bing_data['richSnippetLink'][0])) ? $bing_data['richSnippetLink'][0] : null;

                // * updating bing_rich_snippet in PostContent start
                try {
                    $post_content->update([
                        'bing_rich_snippet_text' => $bing_rich_snippet_text['bing_rich_snippet_text'],
                        'bing_rich_snippet_link' => $bing_rich_snippet_link['bing_rich_snippet_link'],
                    ]);
                    $keyword->update(['is_scraped' => 'bing_rich_snippet_updated']);
                } catch (\Throwable $th) {
                    echo "Something bad with People Also Ask Please check Api: $api_url_bing<br>";
                    $keyword->update(['is_scraped' => 'bing_rich_snippet_update_fail']);
                }
                //~ updating bing_rich_snippet in PostContent end

                //* updating bing_slider_faq in PostContent start
                $bing_slider_faq_questions['slider_questions'] = (!empty($bing_data['slideQuestions'])) ? $bing_data['slideQuestions'] : null;
                $bing_slider_faq_answers['slider_answers']     = (!empty($bing_data['slideAnswers'])) ? $bing_data['slideAnswers'] : null;

                try {
                    $post_content->update([
                        'bing_slider_faq_questions' => $bing_slider_faq_questions['slider_questions'],
                        'bing_slider_faq_answers'   => $bing_slider_faq_answers['slider_answers'],
                    ]);
                    $keyword->update(['is_scraped' => 'bing_slider_faq_updated']);
                } catch (\Throwable $th) {
                    echo "Something bad with People Also Ask Please check APi: $api_url_bing<br>";
                    $keyword->update(['is_scraped' => 'bing_slider_faq_update_fail']);
                }
                //~ updating bing_slider_faq in PostContent end

                //* updating bing_pop in PostContent start
                $bing_pop_questions['pop_questions'] = (!empty($bing_data['popQuestions'])) ? $bing_data['popQuestions'] : null;
                $bing_pop_answers['pop_answers']     = (!empty($bing_data['popAnswers'])) ? $bing_data['popAnswers'] : null;

                try {
                    $post_content->update([
                        'bing_pop_faq_questions' => $bing_pop_questions['pop_questions'],
                        'bing_pop_faq_answers'   => $bing_pop_answers['pop_answers'],
                    ]);
                    $keyword->update(['is_scraped' => 'bing_pop_faq_updated']);
                } catch (\Throwable $th) {
                    echo "Something bad with People Also Ask Please Check Api<br>";
                    $keyword->update(['is_scraped' => 'bing_pop_faq_update_fail']);
                }
                //~updating bing_pop in PostContent end

                //* updating bing_tab in PostContent start
                $bing_tab_questions['tab_questions'] = (!empty($bing_data['tabNav'])) ? $bing_data['tabNav'] : null;
                $bing_tab_answers['tab_answers']     = (!empty($bing_data['tabContent'])) ? $bing_data['tabContent'] : null;

                try {
                    $post_content->update([
                        'bing_tab_faq_questions' => $bing_tab_questions['tab_questions'],
                        'bing_tab_faq_answers'   => $bing_tab_answers['tab_answers'],
                    ]);
                    $keyword->update(['is_scraped' => 'bing_tab_faq_updated']);
                } catch (\Throwable $th) {
                    echo "Something bad with People Also Ask Please Check api: $api_url_bing<br>";
                    $keyword->update(['is_scraped' => 'bing_tab_faq_update_fail']);
                }
                //~ updating bing_tab in PostContent end

                $keyword->update(['is_scraped' => 'success']);

            } else {
                $keyword->update(['is_scraped' => 'duplicate']);

                $ip->update([
                    'status' => 'OK',
                    'ERROR'  => null,
                ]);

                ScrapingFailed::create([
                    'source_value' => $keyword->value,
                    'error'        => 'Duplicate Removed From DataBase Id:' . $keyword->id,
                ]);

                dd("Duplicate Record Found");
            }

        } else {
            $ip->update([
                'status' => 'OK',
                'ERROR'  => null,
            ]);
            dd("No Keywords Found. Please Add Keywords To scrape or STOP Scraping");
        }
    }
}
