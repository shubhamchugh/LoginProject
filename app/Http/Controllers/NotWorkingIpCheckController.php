<?php

namespace App\Http\Controllers;

use App\Models\IpRecord;
use App\Models\Post;
use Illuminate\Support\Facades\Http;

class NotWorkingIpCheckController extends Controller
{
    public function check_ip()
    {
        $ip = IpRecord::where('status', 'NOT_WORKING')->orWhere('status', 'DISCARD')->inRandomOrder()->first();
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

        $api_data = Http::get($api_url_bing)->timeout(150)->connectTimeout(30)->body();

        echo "<br>Bing APi Data :<br>";
        print_r($api_data);

        $bing_data = json_decode($api_data, true);

        $result_title['result_title'][]             = (!empty($bing_data['resultTitle'])) ? $bing_data['resultTitle'] : null;
        $result_description['result_description'][] = (!empty($bing_data['resultDescription'])) ? $bing_data['resultDescription'] : null;
        $result_url['result_url'][]                 = (!empty($bing_data['resultUrl'])) ? $bing_data['resultUrl'] : null;

        if (!empty($result_title) && !empty($result_description) && !empty($result_url['result_url'][0])) {
            $ip->update([
                'status' => 'OK',
            ]);
            echo "<br>No Need To Check Ip Status is OK";
        } else {
            $ip->update([
                'status' => 'DISCARD',
            ]);
            echo "<br>Please Check ip Carefully System 'DISCARD' the Ip something bad with this: .$api_url_bing";
        }
    }
}
