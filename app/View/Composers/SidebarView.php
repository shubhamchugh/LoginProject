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
        $last_id = Post::orderBy('id', 'DESC')->published()->first();
        $sidebar = Post::published()->wherein('id', (getRandomNumberArray(1, $last_id->id, config('app.SIDEBAR_POST_COUNT'))))->get();

        $View->with([
            'sidebar' => $sidebar,
        ]);
    }
}
