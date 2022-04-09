<?php

namespace App\Http\Controllers\Backend\Update;

use App\Models\Post;
use App\Models\FakeUser;
use App\Models\PostContent;
use App\Http\Controllers\Controller;

class CountCreatePostContentController extends Controller
{
    public function __invoke()
    {
        $totalFakeUser = FakeUser::count();

        if (empty($totalFakeUser)) {
            dd("Please Get Some Fake Users before Scrape Post Please  Help: 'example.com/insert?userCount=Value'");
        }
        $post = Post::where('is_content', 0)->first();
        if (empty($post)) {
            dd("All is_content Have Values Please CHeck DataBase Carefully and Stop hitting");
        }
        $post_content_count = PostContent::where('post_id', $post->id)->count();
        if (0 == $post_content_count) {

            PostContent::create([
                'post_id'      => $post->id,
                'fake_user_id' => mt_rand(1, $totalFakeUser),
            ]);

            $post_content_count = PostContent::where('post_id', $post->id)->count();
            $post->update([
                'is_content' => $post_content_count,
            ]);

        } else {
            $post->update([
                'is_content' => $post_content_count,
            ]);
        }
    }
}
