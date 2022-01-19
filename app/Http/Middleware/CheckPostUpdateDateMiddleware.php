<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Backend\Update\BingAutoUpdateController;

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
        $postDate = $request->route('post')->updated_at;
        $nowTime  = Carbon::now();
        $days     = $nowTime->diffInDays($postDate);

        if ($days < config('app.POST_UPDATE_DURATION')) {
            return $next($request);
        } else {
            try {
                BingAutoUpdateController::update($request->route('post')->id, $request->route('post')->post_title);
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
