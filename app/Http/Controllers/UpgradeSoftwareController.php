<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        echo shell_exec('cd .. && git status');

        echo shell_exec('cd .. && sudo chmod -R o+rw bootstrap/cache');
        echo shell_exec('cd .. && sudo chmod -R o+rw storage');
        echo shell_exec('cd .. && sudo chmod -R 777 storage');
        echo shell_exec('cd .. && sudo chmod -R 777 bootstrap/cache');
        echo shell_exec('cd .. && sudo chmod -R 777 public');
        echo shell_exec('cd .. && sudo chmod -R o+rw public');

        echo shell_exec('cd .. && git reset --hard && git pull');

        echo shell_exec('cd .. && COMPOSER_MEMORY_LIMIT=-1 composer update');
        echo shell_exec('cd .. && php artisan migrate');

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

        Artisan::call('debugbar:clear');
        print_r(Artisan::output());

        Artisan::call('event:clear');
        print_r(Artisan::output());

        echo shell_exec('cd .. && sudo chmod -R o+rw bootstrap/cache');
        echo shell_exec('cd .. && sudo chmod -R o+rw storage');
        echo shell_exec('cd .. && sudo chmod -R 777 storage');
        echo shell_exec('cd .. && sudo chmod -R 777 bootstrap/cache');
        echo shell_exec('cd .. && sudo chmod -R 777 public');
        echo shell_exec('cd .. && sudo chmod -R o+rw public');

    }
}
