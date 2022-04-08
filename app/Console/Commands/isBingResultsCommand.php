<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class isBingResultsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BingResult:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bing Result Update in Existing Database where found Null Value';

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
        $scraping_url = route('is_bing_results.update');
        Http::get($scraping_url)->timeout(150)->connectTimeout(30)->getBody();
        $this->info('Successfully hit to Bing Result Update Url:' . $scraping_url);
    }
}
