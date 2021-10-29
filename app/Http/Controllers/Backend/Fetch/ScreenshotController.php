<?php

namespace App\Http\Controllers\Backend\Fetch;

use App\Models\PostContent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ScreenshotController extends Controller
{
    public function saveImage(Request $request)
    {
        $start = (!empty($request->start)) ? $request->start : 0;
        $end   = (!empty($request->end)) ? $request->end : 99999999999999;

        $postContent = PostContent::where('is_image', 0)->whereBetween('id', [$start, $end])->orderBy('id', 'ASC')->first();

        if (!empty($postContent)) {

            $url = trim($postContent->content_url);
            // an image will be saved
            try {
                $base64Data = Browsershot::url($url)->base64Screenshot();
            } catch (\Throwable $th) {
                PostContent::where('id', $postContent->id)->update([
                    'is_image'      => 1,
                    'content_image' => 'noimage.png',
                ]);
                die("Unable to Take Screenshot");
            }

            $imageName = (!empty($postContent->content_title)) ? Str::slug($postContent->content_title) . ".png" : "product-" . time() . ".png";

            //decode base64 string
            $image = base64_decode($base64Data);
            Storage::disk('wasabi')->put($imageName, $image);

            PostContent::where('id', $postContent->id)->update([
                'is_image'      => 1,
                'content_image' => $imageName,
            ]);
            die("image inserted");

        } else {
            die("No Image Found For Scraping");
        }
    }
}
