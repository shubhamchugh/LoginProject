<?php

namespace App\Http\Controllers;

use App\Models\PostContent;
use App\Models\JsonPostContent;

class SerializeToJsonController extends Controller
{
    public function JsonConvert()
    {
        $postContent_all = PostContent::orderBy('id', 'ASC')->limit(config('constant.TRANSFER_TO_JSON_COUNT'))->get();

        foreach ($postContent_all as $key => $postContent) {
            $key = ++$key;
            echo "<h1>($key)</h1>";

            $postContent->update([
                'is_json_transfer' => 1,
            ]);

            $json_post_content = JsonPostContent::create(
                [
                    'post_id' => $postContent->post_id,
                ]
            );

            $json_post_content->update([
                'fake_user_id' => $postContent->fake_user_id,
            ]);

            $json_post_content->update([
                'post_description' => $postContent->post_description,
            ]);

            $json_post_content->update([
                'post_thumbnail' => $postContent->post_thumbnail,
            ]);

            if (is_serialized($postContent->bing_related_keywords)) {
                $json_post_content->update([
                    'bing_related_keywords' => unserialize($postContent->bing_related_keywords),
                ]);
            } else {
                echo "bing_related_keywords Already Converted to Json Maybe <br>";
            }

            if (is_serialized($postContent->google_related_keywords)) {
                $json_post_content->update([
                    'google_related_keywords' => unserialize($postContent->google_related_keywords),
                ]);
            } else {
                echo "google_related_keywords Already Converted to Json Maybe<br>";
            }

            if (is_serialized($postContent->bing_news)) {
                $bing_news = unserialize($postContent->bing_news);

                $json_post_content->update([
                    'bing_news_title'       => $bing_news['title'],
                    'bing_news_description' => $bing_news['description'],
                ]);

            } else {
                echo "bing_news_title Already bing_news_description Converted to Json Maybe bing_news <br>";
            }

            if (is_serialized($postContent->bing_videos)) {
                $json_post_content->update([
                    'bing_videos' => unserialize($postContent->bing_videos),
                ]);
            } else {
                echo "Already Converted to Json Maybe bing_videos<br>";
            }

            if (is_serialized($postContent->bing_images)) {
                $bing_images_data = unserialize($postContent->bing_images);
                $json_post_content->update([
                    'bing_images' => $bing_images_data['images'],
                ]);
            } else {
                echo "bing_images Already Converted to Json Maybe bing_images <br>";
            }

            if (is_serialized($postContent->bing_search_result)) {
                $bing_search_result_data = unserialize($postContent->bing_search_result);

                $json_post_content->update([
                    'bing_search_result_title'       => $bing_search_result_data['result_title'][0],
                    'bing_search_result_description' => $bing_search_result_data['result_description'][0],
                    'bing_search_result_url'         => $bing_search_result_data['result_url'][0],
                ]);

            } else {
                echo "bing_search_result_title bing_search_result_description bing_search_result_url Already Converted to Json Maybe <br>";
            }

            if (is_serialized($postContent->bing_paa)) {
                $bing_paa_data = unserialize($postContent->bing_paa);

                $json_post_content->update([
                    'bing_paa_questions' => $bing_paa_data['paa_questions'][0],
                    'bing_paa_answers'   => $bing_paa_data['paa_Answers'][0],
                ]);
            } else {
                echo "bing_paa_questions bing_paa_answers Already Converted to Json Maybe <br>";
            }

            if (is_serialized($postContent->bing_rich_snippet)) {
                $bing_rich_snippet_data = unserialize($postContent->bing_rich_snippet);

                $json_post_content->update([
                    'bing_rich_snippet_text' => $bing_rich_snippet_data['bing_rich_snippet_text'][0][0],
                    'bing_rich_snippet_link' => $bing_rich_snippet_data['bing_rich_snippet_link'][0][0],
                ]);
            } else {
                echo "bing_rich_snippet_text bing_rich_snippet_link Already Converted to Json Maybe";
            }

            if (is_serialized($postContent->bing_slider_faq)) {
                $bing_slider_faq_data = unserialize($postContent->bing_slider_faq);

                $json_post_content->update([
                    'bing_slider_faq_questions' => $bing_slider_faq_data['slider_questions'][0],
                    'bing_slider_faq_answers'   => $bing_slider_faq_data['slider_answers'][0],
                ]);
            } else {
                echo "bing_slider_faq_questions bing_slider_faq_answers Already Converted to Json Maybe <br>";
            }

            if (is_serialized($postContent->bing_tab_faq)) {
                $bing_tab_faq_data = unserialize($postContent->bing_tab_faq);

                $json_post_content->update([
                    'bing_tab_faq_questions' => $bing_tab_faq_data['tab_questions'][0],
                    'bing_tab_faq_answers'   => $bing_tab_faq_data['tab_answers'][0],
                ]);
            } else {
                echo "bing_tab_faq_questions bing_tab_faq_answers Already Converted to Json Maybe <br>";
            }

            if (is_serialized($postContent->bing_pop_faq)) {
                $bing_pop_faq_data = unserialize($postContent->bing_pop_faq);

                $json_post_content->update([
                    'bing_pop_faq_questions' => $bing_pop_faq_data['pop_questions'][0],
                    'bing_pop_faq_answers'   => $bing_pop_faq_data['pop_answers'][0],
                ]);
            } else {
                echo "bing_pop_faq_questions bing_pop_faq_answers Already Converted to Json Maybe <br>";
            }

            if (is_serialized($postContent->google_rich_snippet)) {
                $google_rich_snippet_data = unserialize($postContent->google_rich_snippet);

                $json_post_content->update([
                    'google_rich_snippet' => $google_rich_snippet_data[0],
                ]);
            } else {
                echo "Already Converted to Json Maybe";
            }

            if (is_serialized($postContent->google_faq)) {
                $google_faq_data = unserialize($postContent->google_faq);

                $json_post_content->update([
                    'google_faq_questions' => $google_faq_data['questions'][0],
                    'google_faq_answers'   => $google_faq_data['answers'][0],
                ]);
            } else {
                echo "google_faq_questions google_faq_answers Already Converted to Json Maybe <br>";
            }

            // foreach ($postContent_all as $postContent) {
            //     if (is_serialized($postContent->google_rich_snippet)) {
            //         $json_post_content->update([
            //             'google_rich_snippet' => unserialize($postContent->google_rich_snippet),
            //         ]);
            //     } else {
            //         echo "Already Converted to Json Maybe";
            //     }
            // }

            if (is_serialized($postContent->google_search_result)) {
                $google_search_result_data = unserialize($postContent->google_search_result);

                $json_post_content->update([
                    'google_search_result_title'       => $google_search_result_data['title'][0],
                    'google_search_result_description' => $google_search_result_data['description'][0],
                    'google_search_result_url'         => $google_search_result_data['url'][0],
                ]);

            } else {
                echo "google_search_result Already Converted to Json Maybe <br>";
            }

        }

    }
}
