<?php

namespace App\Http\Controllers\Backend\Update;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\FakeUser;
use App\Models\PostContent;
use App\Models\ScrapingFailed;
use Spatie\Browsershot\Browsershot;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AutoUpdatePostController extends Controller
{
    public static function update($post_id, $keyword)
    {

        echo "We are updating post For better experience Please Refresh Page";

        $totalFakeUser = FakeUser::count();
        $post_content  = PostContent::create([
            'post_id'      => $post_id,
            'fake_user_id' => mt_rand(1, $totalFakeUser),
        ]);

        // try to save images in database
        try {
            $imageUrl = 'https://www.bing.com/images/search?q=' . str_replace(' ', '+', $keyword) . '&qft=+filterui:licenseType-Any&first=1&tsc=ImageBasicHover';

            $imageHtml = Browsershot::url($imageUrl)
                ->windowSize(1000, 1000)
                ->waitUntilNetworkIdle()
                ->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582')
                ->bodyHtml();

            $dom_document_news = new \DOMDocument();
            libxml_use_internal_errors(true); //disable libxml errors

            $dom_document_news->loadHTML($imageHtml);
            libxml_clear_errors(); //remove errors for yucky html

            $dom_document_news->preserveWhiteSpace = false;
            $dom_document_news->saveHTML();

            $document_xpath_news = new \DOMXPath($dom_document_news);

            //News Xpath to get Data
            $images = $document_xpath_news->query('//a[@class="iusc"]/@m');

            if (!empty($imageHtml) && (1 <= $images->length)) {

                foreach ($images as $image) {
                    $images_j['images'][] = (!empty($image->nodeValue)) ? $image->nodeValue : null;
                }

                if (!empty($images_j['images'][0])) {

                    $images = (!empty($images_j)) ? serialize($images_j) : null;
                } else {
                    $images = (!empty($news)) ? serialize($news) : null;
                }

                //Updating images in database
                try {
                    $post_content->update([
                        'bing_images' => $images,
                    ]);

                } catch (\Throwable $th) {
                    echo "Fail to store Bing images In database <br>";

                }
            } else {
                echo "images_update_fail_no_data_found";
            }
        } catch (\Throwable $th) {

            echo "Something bad With Bing images Please check: $imageUrl <br>";

        }

        // try to save thumbnail_images in database
        try {
            $imageUrl = 'https://www.bing.com/images/search?q=' . str_replace(' ', '+', $keyword) . '&qft=+filterui:aspect-wide&qft=+filterui:licenseType-Any&first=1&tsc=ImageBasicHover';

            $imageHtml = Browsershot::url($imageUrl)
                ->windowSize(1000, 1000)
                ->waitUntilNetworkIdle()
                ->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582')
                ->bodyHtml();

            $dom_document_news = new \DOMDocument();
            libxml_use_internal_errors(true); //disable libxml errors

            $dom_document_news->loadHTML($imageHtml);
            libxml_clear_errors(); //remove errors for yucky html

            $dom_document_news->preserveWhiteSpace = false;
            $dom_document_news->saveHTML();

            $document_xpath_news = new \DOMXPath($dom_document_news);

            //News Xpath to get Data
            $images = $document_xpath_news->query('//a[@class="iusc"]/@m');

            if (!empty($imageHtml) && (1 <= $images->length)) {

                foreach ($images as $image) {
                    $images_j['images'][] = (!empty($image->nodeValue)) ? $image->nodeValue : null;
                }

                if (!empty($images_j['images'][0])) {

                    $images = (!empty($images_j)) ? serialize($images_j) : null;
                } else {
                    $images = (!empty($news)) ? serialize($news) : null;
                }
                $thumbnail = json_decode($images_j['images'][0], true);

                //Updating thumbnail_images in database
                try {
                    $post_content->update([
                        'post_thumbnail' => $thumbnail['murl'],
                    ]);

                } catch (\Throwable $th) {
                    echo "Fail to store Bing thumbnail_images In database <br>";

                }
            } else {
                echo "thumbnail_images_fail_no_data_found";
            }
        } catch (\Throwable $th) {

            echo "Something bad With Bing thumbnail_images Please check: $imageUrl <br>";

        }

        // try to update New From bing News search
        try {
            $newsUrl = 'https://www.bing.com/news/search?q=' . str_replace(' ', '+', $keyword);

            $newsHtml = Browsershot::url($newsUrl)
                ->windowSize(1000, 1000)
                ->waitUntilNetworkIdle()
                ->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582')
                ->bodyHtml();

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
                    $news_d['description'][] = (!empty($news_description->nodeValue)) ? $news_description->nodeValue : null;
                }

                $post_description = (!empty($news_d['description'][0])) ? $news_d['description'][0] : null;

                if (!empty($news_t) && !empty($news_d)) {
                    $news = array_merge($news_t, $news_d);
                    // echo "news";
                    // print_r($news);
                    $news = (!empty($news)) ? serialize($news) : null;
                } else {
                    $news = (!empty($news)) ? serialize($news) : null;
                }

                //Updating news in database
                try {
                    $post_content->update([
                        'bing_news'        => $news,
                        'post_description' => $post_description,
                    ]);

                } catch (\Throwable $th) {
                    echo "Fail to store Bing News In database <br>";
                }
            } else {
                echo "news_update_fail_no_data_found";
            }

        } catch (\Throwable $th) {
            echo "Something bad With Bing News Please check: $newsUrl <br>";
        }

        //try to update video from bing search
        try {
            $videoUrl  = 'https://www.bing.com/videos/search?q=' . str_replace(' ', '+', $keyword) . '&qft=+filterui%3amsite-youtube.com';
            $videoHtml = Browsershot::url($videoUrl)
                ->windowSize(1000, 1000)
                ->waitUntilNetworkIdle(false)
                ->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582')
                ->bodyHtml();

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
                    $video_j[] = (!empty($video_json->nodeValue)) ? $video_json->nodeValue : null;
                }
                // echo "videos: ";

                // print_r($video_j);

                $video_j = (!empty($video_j)) ? serialize($video_j) : null;

                try {
                    $post_content->update([
                        'bing_videos' => $video_j,
                    ]);

                } catch (\Throwable $th) {
                    echo "Fail to store Bing Video in Database<br>";

                }
            } else {
                echo "bing_update_fail_data_not_found<br>";
            }
        } catch (\Throwable $th) {
            echo "some thing bad with Bing Video Search Please check: $videoUrl <br>";
            echo "bing_video_hit_fail<br>";
        }

        // hit to Bing Api
        try {
            $api_url_bing = 'http://' . config('app.NODE_SCRAPER_IP') . ':3000/bing?url=https://www.bing.com/search?q=' . str_replace(' ', '+', $keyword);
            $api_data     = Http::retry(3, 60)->get($api_url_bing)->body();

            $bing_data = json_decode($api_data, true);

            $bing_related_keywords = (!empty($bing_data['relatedKeywords'])) ? serialize($bing_data['relatedKeywords']) : null;

        } catch (\Throwable $th) {
            echo "Bing Api not responding properly Please check api manually:  $api_url_bing <br>";

        }

        //updating Related Keywords
        try {
            $post_content->update([
                'bing_related_keywords' => $bing_related_keywords,
            ]);

        } catch (\Throwable $th) {
            echo "fail to update Bing_keywords";

        }

        $result_title['result_title'][]             = (!empty($bing_data['resultTitle'])) ? $bing_data['resultTitle'] : null;
        $result_description['result_description'][] = (!empty($bing_data['resultDescription'])) ? $bing_data['resultDescription'] : null;
        $result_url['result_url'][]                 = (!empty($bing_data['resultUrl'])) ? $bing_data['resultUrl'] : null;

        if (!empty($result_title) && !empty($result_description) && !empty($result_url['result_url'][0])) {
            $bing_search_result = array_merge($result_title, $result_description, $result_url);
            $bing_search_result = (!empty($bing_search_result)) ? serialize($bing_search_result) : null;
        } else {
            $bing_search_result = (!empty($bing_search_result)) ? serialize($bing_search_result) : null;
        }

        // updating bing_search_result in PostContent
        try {
            $post_content->update([
                'bing_search_result' => $bing_search_result,
            ]);

        } catch (\Throwable $th) {
            echo "Something bad with search results";

        }

        $bing_paa_questions['paa_questions'][] = (!empty($bing_data['mainQuestions'])) ? $bing_data['mainQuestions'] : null;
        $bing_paa_answers['paa_Answers'][]     = (!empty($bing_data['resultDescription'])) ? $bing_data['resultDescription'] : null;

        if (!empty($bing_paa_questions['paa_questions'][0]) && !empty($bing_paa_answers['paa_Answers'][0])) {

            $bing_paa = array_merge($bing_paa_questions, $bing_paa_answers);
            $bing_paa = (!empty($bing_paa)) ? serialize($bing_paa) : null;

        } else {
            $bing_paa = (!empty($bing_paa)) ? serialize($bing_paa) : null;
        }

        // updating bing_paa in PostContent
        try {
            $post_content->update([
                'bing_paa' => $bing_paa,
            ]);

        } catch (\Throwable $th) {
            echo "Something bad with People Also Ask";

        }

        $bing_rich_snippet_text['bing_rich_snippet_text'][] = (!empty($bing_data['richSnippet'])) ? preg_replace('/<img[^>]+\>/i', ' ', $bing_data['richSnippet']) : null;
        $bing_rich_snippet_link['bing_rich_snippet_link'][] = (!empty($bing_data['richSnippetLink'])) ? $bing_data['richSnippetLink'] : null;

        if (!empty($bing_rich_snippet_text['bing_rich_snippet_text'][0]) && !empty($bing_rich_snippet_link['bing_rich_snippet_link'][0])) {

            $bing_rich_snippet = array_merge($bing_rich_snippet_text, $bing_rich_snippet_link);
            $bing_rich_snippet = (!empty($bing_rich_snippet)) ? serialize($bing_rich_snippet) : null;

        } else {
            $bing_rich_snippet = (!empty($bing_rich_snippet)) ? serialize($bing_rich_snippet) : null;
        }

        // updating bing_rich_snippet in PostContent
        try {
            $post_content->update([
                'bing_rich_snippet' => $bing_rich_snippet,
            ]);

        } catch (\Throwable $th) {
            echo "Something bad with People Also Ask";

        }

        $bing_slider_faq_questions['slider_questions'][] = (!empty($bing_data['slideQuestions'])) ? $bing_data['slideQuestions'] : null;
        $bing_slider_faq_answers['slider_answers'][]     = (!empty($bing_data['slideAnswers'])) ? $bing_data['slideAnswers'] : null;

        if (!empty($bing_slider_faq_questions['slider_questions'][0]) && !empty($bing_slider_faq_answers['slider_answers'][0])) {

            $bing_slider_faq = array_merge($bing_slider_faq_questions, $bing_slider_faq_answers);
            $bing_slider_faq = (!empty($bing_slider_faq)) ? serialize($bing_slider_faq) : null;

        } else {
            $bing_slider_faq = (!empty($bing_slider_faq)) ? serialize($bing_slider_faq) : null;
        }

        // updating bing_rich_snippet in PostContent
        try {
            $post_content->update([
                'bing_slider_faq' => $bing_slider_faq,
            ]);

        } catch (\Throwable $th) {
            echo "Something bad with People Also Ask<br>";

        }

        $bing_pop_questions['pop_questions'][] = (!empty($bing_data['popQuestions'])) ? $bing_data['popQuestions'] : null;
        $bing_pop_answers['pop_answers'][]     = (!empty($bing_data['popAnswers'])) ? $bing_data['popAnswers'] : null;

        if (!empty($bing_pop_questions['pop_questions'][0]) && !empty($bing_pop_answers['pop_answers'][0])) {

            $bing_pop_faq = array_merge($bing_pop_questions, $bing_pop_answers);
            $bing_pop_faq = (!empty($bing_pop_faq)) ? serialize($bing_pop_faq) : null;

        } else {
            $bing_pop_faq = (!empty($bing_pop_faq)) ? serialize($bing_pop_faq) : null;
        }

        // updating bing_rich_snippet in PostContent
        try {
            $post_content->update([
                'bing_pop_faq' => $bing_pop_faq,
            ]);

        } catch (\Throwable $th) {
            echo "Something bad with People Also Ask<br>";

        }

        $bing_tab_questions['tab_questions'][] = (!empty($bing_data['tabNav'])) ? $bing_data['tabNav'] : null;
        $bing_tab_answers['tab_answers'][]     = (!empty($bing_data['tabContent'])) ? $bing_data['tabContent'] : null;

        if (!empty($bing_tab_questions['tab_questions'][0]) && !empty($bing_tab_answers['tab_answers'][0])) {

            $bing_tab_faq = array_merge($bing_tab_questions, $bing_tab_answers);
            $bing_tab_faq = (!empty($bing_tab_faq)) ? serialize($bing_tab_faq) : null;

        } else {
            $bing_tab_faq = (!empty($bing_tab_faq)) ? serialize($bing_tab_faq) : null;
        }

        // updating bing_rich_snippet in PostContent
        try {
            $post_content->update([
                'bing_tab_faq' => $bing_tab_faq,
            ]);

        } catch (\Throwable $th) {
            echo "Something bad with People Also Ask<br>";

        }

        try {
            $api_url_google  = 'http://' . config('app.NODE_SCRAPER_IP') . ':3000/google?url=https://www.google.com/search?q=' . str_replace(' ', '+', $keyword);
            $api_data_google = Http::retry(3, 60)->get($api_url_google)->body();

            $google_data = json_decode($api_data_google, true);

            $google_related_keywords = (!empty($google_data['relatedKeywordsGoogle'])) ? serialize($google_data['relatedKeywordsGoogle']) : null;
            $google_rich_snippet     = (!empty($google_data['richSnippetGoogle'])) ? serialize($google_data['richSnippetGoogle']) : null;

        } catch (\Throwable $th) {
            echo "Something bad with google.com Please check: $api_url_google <br>";

        }

        // updating google_related_keywords in PostContent
        try {
            $post_content->update([
                'google_related_keywords' => $google_related_keywords,
            ]);

        } catch (\Throwable $th) {
            echo "Something bad with google_related_keywords <br>";

        }

        // updating google_rich_snippet in PostContent
        try {
            $post_content->update([
                'google_rich_snippet' => $google_rich_snippet,
            ]);

        } catch (\Throwable $th) {
            echo "Something bad with google_related_keywords <br>";

        }

        $google_faq_questions['questions'][] = (!empty($google_data['questions'])) ? $google_data['questions'] : array();
        $google_faq_answers['answers'][]     = (!empty($google_data['answers'])) ? $google_data['answers'] : array();

        if (!empty($google_faq_questions['questions'][0]) && !empty($google_faq_answers['answers'][0])) {

            $google_faq = array_merge($google_faq_questions, $google_faq_answers);
            $google_faq = (!empty($google_faq)) ? serialize($google_faq) : null;

        } else {
            $google_faq = (!empty($google_faq)) ? serialize($google_faq) : null;
        }

        // updating google_faq in PostContent
        try {
            $post_content->update([
                'google_faq' => $google_faq,
            ]);

        } catch (\Throwable $th) {
            echo "Something bad with People Also Ask <br>";

        }

        $google_result_title['title'][]             = (!empty($google_data['resultTitleGoogle'])) ? $google_data['resultTitleGoogle'] : null;
        $google_result_description['description'][] = (!empty($google_data['resultDescriptionGoogle'])) ? $google_data['resultDescriptionGoogle'] : null;
        $google_result_url['url'][]                 = (!empty($google_data['resultUrlGoogle'])) ? $google_data['resultUrlGoogle'] : null;

        if (!empty($google_result_title['title'][0]) && !empty($google_result_description['description'][0]) && !empty($google_result_url['url'][0])) {

            $google_search_result = array_merge($google_result_title, $google_result_description, $google_result_url);
            $google_search_result = (!empty($google_search_result)) ? serialize($google_search_result) : null;

        } else {
            $google_faq = (!empty($google_search_result)) ? serialize($google_search_result) : null;
        }

        // updating google_search_result in PostContent
        try {
            $post_content->update([
                'google_search_result' => $google_search_result,
            ]);

        } catch (\Throwable $th) {
            echo "Something bad with google_search_result <br>";

        }

    }

    ////////////////////////////////////////////////////////////////////////////
    //// ?add post if not Found in database use in Related Keyword function/////
    ///////////////////////////////////////////////////////////////////////////
    public static function addPost($slug)
    {
        $keyword = str_replace('-', ' ', $slug);

        if (!empty($slug)) {

            $duplicate_check = Post::where('source_value', $keyword)->first();
            $totalFakeUser   = FakeUser::count();

            if (empty($duplicate_check)) {

                $post = Post::create([
                    'post_title'   => $keyword,
                    'source_value' => $keyword,
                    'fake_user_id' => mt_rand(1, $totalFakeUser),
                    'published_at' => Carbon::today()->subDays(rand(0, 365)),
                ]);

                $post_content = PostContent::create([
                    'post_id'      => $post->id,
                    'fake_user_id' => mt_rand(1, $totalFakeUser),
                ]);

                // try to save images in database
                try {
                    $imageUrl = 'https://www.bing.com/images/search?q=' . str_replace(' ', '+', $keyword) . '&qft=+filterui:licenseType-Any&first=1&tsc=ImageBasicHover';

                    $imageHtml = Browsershot::url($imageUrl)
                        ->windowSize(1000, 1000)
                        ->waitUntilNetworkIdle()
                        ->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582')
                        ->bodyHtml();

                    $dom_document_news = new \DOMDocument();
                    libxml_use_internal_errors(true); //disable libxml errors

                    $dom_document_news->loadHTML($imageHtml);
                    libxml_clear_errors(); //remove errors for yucky html

                    $dom_document_news->preserveWhiteSpace = false;
                    $dom_document_news->saveHTML();

                    $document_xpath_news = new \DOMXPath($dom_document_news);

                    //News Xpath to get Data
                    $images = $document_xpath_news->query('//a[@class="iusc"]/@m');

                    if (!empty($imageHtml) && (1 <= $images->length)) {

                        foreach ($images as $image) {
                            $images_j['images'][] = (!empty($image->nodeValue)) ? $image->nodeValue : null;
                        }

                        if (!empty($images_j['images'][0])) {

                            $images = (!empty($images_j)) ? serialize($images_j) : null;
                        } else {
                            $images = (!empty($news)) ? serialize($news) : null;
                        }

                        //Updating images in database
                        try {
                            $post_content->update([
                                'bing_images' => $images,
                            ]);

                        } catch (\Throwable $th) {
                            echo "Fail to store Bing images In database <br>";

                        }
                    } else {
                        echo "images_update_fail_no_data_found";
                    }
                } catch (\Throwable $th) {

                    echo "Something bad With Bing images Please check: $imageUrl <br>";

                }

                // try to save thumbnail in database
                try {
                    $imageUrl = 'https://www.bing.com/images/search?q=' . str_replace(' ', '+', $keyword) . '&qft=+filterui:aspect-wide&qft=+filterui:licenseType-Any&first=1&tsc=ImageBasicHover';

                    $imageHtml = Browsershot::url($imageUrl)
                        ->windowSize(1000, 1000)
                        ->waitUntilNetworkIdle()
                        ->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582')
                        ->bodyHtml();

                    $dom_document_news = new \DOMDocument();
                    libxml_use_internal_errors(true); //disable libxml errors

                    $dom_document_news->loadHTML($imageHtml);
                    libxml_clear_errors(); //remove errors for yucky html

                    $dom_document_news->preserveWhiteSpace = false;
                    $dom_document_news->saveHTML();

                    $document_xpath_news = new \DOMXPath($dom_document_news);

                    //News Xpath to get Data
                    $images = $document_xpath_news->query('//a[@class="iusc"]/@m');

                    if (!empty($imageHtml) && (1 <= $images->length)) {

                        foreach ($images as $image) {
                            $images_j['images'][] = (!empty($image->nodeValue)) ? $image->nodeValue : null;
                        }

                        if (!empty($images_j['images'][0])) {

                            $images = (!empty($images_j)) ? serialize($images_j) : null;
                        } else {
                            $images = (!empty($news)) ? serialize($news) : null;
                        }
                        $thumbnail = json_decode($images_j['images'][0], true);

                        //Updating images in database
                        try {
                            $post_content->update([
                                'post_thumbnail' => $thumbnail['murl'],
                            ]);

                        } catch (\Throwable $th) {
                            echo "Fail to store Bing thumbnail_images In database <br>";

                        }
                    } else {
                        echo "thumbnail_images_update_fail_no_data_found";
                    }
                } catch (\Throwable $th) {

                    echo "Something bad With Bing thumbnail_images Please check: $imageUrl <br>";

                }

                // try to update New From bing News search
                try {
                    $newsUrl = 'https://www.bing.com/news/search?q=' . str_replace(' ', '+', $keyword);

                    $newsHtml = Browsershot::url($newsUrl)
                        ->windowSize(1000, 1000)
                        ->waitUntilNetworkIdle()
                        ->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582')
                        ->bodyHtml();

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
                            $news_d['description'][] = (!empty($news_description->nodeValue)) ? $news_description->nodeValue : null;
                        }

                        $post_description = (!empty($news_d['description'][0])) ? $news_d['description'][0] : null;

                        if (!empty($news_t) && !empty($news_d)) {
                            $news = array_merge($news_t, $news_d);
                            // echo "news";
                            // print_r($news);
                            $news = (!empty($news)) ? serialize($news) : null;
                        } else {
                            $news = (!empty($news)) ? serialize($news) : null;
                        }

                        //Updating news in database
                        try {
                            $post_content->update([
                                'bing_news'        => $news,
                                'post_description' => $post_description,
                            ]);

                        } catch (\Throwable $th) {
                            echo "Fail to store Bing News In database <br>";

                        }
                    } else {
                        echo "news_update_fail_no_data_found";
                    }

                } catch (\Throwable $th) {
                    echo "Something bad With Bing News Please check: $newsUrl <br>";

                }

                //try to update video from bing search
                try {
                    $videoUrl  = 'https://www.bing.com/videos/search?q=' . str_replace(' ', '+', $keyword) . '&qft=+filterui%3amsite-youtube.com';
                    $videoHtml = Browsershot::url($videoUrl)
                        ->windowSize(1000, 1000)
                        ->waitUntilNetworkIdle(false)
                        ->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582')
                        ->bodyHtml();

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
                            $video_j[] = (!empty($video_json->nodeValue)) ? $video_json->nodeValue : null;
                        }
                        // echo "videos: ";

                        // print_r($video_j);

                        $video_j = (!empty($video_j)) ? serialize($video_j) : null;

                        try {
                            $post_content->update([
                                'bing_videos' => $video_j,
                            ]);

                        } catch (\Throwable $th) {
                            echo "Fail to store Bing Video in Database";

                        }
                    } else {
                        echo "bing_update_fail_data_not_found";
                    }
                } catch (\Throwable $th) {
                    echo "some thing bad with Bing Video Search Please check: $videoUrl <br>";

                }

                // hit to Bing Api
                try {
                    $api_url_bing = 'http://' . config('app.NODE_SCRAPER_IP') . ':3000/bing?url=https://www.bing.com/search?q=' . str_replace(' ', '+', $keyword);
                    $api_data     = Http::retry(3, 60)->get($api_url_bing)->body();

                    $bing_data = json_decode($api_data, true);

                    $bing_related_keywords = (!empty($bing_data['relatedKeywords'])) ? serialize($bing_data['relatedKeywords']) : null;

                } catch (\Throwable $th) {
                    echo "Bing Api not responding properly Please check api manually:  $api_url_bing <br>";

                }

                //updating Related Keywords
                try {
                    $post_content->update([
                        'bing_related_keywords' => $bing_related_keywords,
                    ]);

                } catch (\Throwable $th) {
                    echo "fail to update Bing_keywords";

                }

                $result_title['result_title'][]             = (!empty($bing_data['resultTitle'])) ? $bing_data['resultTitle'] : null;
                $result_description['result_description'][] = (!empty($bing_data['resultDescription'])) ? $bing_data['resultDescription'] : null;
                $result_url['result_url'][]                 = (!empty($bing_data['resultUrl'])) ? $bing_data['resultUrl'] : null;

                if (!empty($result_title) && !empty($result_description) && !empty($result_url['result_url'][0])) {
                    $bing_search_result = array_merge($result_title, $result_description, $result_url);
                    $bing_search_result = (!empty($bing_search_result)) ? serialize($bing_search_result) : null;
                } else {
                    $bing_search_result = (!empty($bing_search_result)) ? serialize($bing_search_result) : null;
                }

                // updating bing_search_result in PostContent
                try {
                    $post_content->update([
                        'bing_search_result' => $bing_search_result,
                    ]);

                } catch (\Throwable $th) {
                    echo "Something bad with search results";

                }

                $bing_paa_questions['paa_questions'][] = (!empty($bing_data['mainQuestions'])) ? $bing_data['mainQuestions'] : null;
                $bing_paa_answers['paa_Answers'][]     = (!empty($bing_data['resultDescription'])) ? $bing_data['resultDescription'] : null;

                if (!empty($bing_paa_questions['paa_questions'][0]) && !empty($bing_paa_answers['paa_Answers'][0])) {

                    $bing_paa = array_merge($bing_paa_questions, $bing_paa_answers);
                    $bing_paa = (!empty($bing_paa)) ? serialize($bing_paa) : null;

                } else {
                    $bing_paa = (!empty($bing_paa)) ? serialize($bing_paa) : null;
                }

                // updating bing_paa in PostContent
                try {
                    $post_content->update([
                        'bing_paa' => $bing_paa,
                    ]);

                } catch (\Throwable $th) {
                    echo "Something bad with People Also Ask";

                }

                $bing_rich_snippet_text['bing_rich_snippet_text'][] = (!empty($bing_data['richSnippet'])) ? preg_replace('/<img[^>]+\>/i', ' ', $bing_data['richSnippet']) : null;
                $bing_rich_snippet_link['bing_rich_snippet_link'][] = (!empty($bing_data['richSnippetLink'])) ? $bing_data['richSnippetLink'] : null;

                if (!empty($bing_rich_snippet_text['bing_rich_snippet_text'][0]) && !empty($bing_rich_snippet_link['bing_rich_snippet_link'][0])) {

                    $bing_rich_snippet = array_merge($bing_rich_snippet_text, $bing_rich_snippet_link);
                    $bing_rich_snippet = (!empty($bing_rich_snippet)) ? serialize($bing_rich_snippet) : null;

                } else {
                    $bing_rich_snippet = (!empty($bing_rich_snippet)) ? serialize($bing_rich_snippet) : null;
                }

                // updating bing_rich_snippet in PostContent
                try {
                    $post_content->update([
                        'bing_rich_snippet' => $bing_rich_snippet,
                    ]);

                } catch (\Throwable $th) {
                    echo "Something bad with People Also Ask";

                }

                $bing_slider_faq_questions['slider_questions'][] = (!empty($bing_data['slideQuestions'])) ? $bing_data['slideQuestions'] : null;
                $bing_slider_faq_answers['slider_answers'][]     = (!empty($bing_data['slideAnswers'])) ? $bing_data['slideAnswers'] : null;

                if (!empty($bing_slider_faq_questions['slider_questions'][0]) && !empty($bing_slider_faq_answers['slider_answers'][0])) {

                    $bing_slider_faq = array_merge($bing_slider_faq_questions, $bing_slider_faq_answers);
                    $bing_slider_faq = (!empty($bing_slider_faq)) ? serialize($bing_slider_faq) : null;

                } else {
                    $bing_slider_faq = (!empty($bing_slider_faq)) ? serialize($bing_slider_faq) : null;
                }

                // updating bing_rich_snippet in PostContent
                try {
                    $post_content->update([
                        'bing_slider_faq' => $bing_slider_faq,
                    ]);

                } catch (\Throwable $th) {
                    echo "Something bad with People Also Ask<br>";

                }

                $bing_pop_questions['pop_questions'][] = (!empty($bing_data['popQuestions'])) ? $bing_data['popQuestions'] : null;
                $bing_pop_answers['pop_answers'][]     = (!empty($bing_data['popAnswers'])) ? $bing_data['popAnswers'] : null;

                if (!empty($bing_pop_questions['pop_questions'][0]) && !empty($bing_pop_answers['pop_answers'][0])) {

                    $bing_pop_faq = array_merge($bing_pop_questions, $bing_pop_answers);
                    $bing_pop_faq = (!empty($bing_pop_faq)) ? serialize($bing_pop_faq) : null;

                } else {
                    $bing_pop_faq = (!empty($bing_pop_faq)) ? serialize($bing_pop_faq) : null;
                }

                // updating bing_rich_snippet in PostContent
                try {
                    $post_content->update([
                        'bing_pop_faq' => $bing_pop_faq,
                    ]);

                } catch (\Throwable $th) {
                    echo "Something bad with People Also Ask<br>";

                }

                $bing_tab_questions['tab_questions'][] = (!empty($bing_data['tabNav'])) ? $bing_data['tabNav'] : null;
                $bing_tab_answers['tab_answers'][]     = (!empty($bing_data['tabContent'])) ? $bing_data['tabContent'] : null;

                if (!empty($bing_tab_questions['tab_questions'][0]) && !empty($bing_tab_answers['tab_answers'][0])) {

                    $bing_tab_faq = array_merge($bing_tab_questions, $bing_tab_answers);
                    $bing_tab_faq = (!empty($bing_tab_faq)) ? serialize($bing_tab_faq) : null;

                } else {
                    $bing_tab_faq = (!empty($bing_tab_faq)) ? serialize($bing_tab_faq) : null;
                }

                // updating bing_rich_snippet in PostContent
                try {
                    $post_content->update([
                        'bing_tab_faq' => $bing_tab_faq,
                    ]);

                } catch (\Throwable $th) {
                    echo "Something bad with People Also Ask<br>";

                }

                try {
                    $api_url_google  = 'http://' . config('app.NODE_SCRAPER_IP') . ':3000/google?url=https://www.google.com/search?q=' . str_replace(' ', '+', $keyword);
                    $api_data_google = Http::retry(3, 60)->get($api_url_google)->body();

                    $google_data = json_decode($api_data_google, true);

                    $google_related_keywords = (!empty($google_data['relatedKeywordsGoogle'])) ? serialize($google_data['relatedKeywordsGoogle']) : null;
                    $google_rich_snippet     = (!empty($google_data['richSnippetGoogle'])) ? serialize($google_data['richSnippetGoogle']) : null;

                } catch (\Throwable $th) {
                    echo "Something bad with google.com Please check: $api_url_google <br>";

                }

                // updating google_related_keywords in PostContent
                try {
                    $post_content->update([
                        'google_related_keywords' => $google_related_keywords,
                    ]);

                } catch (\Throwable $th) {
                    echo "Something bad with google_related_keywords <br>";

                }

                // updating google_rich_snippet in PostContent
                try {
                    $post_content->update([
                        'google_rich_snippet' => $google_rich_snippet,
                    ]);

                } catch (\Throwable $th) {
                    echo "Something bad with google_related_keywords <br>";

                }

                $google_faq_questions['questions'][] = (!empty($google_data['questions'])) ? $google_data['questions'] : array();
                $google_faq_answers['answers'][]     = (!empty($google_data['answers'])) ? $google_data['answers'] : array();

                if (!empty($google_faq_questions['questions'][0]) && !empty($google_faq_answers['answers'][0])) {

                    $google_faq = array_merge($google_faq_questions, $google_faq_answers);
                    $google_faq = (!empty($google_faq)) ? serialize($google_faq) : null;

                } else {
                    $google_faq = (!empty($google_faq)) ? serialize($google_faq) : null;
                }

                // updating google_faq in PostContent
                try {
                    $post_content->update([
                        'google_faq' => $google_faq,
                    ]);

                } catch (\Throwable $th) {
                    echo "Something bad with People Also Ask <br>";

                }

                $google_result_title['title'][]             = (!empty($google_data['resultTitleGoogle'])) ? $google_data['resultTitleGoogle'] : null;
                $google_result_description['description'][] = (!empty($google_data['resultDescriptionGoogle'])) ? $google_data['resultDescriptionGoogle'] : null;
                $google_result_url['url'][]                 = (!empty($google_data['resultUrlGoogle'])) ? $google_data['resultUrlGoogle'] : null;

                if (!empty($google_result_title['title'][0]) && !empty($google_result_description['description'][0]) && !empty($google_result_url['url'][0])) {

                    $google_search_result = array_merge($google_result_title, $google_result_description, $google_result_url);
                    $google_search_result = (!empty($google_search_result)) ? serialize($google_search_result) : null;

                } else {
                    $google_faq = (!empty($google_search_result)) ? serialize($google_search_result) : null;
                }

                // updating google_search_result in PostContent
                try {
                    $post_content->update([
                        'google_search_result' => $google_search_result,
                    ]);

                } catch (\Throwable $th) {
                    echo "Something bad with google_search_result <br>";

                }

            } else {

                ScrapingFailed::create([
                    'source_value' => $keyword,
                    'error'        => 'Duplicate Removed From DataBase Id:' . $duplicate_check->id,
                ]);

                dd("Duplicate Record Found");
            }
        }

    }
}
