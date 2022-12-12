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
        if (config('constant.RE_WRITE_ID') != 0) {
            dd("Please Change RE_WRITE_ID to 0 in .env file");
        }
        
        $post = Post::where('is_rewrite', 'pending')->first();

        if (empty($post)) {
            dd("no post found for rewrite");
        }

        $post->update([
            'is_rewrite' => 'doing',
        ]);

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
            'bing_images' => $oldContent->bing_images,
            'post_content_above' => $oldContent->post_content_above,
            'post_content_middle' => $oldContent->post_content_middle,
            'post_content_after' => $oldContent->post_content_after,
            'bing_paa_questions' => $oldContent->bing_paa_questions,
            'bing_pop_faq_questions' => $oldContent->bing_pop_faq_questions,
        ]);


        if (!empty($oldContent->bing_paa_answers)) {
            $bing_paa_answers_updated =null;
            foreach ($oldContent->bing_paa_answers as $key => $bing_paa_answers) {
                $bing_paa_answers_rewrite =  WordAi_API::response($bing_paa_answers);
                
                if ($bing_paa_answers_rewrite['status'] == 'Success') {
                    $bing_paa_answers_updated[] =  $bing_paa_answers_rewrite['rewrite'];
                }else{
                    $bing_paa_answers_updated[] = null;
                }
            }
            $rewriteContent->update([
                'bing_paa_answers' => $bing_paa_answers_updated,
            ]);
        }

        if (!empty($oldContent->bing_pop_faq_answers)) {
            $bing_pop_faq_answers_updated = Null;
            foreach ($oldContent->bing_pop_faq_answers as $key => $bing_pop_faq_answers) {
                $bing_pop_faq_answers_rewrite =  WordAi_API::response($bing_pop_faq_answers);
                if ( $bing_pop_faq_answers_rewrite['status'] == 'Success') {
                    $bing_pop_faq_answers_updated[] =  $bing_pop_faq_answers_rewrite['rewrite'];
                }else{
                    $bing_pop_faq_answers_updated[] = null;
                }
            }
            $rewriteContent->update([
                'bing_pop_faq_answers' => $bing_pop_faq_answers_updated,
            ]);
        }

        if (!empty($oldContent->bing_rich_snippet_text)) {
            $bing_rich_snippet_text_rewrite =  WordAi_API::response($oldContent->bing_rich_snippet_text);
            $bing_rich_snippet_text_updated = null;
            if ( $bing_rich_snippet_text_rewrite['status'] == 'Success') {
                $bing_rich_snippet_text_updated[] =  $bing_rich_snippet_text_rewrite['rewrite'];
            }
            $rewriteContent->update([
                'bing_rich_snippet_text' => $bing_rich_snippet_text_updated,
            ]);
        }


        if (!empty($oldContent->post_description)) {
            $post_description_rewrite =  WordAi_API::response($oldContent->post_description);

            $post_description_updated = null;
            if ( $post_description_rewrite['status'] == 'Success') {
                $post_description_updated[] =  $post_description_rewrite['rewrite'];
            }

            $rewriteContent->update([
                'post_description' => $post_description_updated,
            ]);
        }

        $post->update([
                'is_rewrite' => 'done',
        ]);
    }
}
