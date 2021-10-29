<?php

namespace App\Http\Controllers\Backend\Fetch;

use App\Models\Metadata;
use App\Models\PostContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class MetadataController extends Controller
{
    public function saveMetadata(Request $request)
    {
        $start       = (!empty($request->start)) ? $request->start : 0;
        $end         = (!empty($request->end)) ? $request->end : 9999999999999999;
        $postContent = PostContent::where('is_metadata', 0)->whereBetween('id', [$start, $end])->orderBy('id', 'ASC')->first();

        if (!empty($postContent)) {
            $url      = $postContent->content_url;
            $parse    = parse_url(trim($url));
            $parse    = $parse['host'];
            $parse    = env('ScrapeApi') . '/core/api-bulk.php?sitelink=' . $parse;
            $response = Http::get($parse);
            $response = $response->body();

            $response = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response);
            $response = json_decode($response, true);
            if (!empty($response['host_ip'])) {
                if (!empty($response)) {
                    Metadata::create([
                        'content_id'          => $postContent->id,
                        'price'               => $response['price'],
                        'site_title'          => $response['site_title'],
                        'site_description'    => $response['site_description'],
                        'site_keywords'       => $response['site_keywords'],
                        'age'                 => $response['age'],
                        'response_time'       => $response['response_time'],
                        'alexa_global_rank'   => $response['alexa_global_rank'][0],
                        'alexa_pop'           => $response['alexa_pop'][0],
                        'alexa_regional_rank' => $response['alexa_regional_rank'][0],
                        'alexa_back'          => $response['alexa_back'],
                        'host_ip'             => $response['host_ip'],
                        'host_country'        => $response['host_country'],
                        'host_isp'            => $response['host_isp'],
                        'blacklist_result'    => $response['blacklist_result'],
                        'whois_data'          => $response['whois_data'],

                    ]);
                    PostContent::where('id', $postContent->id)->update(
                        [
                            'is_metadata' => 1, // updated record scraped success
                        ]
                    );
                    echo "Data Inserted";
                } else {
                    PostContent::where('id', $postContent->id)->update(
                        [
                            'is_metadata' => 2, //fail due to not valid domain
                        ]
                    );
                    die("Not Valid Domain");
                }
            } else {
                PostContent::where('id', $postContent->id)->update(
                    [
                        'is_metadata' => 3, //Domain TLD Not In DataBase
                    ]
                );
                die("Domain TLD Not In DataBase ");
            }

        } else {
            die("No Record Found");
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
