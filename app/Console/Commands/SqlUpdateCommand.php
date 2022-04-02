<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SqlUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Settings table update using SQL-Update now its up to date with latest settings';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sqlUpdate = route('sql-update');
        Http::get($sqlUpdate)->getBody();
        $this->info('Successfully hit to SQL Update Url:' . $sqlUpdate);
    }
}
