<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class NotWorkingIpCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ip:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'your one ip status updated in database';

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
        $sqlUpdate = route('ip-update');
        Http::get($sqlUpdate)->getBody();
        $this->info('Successfully updated ip Database:' . $sqlUpdate);
    }
}
