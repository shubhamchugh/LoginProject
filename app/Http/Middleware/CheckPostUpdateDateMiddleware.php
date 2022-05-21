<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\JsonPostContent;
use App\Http\Controllers\Backend\Update\AutoUpdatePostController;

class CheckPostUpdateDateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $update_date = $request->route('post')->updated_at;
        $nowTime     = Carbon::now();
        $days        = $nowTime->diffInDays($update_date);

        if (count($request->route('post')->content) > 0) {
            $post_content = $request->route('post')->content[mt_rand(0, (count($request->route('post')->content) - 1))];
            if (empty($post_content->bing_search_result_url) && config('constant.Bing_search_result_check')) {
                JsonPostContent::where('id', $post_content->id)->delete();
                return redirect()->route('post.show', $request->route('post')->slug);
            }
        }

        if ($days < config('constant.POST_UPDATE_DURATION')) {
            return $next($request);
        } else {
            try {
                AutoUpdatePostController::update_and_create($request->route('post')->id, $request->route('post')->post_title);

                Post::Where('id', $request->route('post')->id)->update([
                    'updated_at' => Carbon::now(),
                ]);
                return redirect()->route('post.show', $request->route('post')->slug);
            } catch (\Throwable $th) {

                Post::Where('id', $request->route('post')->id)->update([
                    'updated_at' => Carbon::now(),
                ]);

                return $next($request);

            }
        }
        return $next($request);
    }
}
