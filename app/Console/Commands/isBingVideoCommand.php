<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class isBingVideoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BingVideo:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bing Video Update in Existing Database where found Null Value';

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
        $scraping_url = route('is_bing_video.update');
        Http::timeout(20)->get($scraping_url)->getBody();
        $this->info('Successfully hit to Bing Video Update Url:' . $scraping_url);
    }
}
