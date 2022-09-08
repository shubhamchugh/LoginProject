<?php

namespace App\Http\Controllers\Backend\Update;

use Illuminate\Http\Request;
use App\Models\JsonPostContent;
use App\Http\Controllers\Controller;

class BlankPostUpdateController extends Controller
{
    public function __invoke()
    {
            
        $blank_post = JsonPostContent::with('post')
                                    ->where('BlankPostCheck' ,'pending')
                                    ->whereNull('bing_pop_faq_questions')
                                    ->whereNull('google_faq_questions')
                                    ->select('id','post_id')
                                    ->first();
            if(empty( $blank_post))
            {
                dd("Blank Post Not Found");
            }
        $blank_post->update([
                'BlankPostCheck' => 'scraping'
        ]);              

        try {
            $update = AutoUpdatePostController::update_existing($blank_post->id,$blank_post->post->post_title);
        
            $blank_post->update([
                'BlankPostCheck' => 'done'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            $blank_post->update([
                'BlankPostCheck' => 'fail'
            ]);
        }
            return $update;                    
    }
}
