<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ScrapeStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scraping Start, Will hit to the Scraping Url';

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
        $scraping_url = route('scrape.faq');
        Http::get($scraping_url)->getBody();
        $this->info('Successfully hit to Scraping Url:' . $scraping_url);
    }
}
