<?php

namespace App\Http\Controllers;

use App\Models\IpRecord;
use Illuminate\Support\Facades\Http;

class NotWorkingIpCheckController extends Controller
{
    public function check_ip()
    {
        $ip = IpRecord::where('status', 'NOT_WORKING')->inRandomOrder()->first();
        if (empty($ip->ip_address)) {
            die("All ip are working fine");
        }
        $keyword      = 'how to use excel';
        $api_url_bing = 'http://' . $ip->ip_address . ':3000/bing?url=https://www.bing.com/search?q=' . str_replace(' ', '+', $keyword);
        echo "Bing Api Url: $api_url_bing<br>";
        $api_data = Http::get($api_url_bing)->body();

        $bing_data = json_decode($api_data, true);

        echo "Bing APi Data :<br>";
        print_r($bing_data);

        $result_title['result_title'][]             = (!empty($bing_data['resultTitle'])) ? $bing_data['resultTitle'] : null;
        $result_description['result_description'][] = (!empty($bing_data['resultDescription'])) ? $bing_data['resultDescription'] : null;
        $result_url['result_url'][]                 = (!empty($bing_data['resultUrl'])) ? $bing_data['resultUrl'] : null;

        if (!empty($result_title) && !empty($result_description) && !empty($result_url['result_url'][0])) {
            $ip->update([
                'status' => 'OK',
            ]);
            echo "Please Check ip Carefully something bad with this: .$api_url_bing";
        } else {
            $ip->update([
                'status' => 'DISCARD',
            ]);
            echo "Please Check ip Carefully something bad with this: .$api_url_bing";
        }
    }
}
