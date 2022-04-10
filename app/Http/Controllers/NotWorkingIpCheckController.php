<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\IpRecord;
use Illuminate\Support\Facades\Http;

class NotWorkingIpCheckController extends Controller
{
    public function check_ip()
    {

        $ip_SCRAPING = IpRecord::where('status', 'SCRAPING')->orderBy('updated_at', 'asc')->get();

        if (!empty($ip_SCRAPING)) {
            foreach ($ip_SCRAPING as $ip_data) {
                $nowTime = Carbon::now();
                $minutes = $nowTime->diffInMinutes($ip_data->updated_at);

                if (10 < $minutes) {
                    $ip_data->update([
                        'status' => 'NOT_WORKING',
                    ]);
                    echo "$ip_data->ip_address Status Change SCRAPING to NOT_WORKING<br>";
                }

            }
        } else {
            echo "No Ip Found Under SCRAPING TAG<br>";
        }

        $ip = IpRecord::where('status', 'NOT_WORKING')->orWhere('status', 'DISCARD')->orderBy('updated_at', 'asc')->first();
        if (empty($ip->ip_address)) {
            die("All ip are working fine");
        }

        $ip->update([
            'status' => 'CHECKING',
        ]);

        $count = Post::count();

        $post = Post::wherein('id', (getRandomNumberArray(1, $count, 1)))->first('post_title');

        $keyword = $post->post_title;

        $api_url_bing = 'http://' . $ip->ip_address . ':3000/bing?url=https://www.bing.com/search?q=' . str_replace(' ', '+', $keyword);
        echo "Bing Api Url: $api_url_bing<br>";

        try {
            $api_data = Http::timeout(60)->get($api_url_bing)->body();

            echo "<br>Bing APi Data :<br>";
            print_r($api_data);

            $bing_data = json_decode($api_data, true);

            $result_title['result_title'][]             = (!empty($bing_data['resultTitle'])) ? $bing_data['resultTitle'] : null;
            $result_description['result_description'][] = (!empty($bing_data['resultDescription'])) ? $bing_data['resultDescription'] : null;
            $result_url['result_url'][]                 = (!empty($bing_data['resultUrl'])) ? $bing_data['resultUrl'] : null;

            if (!empty($result_title) && !empty($result_description) && !empty($result_url['result_url'][0])) {
                $ip->update([
                    'status' => 'OK',
                    'ERROR'  => null,

                ]);
                echo "<br>No Need To Check Ip Status is OK";
            } else {
                $ip->update([
                    'status' => 'DISCARD',
                ]);
                echo "<br>Please Check ip Carefully System 'DISCARD' the Ip something bad with this: .$api_url_bing";
            }
        } catch (\Throwable $th) {
            $ip->update([
                'status' => 'NOT_RESPONDING',
            ]);
            echo "<br>Ip Not responding maybe timeout error<br>";
            throw $th;
        }

    }
}
