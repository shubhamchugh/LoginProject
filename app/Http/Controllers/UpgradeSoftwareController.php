<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;

class UpgradeSoftwareController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        echo "<pre>";
        echo "<h2>Git Update Output</h2>";
        echo shell_exec("git config --global --add safe.directory '*'");
        echo shell_exec('cd .. && git status');

        echo shell_exec('cd .. && sudo chmod -R o+rw bootstrap/cache');
        echo shell_exec('cd .. && sudo chmod -R o+rw storage');
        echo shell_exec('cd .. && sudo chmod -R 777 storage');
        echo shell_exec('cd .. && sudo chmod -R 777 bootstrap/cache');
        echo shell_exec('cd .. && sudo chmod -R 777 public');
        echo shell_exec('cd .. && sudo chmod -R o+rw public');

        echo shell_exec('cd .. && git remote set-url origin https://github.com/shubhamchugh/LoginProject.git
        && git reset --hard && git clean -d -f && git pull');

        echo shell_exec('cd .. && git update-index --skip-worktree public/themes/DevBlog/assets/images/profile.png');

        echo shell_exec('cd .. && COMPOSER_MEMORY_LIMIT=-1 composer update');

        echo "<h2>Migration Details</h2>";
        echo shell_exec('cd .. && php artisan migrate');

        echo "<h2>Cache Clear Update Output</h2>";
        Artisan::call('cache:clear');
        print_r(Artisan::output());

        Artisan::call('clear-compiled');
        print_r(Artisan::output());

        Artisan::call('config:cache');
        print_r(Artisan::output());

        Artisan::call('route:cache');
        print_r(Artisan::output());

        Artisan::call('view:clear');
        print_r(Artisan::output());

        Artisan::call('optimize:clear');
        print_r(Artisan::output());

        Artisan::call('event:clear');
        print_r(Artisan::output());

        echo "<h2>Admin and MenuBar Data Reset</h2>";
        Artisan::call('db:seed --force');
        print_r(Artisan::output());

        echo shell_exec('cd .. && php artisan debugbar:clear');

        echo shell_exec('cd .. && sudo chmod -R o+rw bootstrap/cache');
        echo shell_exec('cd .. && sudo chmod -R o+rw storage');
        echo shell_exec('cd .. && sudo chmod -R 777 storage');
        echo shell_exec('cd .. && sudo chmod -R 777 bootstrap/cache');
        echo shell_exec('cd .. && sudo chmod -R 777 public');
        echo shell_exec('cd .. && sudo chmod -R o+rw public');

        echo "<h2>Settings Update Output</h2>";
        $url        = url('/sql-update');
        $sql_update = Http::get($url);

        if (!($sql_update->ok())) {
            return "Please check env file Domain name";
        }
        print_r($sql_update->body());
    }
}
