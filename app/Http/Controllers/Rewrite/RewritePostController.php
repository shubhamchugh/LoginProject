<?php

namespace App\Http\Controllers\Rewrite;

use App\Helpers\HelperClasses\WordAi\WordAi_API;
use App\Http\Controllers\Controller;
use App\Models\JsonPostContent;
use App\Models\Post;
use Illuminate\Http\Request;

class RewritePostController extends Controller
{
    public function index()
    {
        $post = Post::where('is_rewrite', 'pending')->first();

        $oldContent = $post->content->first();
                
        if ($oldContent->rewrite_id != 0) {
                dd("Already Rewrite Content OR Don't have content");
        }

        $rewriteContent = JsonPostContent::firstOrCreate([
                'rewrite_id' => 1,
                'post_id' => $oldContent->post_id,
                'fake_user_id' => $oldContent->fake_user_id,
        ]);

        $rewriteContent->update([
                'post_thumbnail' => $oldContent->post_thumbnail,
                // 'bing_related_keywords' => $oldContent->bing_related_keywords,
                // 'google_related_keywords' => $oldContent->google_related_keywords,
                // 'bing_videos' => $oldContent->bing_videos,
                'bing_images' => $oldContent->bing_images,
                // 'bing_news_title' => $oldContent->bing_news_title,
                // 'bing_news_description' => $oldContent->bing_news_description,
                // 'bing_search_result_title' => $oldContent->bing_search_result_title,
                // 'bing_search_result_description' =>$oldContent->bing_search_result_description,
                // 'bing_search_result_url' => $oldContent->bing_search_result_url,
                // 'google_search_result_title' => $oldContent->google_search_result_title,
                // 'google_search_result_description' => $oldContent->google_search_result_description,
                // 'google_search_result_url' => $oldContent->google_search_result_url,
                'post_content_above' => $oldContent->post_content_above,
                'post_content_middle' => $oldContent->post_content_middle,
                'post_content_after' => $oldContent->post_content_after,
                'bing_paa_questions' => $oldContent->bing_paa_questions,
                'bing_pop_faq_questions' => $oldContent->bing_pop_faq_questions,
        ]);

        if (!empty($oldContent->bing_paa_answers)) {
            foreach ($oldContent->bing_paa_answers as $key => $bing_paa_answers) {
                $bing_paa_answers_rewrite_array =  WordAi_API::response($bing_paa_answers);
                foreach ($bing_paa_answers_rewrite_array['rewrite'] as  $bing_paa_answers_rewrite) {
                    $bing_paa_answers_rewrite_new[] = $bing_paa_answers_rewrite;
                }
                $bing_paa_answers_updated[] =  implode(',', $bing_paa_answers_rewrite_new);
                $bing_paa_answers_rewrite_new = array();
            }
            $rewriteContent->update([
                'bing_paa_answers' => $bing_paa_answers_updated,
            ]);
        }

        if (!empty($oldContent->bing_pop_faq_answers)) {
            foreach ($oldContent->bing_pop_faq_answers as $key => $bing_pop_faq_answers) {
                $bing_pop_faq_answers_rewrite_array =  WordAi_API::response($bing_pop_faq_answers);
                foreach ($bing_pop_faq_answers_rewrite_array['rewrite'] as  $bing_pop_faq_answers_rewrite) {
                    $bing_pop_faq_answers_rewrite_new[] = $bing_pop_faq_answers_rewrite;
                }
                $bing_pop_faq_answers_updated[] =  implode(',', $bing_pop_faq_answers_rewrite_new);
                $bing_pop_faq_answers_rewrite_new = array();
            }
            $rewriteContent->update([
                'bing_pop_faq_answers' => $bing_pop_faq_answers_updated,
            ]);
        }

        if (!empty($oldContent->bing_rich_snippet_text)) {
            $bing_rich_snippet_text_rewrite_array =  WordAi_API::response($oldContent->bing_rich_snippet_text);
            foreach ($bing_rich_snippet_text_rewrite_array['rewrite'] as  $bing_rich_snippet_text_rewrite) {
                $bing_rich_snippet_text_rewrite_new[] = $bing_rich_snippet_text_rewrite;
            }
            $bing_rich_snippet_text_updated[] =  implode(',', $bing_rich_snippet_text_rewrite_new);
            $bing_rich_snippet_text_rewrite_new = array();

            $rewriteContent->update([
                'bing_rich_snippet_text' => $bing_rich_snippet_text_updated,
            ]);
        }

        $post->update([
                'is_rewrite' => 'done',
        ]);
    }
}
