<?php

namespace App\Providers;

use App\View\Composers\SidebarView;
use Illuminate\Support\ServiceProvider;

class VariableSharingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $theme_path = 'themes.' . config('constant.THEME_NAME') . '.content.*';

        view()->composer([
            $theme_path,
        ], SidebarView::class);
    }
}
