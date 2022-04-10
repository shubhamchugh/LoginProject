<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class isGoogleResultCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GoogleResult:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Google Result Update in Existing Database where found Null Value';

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
        $scraping_url = route('is_google_results.update');
        Http::timeout(60)->get($scraping_url)->getBody();
        $this->info('Successfully hit to Google Result Update Url:' . $scraping_url);
    }
}
