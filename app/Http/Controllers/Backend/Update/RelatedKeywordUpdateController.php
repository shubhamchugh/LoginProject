<?php

namespace App\Http\Controllers\Backend\Update;

use App\Http\Controllers\Backend\Update\AutoUpdatePostController;
use App\Http\Controllers\Controller;
use App\Models\Post;

class RelatedKeywordUpdateController extends Controller
{
    public function relatedKeywords($keyword)
    {
        $keyword = str_replace(' ', '-', $keyword);

        $post = Post::where('slug', $keyword)->first();

        if (!empty($post->slug)) {
            return redirect()->route('post.show', ['post' => $post->slug]);
        } else {
            try {
                AutoUpdatePostController::addPost($keyword);
                return redirect()->route('post.show', ['post' => $keyword]);
            } catch (\Throwable $th) {
                return redirect()->route('post.show', ['post' => $keyword]);
            }
        }
    }
}
