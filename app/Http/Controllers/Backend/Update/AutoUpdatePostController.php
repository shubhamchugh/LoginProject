<?php

namespace App\Http\Controllers\Backend\Update;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\FakeUser;
use App\Models\IpRecord;
use App\Models\PostContent;
use App\Models\ScrapingFailed;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AutoUpdatePostController extends Controller
{

    /*************************************************
     * UPDATE EXISTING POST CONTENT USING THIS MTHOD *
     *************************************************/

    public static function update_existing($post_content_id, $keyword)
    {
        $ip = IpRecord::where('status', 'OK')->inRandomOrder()->first();
        if (empty($ip->ip_address)) {
            die("Please Add New ip in DataBase to Scrape");
        }
        $ip->update([
            'status' => 'SCRAPING',
        ]);
        echo "Post_Content_id: $post_content_id<br>";
        echo "Keyword: $keyword<br>";
        echo "We are updating post For better experience Please Refresh Page<br>";

        $post_content = PostContent::where('id', $post_content_id)->first();

        // try to save thumbnail_images in database
        try {
            $Bing_image = 'http://' . $ip->ip_address . ':3000/bing-thumb?url=https://www.bing.com/images/search?q=' . str_replace(' ', '+', $keyword) . '&qft=+filterui:aspect-wide&first=1&tsc=ImageBasicHover';
            $thumbnail  = Http::timeout(60)->get($Bing_image)->body();

            $thumbnail = (!empty($thumbnail)) ? $thumbnail : "default.jpg";

            echo "<br>Thumbnail: <br>";
            echo "$thumbnail<br>";

            //Updating thumbnail_images in database
            try {
                $post_content->update([
                    'post_thumbnail' => $thumbnail,
                ]);

            } catch (\Throwable $th) {
                echo "Fail to store Bing thumbnail In database check: $Bing_image<br>";

            }
        } catch (\Throwable $th) {
            echo "Something bad With thumbnail_images Please check: $Bing_image <br>";

        }

        // try to save images for bing_images
        try {
            $Bing_image_url = 'http://' . $ip->ip_address . ':3000/bing-images?url=https://www.bing.com/images/search?q=' . str_replace(' ', '+', $keyword);
            $Bing_image     = Http::timeout(60)->get($Bing_image_url)->body();
            $Bing_image     = json_decode($Bing_image, true);

            if (!empty($Bing_image['images'][0])) {
                $images = (!empty($Bing_image)) ? serialize($Bing_image) : null;
            } else {
                $images = (!empty($Bing_image)) ? serialize($Bing_image) : null;
            }

            echo "<br>Images: <br>";
            print_r($images);

            //Updating images in database
            try {
                $post_content->update([
                    'bing_images' => $images,
                ]);

            } catch (\Throwable $th) {
                echo "Fail to store Bing images In database check:$Bing_image_url<br>";
            }
        } catch (\Throwable $th) {

            echo "Something bad With Bing images Please check: $Bing_image_url <br>";

        }

        // try to update New From bing News search
        try {
            $newsUrl   = 'http://' . $ip->ip_address . ':3000/bing-news?url=https://www.bing.com/news/search?q=' . str_replace(' ', '+', $keyword);
            $bing_news = Http::timeout(60)->get($newsUrl)->body();
            $bing_news = json_decode($bing_news, true);

            echo "<br>bing news<br>";
            print_r($bing_news);

            $bing_news = (!empty($bing_news)) ? serialize($bing_news) : null;

            //Updating news in database
            try {
                $post_content->update([
                    'bing_news' => $bing_news,
                ]);

            } catch (\Throwable $th) {
                echo "Fail to store Bing News In database check: $newsUrl<br>";

            }

        } catch (\Throwable $th) {
            echo "Something bad With Bing News Please check: $newsUrl <br>";

        }

        //try to update video from bing search
        try {

            $videoUrl    = 'http://' . $ip->ip_address . ':3000/bing-videos?url=https://www.bing.com/videos/search?q=' . str_replace(' ', '+', $keyword) . '&qft=+filterui:msite-youtube.com';
            $bing_videos = Http::timeout(60)->get($videoUrl)->body();
            $bing_videos = json_decode($bing_videos, true);

            echo "<br>Bing videos<br>";
            print_r($bing_videos);

            $bing_videos = (!empty($bing_videos)) ? serialize($bing_videos) : null;

            //Updating Videos in database
            try {
                $post_content->update([
                    'bing_videos' => $bing_videos,
                ]);

            } catch (\Throwable $th) {
                echo "Fail to store Bing Video in Database please check: $videoUrl<br>";

            }
        } catch (\Throwable $th) {
            echo "some thing bad with Bing Video Search Please check: $videoUrl <br>";

        }

        // hit to google api and get data for google faq,rich_snippet,search results
        try {
            $api_url_google  = 'http://' . $ip->ip_address . ':3000/google?url=https://www.google.com/search?q=' . str_replace(' ', '+', $keyword);
            $api_data_google = Http::timeout(60)->get($api_url_google)->body();

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

        // hit to Bing Api
        try {
            $api_url_bing = 'http://' . $ip->ip_address . ':3000/bing?url=https://www.bing.com/search?q=' . str_replace(' ', '+', $keyword);
            $api_data     = Http::timeout(60)->get($api_url_bing)->body();

            $bing_data = json_decode($api_data, true);

            $bing_related_keywords = (!empty($bing_data['relatedKeywords'])) ? serialize($bing_data['relatedKeywords']) : null;

        } catch (\Throwable $th) {
            $ip->update([
                'status' => 'NOT_WORKING',
            ]);
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
            $ip->update([
                'status' => 'OK',
                'ERROR'  => null,
            ]);

        } else {
            $ip->update([
                'status' => 'NOT_WORKING',
                'ERROR'  => 'DATA NOT FOUND UPDATING NOT FOUND DATA',
            ]);

            $error_msg = 'API_URL' . $api_url_bing . '<br>TITLE: ' . $result_title . '<br>DEC: ' . $result_description . '<br>url: ' . $result_url;
            $ip->update([
                'status' => 'NOT_WORKING',
                'ERROR'  => $error_msg,
            ]);
            $bing_search_result = (!empty($bing_search_result)) ? serialize($bing_search_result) : null;
        }
        $post_description = (!empty($result_description['result_description'][0][0])) ? $result_description['result_description'][0][0] : null;
        // updating bing_search_result in PostContent
        try {
            $post_content->update([
                'bing_search_result' => $bing_search_result,
                'post_description'   => $post_description,
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

    }

    /****************************************************************************
     * UPDATE AND CREATE POST CONTENT AFTER N NUMBERS OF DAYS USING THIS METHOD *
     ****************************************************************************/

    public static function update_and_create($post_id, $keyword)
    {
        $ip = IpRecord::where('status', 'OK')->inRandomOrder()->first();
        if (empty($ip->ip_address)) {
            dd("Please Add New ip in DataBase to Scrape");
        }

        $ip->update([
            'status' => 'SCRAPING',
        ]);
        echo "We are updating post For better experience Please Refresh Page";

        $totalFakeUser = FakeUser::count();
        $post_content  = PostContent::create([
            'post_id'      => $post_id,
            'fake_user_id' => mt_rand(1, $totalFakeUser),
        ]);

        // try to save thumbnail_images in database
        try {
            $Bing_image = 'http://' . $ip->ip_address . ':3000/bing-thumb?url=https://www.bing.com/images/search?q=' . str_replace(' ', '+', $keyword) . '&qft=+filterui:aspect-wide';
            $thumbnail  = Http::timeout(60)->get($Bing_image)->body();

            $thumbnail = (!empty($thumbnail)) ? $thumbnail : "default.jpg";

            echo "<br>Thumbnail: <br>";
            echo "$thumbnail<br>";

            //Updating thumbnail_images in database
            try {
                $post_content->update([
                    'post_thumbnail' => $thumbnail,
                ]);

            } catch (\Throwable $th) {
                echo "Fail to store Bing thumbnail In database check: $Bing_image<br>";

            }
        } catch (\Throwable $th) {

            echo "Something bad With thumbnail_images Please check: $Bing_image <br>";

        }

        // try to save images for bing_images
        try {
            $Bing_image_url = 'http://' . $ip->ip_address . ':3000/bing-images?url=https://www.bing.com/images/search?q=' . str_replace(' ', '+', $keyword);
            $Bing_image     = Http::timeout(60)->get($Bing_image_url)->body();
            $Bing_image     = json_decode($Bing_image, true);

            if (!empty($Bing_image['images'][0])) {
                $images = (!empty($Bing_image)) ? serialize($Bing_image) : null;
            } else {
                $images = (!empty($Bing_image)) ? serialize($Bing_image) : null;
            }

            echo "<br>Images: <br>";
            print_r($images);

            //Updating images in database
            try {
                $post_content->update([
                    'bing_images' => $images,
                ]);

            } catch (\Throwable $th) {
                echo "Fail to store Bing images In database check:$Bing_image_url<br>";
            }
        } catch (\Throwable $th) {

            echo "Something bad With Bing images Please check: $Bing_image_url <br>";

        }

        // try to update New From bing News search
        try {
            $newsUrl   = 'http://' . $ip->ip_address . ':3000/bing-news?url=https://www.bing.com/news/search?q=' . str_replace(' ', '+', $keyword);
            $bing_news = Http::timeout(60)->get($newsUrl)->body();
            $bing_news = json_decode($bing_news, true);

            echo "<br>bing news<br>";
            print_r($bing_news);

            $bing_news = (!empty($bing_news)) ? serialize($bing_news) : null;

            //Updating news in database
            try {
                $post_content->update([
                    'bing_news' => $bing_news,
                ]);

            } catch (\Throwable $th) {
                echo "Fail to store Bing News In database check: $newsUrl<br>";

            }

        } catch (\Throwable $th) {
            echo "Something bad With Bing News Please check: $newsUrl <br>";

        }

        //try to update video from bing search
        try {

            $videoUrl    = 'http://' . $ip->ip_address . ':3000/bing-videos?url=https://www.bing.com/videos/search?q=' . str_replace(' ', '+', $keyword) . '&qft=+filterui:msite-youtube.com';
            $bing_videos = Http::timeout(60)->get($videoUrl)->body();
            $bing_videos = json_decode($bing_videos, true);

            echo "<br>Bing videos<br>";
            print_r($bing_videos);

            $bing_videos = (!empty($bing_videos)) ? serialize($bing_videos) : null;

            //Updating Videos in database
            try {
                $post_content->update([
                    'bing_videos' => $bing_videos,
                ]);

            } catch (\Throwable $th) {
                echo "Fail to store Bing Video in Database please chec: $videoUrl<br>";

            }
        } catch (\Throwable $th) {
            echo "some thing bad with Bing Video Search Please check: $videoUrl <br>";

        }

        //google api hit to get google data
        try {
            $api_url_google  = 'http://' . $ip->ip_address . ':3000/google?url=https://www.google.com/search?q=' . str_replace(' ', '+', $keyword);
            $api_data_google = Http::timeout(60)->get($api_url_google)->body();

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

        // hit to Bing Api
        try {
            $api_url_bing = 'http://' . $ip->ip_address . ':3000/bing?url=https://www.bing.com/search?q=' . str_replace(' ', '+', $keyword);
            $api_data     = Http::timeout(60)->get($api_url_bing)->body();

            $bing_data = json_decode($api_data, true);

            $bing_related_keywords = (!empty($bing_data['relatedKeywords'])) ? serialize($bing_data['relatedKeywords']) : null;

        } catch (\Throwable $th) {

            $ip->update([
                'status' => 'NOT_WORKING',
            ]);

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

            $ip->update([
                'status' => 'OK',
                'ERROR'  => null,
            ]);
        } else {
            $ip->update([
                'status' => 'NOT_WORKING',
                'ERROR'  => 'DATA NOT FOUND UPDATING NOT FOUND DATA',
            ]);
            $error_msg = 'API_URL' . $api_url_bing . '<br>TITLE: ' . $result_title . '<br>DEC: ' . $result_description . '<br>url: ' . $result_url;
            $ip->update([
                'status' => 'NOT_WORKING',
                'ERROR'  => $error_msg,
            ]);
            $bing_search_result = (!empty($bing_search_result)) ? serialize($bing_search_result) : null;
        }
        $post_description = (!empty($result_description['result_description'][0][0])) ? $result_description['result_description'][0][0] : null;
        // updating bing_search_result in PostContent
        try {
            $post_content->update([
                'bing_search_result' => $bing_search_result,
                'post_description'   => $post_description,
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

    }

    /*********************************************************************
     * ADD POST IF NOT FOUND IN DATABASE USE IN RELATED KEYWORD FUNCTION *
     *********************************************************************/

    public static function addPost($slug)
    {

        $ip = IpRecord::where('status', 'OK')->inRandomOrder()->first();
        if (empty($ip->ip_address)) {
            die("Please Add New ip in DataBase to Scrape");
        }

        $ip->update([
            'status' => 'SCRAPING',
        ]);
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

                // try to save thumbnail_images in database
                try {
                    $Bing_image = 'http://' . $ip->ip_address . ':3000/bing-thumb?url=https://www.bing.com/images/search?q=' . str_replace(' ', '+', $keyword) . '&qft=+filterui:aspect-wide';
                    $thumbnail  = Http::timeout(60)->get($Bing_image)->body();

                    $thumbnail = (!empty($thumbnail)) ? $thumbnail : "default.jpg";

                    echo "<br>Thumbnail: <br>";
                    echo "$thumbnail<br>";

                    //Updating thumbnail_images in database
                    try {
                        $post_content->update([
                            'post_thumbnail' => $thumbnail,
                        ]);

                    } catch (\Throwable $th) {
                        echo "Fail to store Bing thumbnail In database check: $Bing_image<br>";

                    }
                } catch (\Throwable $th) {

                    echo "Something bad With thumbnail_images Please check: $Bing_image <br>";

                }

                // try to save images for bing_images
                try {
                    $Bing_image_url = 'http://' . $ip->ip_address . ':3000/bing-images?url=https://www.bing.com/images/search?q=' . str_replace(' ', '+', $keyword);
                    $Bing_image     = Http::timeout(60)->get($Bing_image_url)->body();
                    $Bing_image     = json_decode($Bing_image, true);

                    if (!empty($Bing_image['images'][0])) {
                        $images = (!empty($Bing_image)) ? serialize($Bing_image) : null;
                    } else {
                        $images = (!empty($Bing_image)) ? serialize($Bing_image) : null;
                    }

                    echo "<br>Images: <br>";
                    print_r($images);

                    //Updating images in database
                    try {
                        $post_content->update([
                            'bing_images' => $images,
                        ]);

                    } catch (\Throwable $th) {
                        echo "Fail to store Bing images In database check:$Bing_image_url<br>";
                    }
                } catch (\Throwable $th) {

                    echo "Something bad With Bing images Please check: $Bing_image_url <br>";

                }

                // try to update New From bing News search
                try {
                    $newsUrl   = 'http://' . $ip->ip_address . ':3000/bing-news?url=https://www.bing.com/news/search?q=' . str_replace(' ', '+', $keyword);
                    $bing_news = Http::timeout(60)->get($newsUrl)->body();
                    $bing_news = json_decode($bing_news, true);

                    echo "<br>bing news<br>";
                    print_r($bing_news);

                    $bing_news = (!empty($bing_news)) ? serialize($bing_news) : null;

                    //Updating news in database
                    try {
                        $post_content->update([
                            'bing_news' => $bing_news,
                        ]);

                    } catch (\Throwable $th) {
                        echo "Fail to store Bing News In database check: $newsUrl<br>";

                    }

                } catch (\Throwable $th) {
                    echo "Something bad With Bing News Please check: $newsUrl <br>";

                }

                //try to update video from bing search
                try {

                    $videoUrl    = 'http://' . $ip->ip_address . ':3000/bing-videos?url=https://www.bing.com/videos/search?q=' . str_replace(' ', '+', $keyword) . '&qft=+filterui:msite-youtube.com';
                    $bing_videos = Http::timeout(60)->get($videoUrl)->body();
                    $bing_videos = json_decode($bing_videos, true);

                    echo "<br>Bing videos<br>";
                    print_r($bing_videos);

                    $bing_videos = (!empty($bing_videos)) ? serialize($bing_videos) : null;

                    //Updating Videos in database
                    try {
                        $post_content->update([
                            'bing_videos' => $bing_videos,
                        ]);

                    } catch (\Throwable $th) {
                        echo "Fail to store Bing Video in Database please chec: $videoUrl<br>";

                    }
                } catch (\Throwable $th) {
                    echo "some thing bad with Bing Video Search Please check: $videoUrl <br>";

                }

                try {
                    $api_url_google  = 'http://' . $ip->ip_address . ':3000/google?url=https://www.google.com/search?q=' . str_replace(' ', '+', $keyword);
                    $api_data_google = Http::timeout(60)->get($api_url_google)->body();

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

                // hit to Bing Api
                try {
                    $api_url_bing = 'http://' . $ip->ip_address . ':3000/bing?url=https://www.bing.com/search?q=' . str_replace(' ', '+', $keyword);
                    $api_data     = Http::timeout(60)->get($api_url_bing)->body();

                    $bing_data = json_decode($api_data, true);

                    $bing_related_keywords = (!empty($bing_data['relatedKeywords'])) ? serialize($bing_data['relatedKeywords']) : null;

                } catch (\Throwable $th) {

                    $ip->update([
                        'status' => 'NOT_WORKING',
                    ]);

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

                    $ip->update([
                        'status' => 'OK',
                        'ERROR'  => null,
                    ]);

                } else {
                    $bing_search_result = (!empty($bing_search_result)) ? serialize($bing_search_result) : null;
                    $ip->update([
                        'status' => 'NOT_WORKING',
                        'ERROR'  => 'DATA NOT FOUND UPDATING NOT FOUND DATA',
                    ]);
                    $error_msg = 'API_URL' . $api_url_bing . '<br>TITLE: ' . $result_title . '<br>DEC: ' . $result_description . '<br>url: ' . $result_url;
                    $ip->update([
                        'status' => 'NOT_WORKING',
                        'ERROR'  => $error_msg,
                    ]);
                }
                $post_description = (!empty($result_description['result_description'][0][0])) ? $result_description['result_description'][0][0] : null;
// updating bing_search_result in PostContent
                try {
                    $post_content->update([
                        'bing_search_result' => $bing_search_result,
                        'post_description'   => $post_description,
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
