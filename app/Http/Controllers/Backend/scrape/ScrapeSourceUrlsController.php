<?php

namespace App\Http\Controllers\Backend\scrape;

use App\Models\Count;
use App\Models\SourceUrl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ScrapeSourceUrlsController extends Controller
{
    public function scrapeSourceUrl(Request $request)
    {

        $start  = (!empty($request->start)) ? $request->start : 0;
        $end    = (!empty($request->end)) ? $request->end : 999999999999999999;
        $domain = (!empty($request->domain)) ? $request->domain : 'https://stackoverflow.com';
        $count  = Count::where('is_scrape', 0)->whereBetween('id', [$start, $end])->first();

        if (!empty($count)) {
            $count->update([
                'is_scrape' => 1,
            ]);

            $url      = $domain . '/questions?tab=newest&page=' . $count->count;
            $response = Http::get($url);
            $html     = $response->body();

            $dom_document = new \DOMDocument();
            libxml_use_internal_errors(true); //disable libxml errors

            $dom_document->loadHTML($html);
            libxml_clear_errors(); //remove errors for yucky html

            $dom_document->preserveWhiteSpace = false;
            $dom_document->saveHTML();

            $document_xpath = new \DOMXPath($dom_document);

            //Get Url
            $Urls = $document_xpath->query('//a[@class="s-link"]/@href');
            $i    = 1;
            foreach ($Urls as $url) {
                $url_to_save = $domain . $url->nodeValue;
                SourceUrl::insertOrIgnore([
                    'is_scraped' => 'pending',
                    'value'      => $url_to_save,
                ]);
                $i++;
            }
        } else {
            echo "No record found";
        }
    }
}
