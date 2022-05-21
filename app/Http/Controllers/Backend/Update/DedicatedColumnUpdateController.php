<?php

namespace App\Http\Controllers\Backend\Update;

use App\Models\IpRecord;
use App\Models\JsonPostContent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DedicatedColumnUpdateController extends Controller
{
    public function is_bing_results()
    {
        $post_content = JsonPostContent::whereNull('bing_search_result')->where('is_bing_results', '0')->orderBy('updated_at', 'asc')->first();
        if (empty($post_content)) {
            dd("No Record Found to Update is_bing_results");
        }

        $keyword = $post_content->post->source_value;
        $slug    = (!empty(config('constant.POST_SLUG'))) ? '/' . config('constant.POST_SLUG') : config('constant.POST_SLUG');
        $url     = url($slug . '/' . $post_content->post->slug);

        echo "<a href='$url' target='_blank'>$url</a>";

        $ip = IpRecord::where('status', 'OK')->inRandomOrder()->first();

        if (empty($ip->ip_address)) {
            dd("Please Add New ip in DataBase to Scrape");
        }

        $ip->update([
            'status' => 'SCRAPING',
            'ERROR'  => request()->getHost(),
        ]);

        $post_content->update([
            'is_bing_results' => 1,
        ]);

        // * hit to Bing Api Start
        $api_url_bing = 'http://' . $ip->ip_address . ':3000/bing?url=https://www.bing.com/search?q=' . str_replace(' ', '+', $keyword);
        echo "Bing Api Url: $api_url_bing<br>";
        try {
            $api_data = Http::timeout(90)->get($api_url_bing)->body();

            $bing_data = json_decode($api_data, true);

            echo "Bing APi Data :<br>";
            print_r($bing_data);

            $ip->update([
                'status'       => 'OK',
                'ERROR'        => null,
                'scrape_count' => DB::raw('scrape_count + 1'),
            ]);

            $bing_related_keywords = (!empty($bing_data['relatedKeywords'])) ? ($bing_data['relatedKeywords']) : null;

        } catch (\Throwable $th) {
            echo "Bing Api not responding properly Please check api manually:  $api_url_bing <br>";

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

        } catch (\Throwable $th) {
            echo "fail to update Bing_keywords<br>";

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

        } catch (\Throwable $th) {
            echo "Something bad with search results Please check Api: $api_url_bing<br>";

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

        } catch (\Throwable $th) {
            echo "Something bad with People Also Ask Please check Api: $api_url_bing<br>";

        }
        //~ updating bing_paa in PostContent end

        // * updating bing_rich_snippet in PostContent start
        $bing_rich_snippet_text['bing_rich_snippet_text'] = (!empty($bing_data['richSnippet'][0])) ? preg_replace('/<img[^>]+\>/i', ' ', $bing_data['richSnippet'][0]) : null;
        $bing_rich_snippet_link['bing_rich_snippet_link'] = (!empty($bing_data['richSnippetLink'][0])) ? $bing_data['richSnippetLink'][0] : null;

        try {
            $post_content->update([
                'bing_rich_snippet_text' => $bing_rich_snippet_text['bing_rich_snippet_text'],
                'bing_rich_snippet_link' => $bing_rich_snippet_link['bing_rich_snippet_link'],
            ]);

        } catch (\Throwable $th) {
            echo "Something bad with People Also Ask Please check Api: $api_url_bing<br>";

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

        } catch (\Throwable $th) {
            echo "Something bad with People Also Ask Please check APi: $api_url_bing<br>";

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

        } catch (\Throwable $th) {
            echo "Something bad with People Also Ask Please Check Api<br>";

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

        } catch (\Throwable $th) {
            echo "Something bad with People Also Ask Please Check api: $api_url_bing<br>";

        }
        //~ updating bing_tab in PostContent end

    }

    public function is_thumbnail_images()
    {

        $post_content = JsonPostContent::whereNull('post_thumbnail')->where('is_thumbnail_images', '0')->orderBy('updated_at', 'asc')->first();
        if (empty($post_content)) {
            dd("No Record Found to Update is_thumbnail_images ");
        }
        $keyword = $post_content->post->source_value;
        $slug    = (!empty(config('constant.POST_SLUG'))) ? '/' . config('constant.POST_SLUG') : config('constant.POST_SLUG');
        $url     = url($slug . '/' . $post_content->post->slug);
        echo "<br>$url<br>";

        $ip = IpRecord::where('status', 'OK')->inRandomOrder()->first();
        if (empty($ip->ip_address)) {
            dd("Please Add New ip in DataBase to Scrape");
        }

        $ip->update([
            'status' => 'SCRAPING',
            'ERROR'  => request()->getHost(),
        ]);

        $post_content->update([
            'is_thumbnail_images' => 1,
        ]);
        // * try to save thumbnail_images in database start
        $Bing_image = 'http://' . $ip->ip_address . ':3000/bing-thumb?url=https://www.bing.com/images/search?q=' . str_replace(' ', '+', $keyword) . '&qft=+filterui:aspect-wide&first=1&tsc=ImageBasicHover';
        try {

            $thumbnail = Http::timeout(90)->get($Bing_image)->body();

            $thumbnail = (!empty($thumbnail)) ? $thumbnail : "default.jpg";

            echo "<br>Thumbnail: <br>";
            echo "$thumbnail<br>";

            $ip->update([
                'status'       => 'OK',
                'ERROR'        => null,
                'scrape_count' => DB::raw('scrape_count + 1'),
            ]);
            //Updating thumbnail_images in database
            try {
                $post_content->update([
                    'post_thumbnail' => $thumbnail,
                ]);

            } catch (\Throwable $th) {
                echo "Fail to store Bing thumbnail In database check: $Bing_image<br>";
                $ip->update([
                    'status' => 'NOT_WORKING',
                    'ERROR'  => 'thumbnail_images DATA Not Get: ' . $Bing_image,
                ]);
                echo "Fail to store Bing thumbnail In database check: $Bing_image<br>";
            }

        } catch (\Throwable $th) {
            $ip->update([
                'status' => 'NOT_WORKING',
                'ERROR'  => 'thumbnail_images response Not Get: ' . $Bing_image,
            ]);
            echo "Something bad With thumbnail_images Please check: $Bing_image <br>";

        }
        //~ try to save thumbnail_images for bing_images end
    }

    public function is_bing_images()
    {
        $post_content = JsonPostContent::whereNull('bing_images')->where('is_bing_images', '0')->orderBy('updated_at', 'asc')->first();

        if (empty($post_content)) {
            dd("No Record Found to Update is_bing_images ");
        }

        $keyword = $post_content->post->source_value;
        $slug    = (!empty(config('constant.POST_SLUG'))) ? '/' . config('constant.POST_SLUG') : config('constant.POST_SLUG');
        $url     = url($slug . '/' . $post_content->post->slug);
        echo "<br>$url<br>";

        $ip = IpRecord::where('status', 'OK')->inRandomOrder()->first();
        if (empty($ip->ip_address)) {
            dd("Please Add New ip in DataBase to Scrape");
        }

        $ip->update([
            'status' => 'SCRAPING',
            'ERROR'  => request()->getHost(),
        ]);

        $post_content->update([
            'is_thumbnail_images' => 1,
        ]);

        $post_content->update([
            'is_bing_images' => 1,
        ]);

        //* try to save images for bing_images start
        $Bing_image_url = 'http://' . $ip->ip_address . ':3000/bing-images?url=https://www.bing.com/images/search?q=' . str_replace(' ', '+', $keyword);
        try {

            $Bing_image = Http::timeout(90)->get($Bing_image_url)->body();
            $Bing_image = json_decode($Bing_image, true);

            if (!empty($Bing_image['images'][0])) {
                $images = (!empty($Bing_image)) ? $Bing_image : null;
            } else {
                $images = (!empty($Bing_image)) ? $Bing_image : null;
            }
            $ip->update([
                'status'       => 'OK',
                'ERROR'        => null,
                'scrape_count' => DB::raw('scrape_count + 1'),
            ]);

            echo "<br>Images: <br>";
            print_r($images);

            //Updating images in database
            try {
                $post_content->update([
                    'bing_images' => $images['images'],
                ]);

            } catch (\Throwable $th) {
                $ip->update([
                    'status' => 'NOT_WORKING',
                    'ERROR'  => 'Bing_image_url DATA Not Get: ' . $Bing_image_url,
                ]);
                echo "Fail to store Bing images In database check:$Bing_image_url<br>";

            }
        } catch (\Throwable $th) {
            $ip->update([
                'status' => 'NOT_WORKING',
                'ERROR'  => 'Bing_image_url response Not Get: ' . $Bing_image_url,
            ]);
            echo "Something bad With Bing images Please check: $Bing_image_url <br>";

        }
        //~ try to save images for bing_images end
    }

    public function is_bing_news()
    {
        $post_content = JsonPostContent::whereNull('bing_news')->where('is_bing_news', '0')->orderBy('updated_at', 'asc')->first();
        if (empty($post_content)) {
            dd("No Record Found to Update is_bing_news");
        }

        $keyword = $post_content->post->source_value;
        $slug    = (!empty(config('constant.POST_SLUG'))) ? '/' . config('constant.POST_SLUG') : config('constant.POST_SLUG');
        $url     = url($slug . '/' . $post_content->post->slug);

        echo "<br>$url<br>";

        $ip = IpRecord::where('status', 'OK')->inRandomOrder()->first();
        if (empty($ip->ip_address)) {
            dd("Please Add New ip in DataBase to Scrape");
        }

        $ip->update([
            'status' => 'SCRAPING',
            'ERROR'  => request()->getHost(),
        ]);

        $post_content->update([
            'is_bing_news' => 1,
        ]);

        //* try to update New From bing News search start
        $newsUrl = 'http://' . $ip->ip_address . ':3000/bing-news?url=https://www.bing.com/news/search?q=' . str_replace(' ', '+', $keyword);
        try {

            $bing_news = Http::timeout(90)->get($newsUrl)->body();
            $bing_news = json_decode($bing_news, true);

            $ip->update([
                'status'       => 'OK',
                'ERROR'        => null,
                'scrape_count' => DB::raw('scrape_count + 1'),
            ]);

            echo "<br>bing news<br>";
            print_r($bing_news);

            $bing_news = (!empty($bing_news)) ? $bing_news : null;

            //Updating news in database
            try {
                $post_content->update([
                    'bing_news_title'       => $bing_news['title'],
                    'bing_news_description' => $bing_news['description'],

                ]);

            } catch (\Throwable $th) {
                $ip->update([
                    'status' => 'NOT_WORKING',
                    'ERROR'  => 'newsUrl data Not Get: ' . $newsUrl,
                ]);
                echo "Fail to store Bing News In database check: $newsUrl<br>";

            }

        } catch (\Throwable $th) {
            $ip->update([
                'status' => 'NOT_WORKING',
                'ERROR'  => 'newsUrl response Not Get: ' . $newsUrl,
            ]);
            echo "Something bad With Bing News Please check: $newsUrl <br>";

        }
        //~ try to update New From bing News search end
    }

    public function is_bing_video()
    {
        $post_content = JsonPostContent::whereNull('bing_videos')->where('is_bing_video', '0')->orderBy('updated_at', 'asc')->first();
        if (empty($post_content)) {
            dd("No Record Found to Update is_bing_video");
        }

        $keyword = $post_content->post->source_value;
        $slug    = (!empty(config('constant.POST_SLUG'))) ? '/' . config('constant.POST_SLUG') : config('constant.POST_SLUG');
        $url     = url($slug . '/' . $post_content->post->slug);

        echo "<br>$url<br>";

        $ip = IpRecord::where('status', 'OK')->inRandomOrder()->first();
        if (empty($ip->ip_address)) {
            dd("Please Add New ip in DataBase to Scrape");
        }

        $ip->update([
            'status' => 'SCRAPING',
            'ERROR'  => request()->getHost(),
        ]);

        $post_content->update([
            'is_bing_video' => 1,
        ]);

        //* try to update video from bing search start
        $videoUrl = 'http://' . $ip->ip_address . ':3000/bing-videos?url=https://www.bing.com/videos/search?q=' . str_replace(' ', '+', $keyword) . '&qft=+filterui:msite-youtube.com';
        try {
            $bing_videos = Http::timeout(90)->get($videoUrl)->body();
            $bing_videos = json_decode($bing_videos, true);

            echo "<br>Bing videos<br>";
            print_r($bing_videos);

            $ip->update([
                'status'       => 'OK',
                'ERROR'        => null,
                'scrape_count' => DB::raw('scrape_count + 1'),
            ]);

            $bing_videos = (!empty($bing_videos)) ? ($bing_videos) : null;

            //Updating Videos in database
            try {
                $post_content->update([
                    'bing_videos' => $bing_videos,
                ]);

            } catch (\Throwable $th) {
                echo "Fail to store Bing Video in Database please chec: $videoUrl<br>";

                $ip->update([
                    'status' => 'NOT_WORKING',
                    'ERROR'  => 'videoUrl data Not Get: ' . $videoUrl,
                ]);
            }
        } catch (\Throwable $th) {

            $ip->update([
                'status' => 'NOT_WORKING',
                'ERROR'  => 'videoUrl response Not Get: ' . $videoUrl,
            ]);
            echo "some thing bad with Bing Video Search Please check: $videoUrl <br>";

        }
        //~ try to update video from bing search end
    }

    public function is_google_results()
    {
        $post_content = JsonPostContent::whereNull('google_search_result')->where('is_google_results', '0')->orderBy('updated_at', 'asc')->first();
        if (empty($post_content)) {
            dd("No Record Found to Update is_google_results");
        }
        $keyword = $post_content->post->source_value;
        $slug    = (!empty(config('constant.POST_SLUG'))) ? '/' . config('constant.POST_SLUG') : config('constant.POST_SLUG');
        $url     = url($slug . '/' . $post_content->post->slug);

        echo "<br>$url<br>";

        $ip = IpRecord::where('status', 'OK')->inRandomOrder()->first();
        if (empty($ip->ip_address)) {
            die("Please Add New ip in DataBase to Scrape");
        }

        $ip->update([
            'status' => 'SCRAPING',
            'ERROR'  => request()->getHost(),
        ]);

        $post_content->update([
            'is_google_results' => 1,
        ]);

        //* Hit to google api and get data for google faq,rich_snippet,search results start
        $api_url_google = 'http://' . $ip->ip_address . ':3000/google?url=https://www.google.com/search?q=' . str_replace(' ', '+', $keyword);
        try {

            echo "Google APi Url: $api_url_google<br>";
            $api_data_google = Http::timeout(90)->get($api_url_google)->body();

            $google_data = json_decode($api_data_google, true);

            echo "Google Api Data:<br>";
            print_r($google_data);
            $ip->update([
                'status'       => 'OK',
                'ERROR'        => null,
                'scrape_count' => DB::raw('scrape_count + 1'),
            ]);

            $google_related_keywords = (!empty($google_data['relatedKeywordsGoogle'])) ? ($google_data['relatedKeywordsGoogle']) : null;
            $google_rich_snippet     = (!empty($google_data['richSnippetGoogle'][0])) ? ($google_data['richSnippetGoogle'][0]) : null;

        } catch (\Throwable $th) {
            $ip->update([
                'status' => 'NOT_WORKING',
                'ERROR'  => 'api_url_google response Not Get: ' . $api_url_google,
            ]);
            echo "Something bad with google.com Please check: $api_url_google <br>";

        }
        //~ Hit to google api and get data for google faq,rich_snippet,search results end

        //* updating google_related_keywords in PostContent start
        try {
            $post_content->update([
                'google_related_keywords' => $google_related_keywords,
            ]);

        } catch (\Throwable $th) {
            echo "Something bad with google_related_keywords Please Check:  $api_url_google<br>";

        }
        //~ updating google_related_keywords in PostContent end

        //* updating google_rich_snippet in PostContent start
        try {
            $post_content->update([
                'google_rich_snippet' => $google_rich_snippet,
            ]);

        } catch (\Throwable $th) {
            echo "Something bad with google_related_keywords Please Check:  $api_url_google<br>";

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

        } catch (\Throwable $th) {
            echo "Something bad with People Also Ask Please Check:  $api_url_google<br>";

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

        } catch (\Throwable $th) {
            echo "Something bad with google_search_result Please Check: $api_url_google <br>";

        }
        // ~ updating google_search_result in PostContent end

    }

}
