<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class CacheClearController extends Controller
{
    public function clear()
    {
        echo "<pre>";
        // Artisan::call('cache:clear');
        // print_r(Artisan::output());

        // Artisan::call('config:cache');
        // print_r(Artisan::output());

        // Artisan::call('route:cache');
        // print_r(Artisan::output());

        // Artisan::call('view:clear');
        // print_r(Artisan::output());

        Artisan::call('optimize:clear');
        print_r(Artisan::output());

        shell_exec('cd .. && sudo chmod -R o+rw bootstrap/cache');
        shell_exec('cd .. && sudo chmod -R o+rw storage');
        shell_exec('cd .. && sudo chmod -R 777 storage');
        shell_exec('cd .. && sudo chmod -R 777 bootstrap/cache');
    }
}
