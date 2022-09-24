<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CacheTruncate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All Type of cache logs of the applications are truncated';

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

        $currentDomain = request()->server('SERVER_NAME');
        $currentDomain_clear = $currentDomain.'/clearcache.php';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "$currentDomain_clear"); // set live website where data from
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); // default
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // default
        curl_setopt($curl, CURLOPT_TIMEOUT, 60); //timeout in seconds
        $content = curl_exec($curl);
        
        $this->info('Successfully truncated all logs and cache You can use clear Manually using this URL:' .  $currentDomain_clear);
    }
}
