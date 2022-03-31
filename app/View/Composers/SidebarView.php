<?php

namespace App\View\Composers;

use App\Models\Post;
use Illuminate\View\View;

class SidebarView
{
    public function compose(View $view)
    {
        $this->composeSidebar($view);
    }

    public function composeSidebar(View $View)
    {
        // $last_id = Post::orderBy('id', 'DESC')->published()->first();
        // $sidebar = Post::published()->wherein('id', (getRandomNumberArray(1, $last_id->id, config('constant.SIDEBAR_POST_COUNT'))))->get();
        $sidebar = Post::published()->inRandomOrder()->limit(10)->get(['id', 'post_title', 'slug']);
        $View->with([
            'sidebar' => $sidebar,
        ]);
    }
}
