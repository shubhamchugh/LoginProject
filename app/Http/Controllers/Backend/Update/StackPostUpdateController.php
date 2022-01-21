<?php

namespace App\Http\Controllers\Backend\Update;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\FakeUser;
use App\Models\PostContent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class StackPostUpdateController extends Controller
{
    public static function update($id)
    {
        $post          = Post::where('id', $id)->first();
        $url_to_scrape = $post->source_value;

        $response = Http::get($url_to_scrape);
        $response = $response->body();

        $dom_document = new \DOMDocument();
        libxml_use_internal_errors(true); //disable libxml errors

        $dom_document->loadHTML($response);
        libxml_clear_errors(); //remove errors for yucky html

        $dom_document->preserveWhiteSpace = false;
        $dom_document->saveHTML();

        $document_xpath = new \DOMXPath($dom_document);

        //News Xpath to get Data
        $questions = $document_xpath->query('//h1/a[@class="question-hyperlink"]');
        $answers   = $document_xpath->query('//div[@class="s-prose js-post-body"]');

        foreach ($questions as $question) {
            $stack_q[] = $question->nodeValue;
        }
        //echo "<pre>";
        //print_r($stack_q);

        foreach ($answers as $answer) {
            $stack_a[] = $answer->c14n();
        }
        //print_r($stack_a);

        $stack_q = (!empty($stack_q)) ? $stack_q[0] : null;

        if (empty($stack_q)) {
            echo "404 Found or Site Block the ip";
            dd();
        }

        $startdate = strtotime("2021-3-01 00:00:00");

        $randomDate = date("Y-m-d H:i:s", mt_rand($startdate, strtotime(Carbon::now())));

        Post::where('id', $id)->update([
            'post_title' => $stack_q,
        ]);

        $stack_a       = (!empty($stack_a)) ? $stack_a : null;
        $totalFakeUser = FakeUser::count();
        if (!empty($stack_a)) {
            PostContent::where('post_id', $id)->delete();
            for ($i = 0; $i < count($stack_a); $i++) {
                PostContent::create([
                    'post_id'      => $id,
                    'fake_user_id' => mt_rand(1, $totalFakeUser),
                    'content_dec'  => $stack_a[$i],
                ]);
            }

        } else {
            echo "Answers Not Found";
            dd();
        }

    }
}
