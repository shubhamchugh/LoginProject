<?php

namespace App\Http\Controllers\Backend\Update;

use Illuminate\Http\Request;
use App\Models\JsonPostContent;
use App\Http\Controllers\Controller;
use App\Models\Post;

class UnpublishBlankPost extends Controller
{
    public function __invoke()
    {
        $blank_posts = JsonPostContent::with('post')
        ->where('BlankPostCheck' ,'pending')
        ->whereNull('bing_pop_faq_questions')
        ->whereNull('google_faq_questions')
        ->select('id','post_id')
        ->get();
        
        if ($blank_posts->isEmpty()) {
            dd("No Blank Post Found");
        }

        foreach ($blank_posts as  $blank_post) {
           $result =  Post::where('id',$blank_post->post_id)->update([
                'status' => 'unpublish'
            ]);
        }
        return $result;
    }
}
