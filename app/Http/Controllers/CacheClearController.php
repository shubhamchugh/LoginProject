<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class CacheClearController extends Controller
{
    public function clear()
    {
        echo "<pre>";
        echo shell_exec('cd .. && sudo chmod -R o+rw bootstrap/cache');
        echo shell_exec('cd .. && sudo chmod -R o+rw storage');
        echo shell_exec('cd .. && sudo chmod -R 777 storage');
        echo shell_exec('cd .. && sudo chmod -R 777 bootstrap/cache');
        echo shell_exec('cd .. && sudo chmod -R 777 public');
        echo shell_exec('cd .. && sudo chmod -R o+rw public');

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

        Artisan::call('settings:clear-cache');
        print_r(Artisan::output());

        Artisan::call('settings:clear-discovered');
        print_r(Artisan::output());

        Artisan::call('settings:discover');
        print_r(Artisan::output());

        print_r(shell_exec('cd .. && cd storage/logs && > laravel.log'));
        print_r(shell_exec('sudo chown -R runcloud:runcloud /home/runcloud/webapps/'));
        print_r(shell_exec('sudo chmod 755 -R /home/runcloud/webapps/'));
    }
}
