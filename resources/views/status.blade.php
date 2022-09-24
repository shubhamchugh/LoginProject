<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>status</title>
</head>

<body>

    <span>
        <h2>Total Publish Unpublish Post Count</h2>
    </span>
    <hr>
    <strong>Publish Post:</strong>{{ $publish_post }}    <br>
    <strong>Unpublish Post:</strong>{{ $unpublish_post  }}<br>
    <span>
        <h2>Total Blank Post <small>(bing_pop_faq_questions && google_faq_questions == NULL)</small></h2>
    </span>
    <hr>
    <strong>Blank Post: </strong>{{ $blank_post }}
    <h2>Scraping Status</h2>
    <hr>
    @foreach ($is_scraped_status as $is_scraped_status_key => $is_scraped_status_value)
    <strong>{{ $is_scraped_status_key }}</strong> : {{ $is_scraped_status_value }} <br>
    @endforeach

    <h2>Re-scrape Post Status</h2>
    <hr>
    <strong>is_bing_results == 0 && bing_search_result_url == NULL :</strong> {{ $is_bing_results }}<br>
    <strong>is_thumbnail_images == 0 && post_thumbnail == NULL: </strong> {{ $is_thumbnail_images }}<br>
    <strong>is_bing_images == 0 && bing_images == NULL: </strong> {{ $is_bing_images }}<br>
    <strong>is_bing_news == 0 && bing_news_title == NULL: </strong> {{ $is_bing_news }}<br>
    <strong>is_bing_video == 0 && bing_videos == NULL: </strong> {{ $is_bing_video }}<br>
    <strong>is_google_results == 0 && google_search_result_url == NULL: </strong>{{ $is_google_results }}

    <h2>Wordpress Transfer Status</h2>
    <hr>
    @foreach ($wordpress_transfer_status as $wordpress_transfer_status_key => $wordpress_transfer_status_value)
    <strong>{{ $wordpress_transfer_status_key }}</strong> : {{ $wordpress_transfer_status_value }} <br>
    @endforeach


    <h2>Flarum Transfer Status</h2>
    <hr>
    @foreach ($Flarum_transfer_status as $Flarum_transfer_status_key => $Flarum_transfer_status_value)
    <strong>{{ $Flarum_transfer_status_key }}</strong> : {{ $Flarum_transfer_status_value }} <br>
    @endforeach




    <h2>Google Indexing Status</h2>
    <hr>

    @foreach ($google_index_status as $google_index_status_key => $google_index_status_value)
    <strong>{{ $google_index_status_key }}</strong> : {{ $google_index_status_value }} <br>
    @endforeach


    <h2>Bing Indexing Status</h2>
    <hr>
    @foreach ($bing_index_status as $bing_index_status_key => $bing_index_status_value)
    <strong>{{ $bing_index_status_key }}</strong> : {{ $bing_index_status_value }} <br>
    @endforeach




</body>

</html>