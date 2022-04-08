<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SeaechEngineIndexingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'search engine Indexing request sent to Google and Bing(if google and bing api Updated)';

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
        $google_indexing = route('google-index');
        Http::get($google_indexing)->timeout(150)->connectTimeout(30)->getBody();
        $bing_indexing = route('bing-index');
        Http::get($google_indexing)->timeout(150)->connectTimeout(30)->getBody();
        $this->info('Successfully Sent request to search Engine Via' . $google_indexing . ' and ' . $bing_indexing);
    }
}
