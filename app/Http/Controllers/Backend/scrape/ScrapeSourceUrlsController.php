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
        $count = Count::where('is_scrape', 0)->first();
        $count->update([
            'is_scrape' => 1,
        ]);
        $url      = 'https://stackoverflow.com/questions?tab=newest&page=' . $count->count;
        $response = Http::get($url);
        $html     = $response->body();

        $dom_document_news = new \DOMDocument();
        libxml_use_internal_errors(true); //disable libxml errors

        $dom_document_news->loadHTML($html);
        libxml_clear_errors(); //remove errors for yucky html

        $dom_document_news->preserveWhiteSpace = false;
        $dom_document_news->saveHTML();

        $document_xpath_news = new \DOMXPath($dom_document_news);

        //Get Url
        $Urls = $document_xpath_news->query('//a[@class="question-hyperlink"]/@href');
        $i    = 1;
        foreach ($Urls as $url) {

            SourceUrl::insertOrIgnore([
                'is_scraped' => 0,
                'value'      => $url->nodeValue,
            ]);
            $i++;
        }
    }
}
