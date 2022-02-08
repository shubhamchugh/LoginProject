@extends('themes.BootstrapSimple.layouts.master')

@section('content')
<?php 
$postContent = $post->content->toArray();
$author = $postContent[0]['fake_user_id'];
$bing_related_keywords = (!empty($postContent[0]['bing_related_keywords'])) ? unserialize($postContent[0]['bing_related_keywords']) : array();
$google_related_keywords = (!empty($postContent[0]['bing_related_keywords'])) ?  unserialize($postContent[0]['google_related_keywords']) : array();
$news = (!empty($postContent[0]['news'])) ? unserialize($postContent[0]['news']) : array();
$videos = (!empty($postContent[0]['videos'])) ? unserialize($postContent[0]['videos']) : array();
$bing_search_result = (!empty($postContent[0]['bing_search_result'])) ? unserialize($postContent[0]['bing_search_result']) : array();
$bing_paa = (!empty($postContent[0]['bing_paa'])) ? unserialize($postContent[0]['bing_paa']) : array();
$bing_rich_snippet = (!empty($postContent[0]['bing_rich_snippet'])) ? unserialize($postContent[0]['bing_rich_snippet']) : array();
$bing_slider_faq = (!empty($postContent[0]['bing_slider_faq'])) ? unserialize($postContent[0]['bing_slider_faq']) : array();
$bing_pop_faq = (!empty($postContent[0]['bing_pop_faq'])) ? unserialize($postContent[0]['bing_pop_faq']) : array();
$bing_tab_faq = (!empty($postContent[0]['bing_tab_faq'])) ? unserialize($postContent[0]['bing_tab_faq']) : array();
$google_faq = (!empty($postContent[0]['google_faq'])) ?  unserialize($postContent[0]['google_faq']) : array();
$google_rich_snippet = (!empty($postContent[0]['google_rich_snippet'])) ? unserialize($postContent[0]['google_rich_snippet']) : array();
$google_search_result = (!empty($postContent[0]['google_search_result'])) ?  unserialize($postContent[0]['google_search_result']) : array();
$indexedArray = array("new", "trend", "hot", "top","best","tip","great","recommended","suggest","worst","excellent","fabulous");
?>

<div class="container">

    <!--============ Page Title =========================================================================-->
    <div class="page-title">
        <div class="container">
            <h1 class="page-title text-capitalize my-3">{{ $post->post_title }}</h1>
        </div>
        <!--end container-->
    </div>
    <!--end background-->

    <nav aria-label="breadcrumb" class="theme-breadcrumb mb-2">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $post->post_title }}</li>
        </ol>
    </nav>


    <div class="main_question">
        <div class="body exInfo" style="margin-top:10px;">
            <div class="login-helper" style="font-size:18px;">
                <h2>Bing rich snippets</h2>
                {!! $bing_rich_snippet['bing_rich_snippet_text'][0][0] ?? ""!!}
                <a href="{{ $bing_rich_snippet['bing_rich_snippet_link'][0][0] ?? "" }}" target="_blank">Full Answer</a>
                <h2>Google rich snippets</h2>
                {!! $google_rich_snippet[0] ?? "" !!}
            </div>
        </div>
    </div>
    <br>


    @if (!empty($google_faq['questions']))

    <div class="row my-3">
        <h3>Google People ask Questions</h3>
        @for ($i = 0; $i < count($google_faq['questions'][0]); $i++) <div class="col-md-6">
            <strong>{!! $google_faq['questions'][0][$i] ?? "" !!}</strong><br>
            {!! $google_faq['answers'][0][$i] ?? "" !!}<br><br>
    </div>
    @endfor
</div>
@endif


@if (!empty($bing_paa['paa_questions']))

<div class="row my-3">
    <h3>Bing People ask Questions</h3>
    @for ($i = 0; $i < count($bing_paa['paa_questions'][0]); $i++) <div class="col-md-6">
        <strong>{!! $bing_paa['paa_questions'][0][$i] ?? "" !!}</strong><br>
        {!! $bing_paa['paa_Answers'][0][$i] ?? "" !!}<br><br>
</div>
@endfor
</div>
@endif

@if (!empty($bing_pop_faq['pop_questions']))
<div class="row my-3">
    <h3>Bing pop Faq</h3>
    @for ($i = 0; $i < count($bing_pop_faq['pop_questions'][0]); $i++) <div class="col-md-6">
        <strong>{!! $bing_pop_faq['pop_questions'][0][$i] ?? "" !!}</strong><br>
        {!! $bing_pop_faq['pop_answers'][0][$i] ?? "" !!}<br><br>
</div>
@endfor
</div>
@endif


@if (!empty($bing_tab_faq['bing_tab_faq']))
<div class="row my-3">
    <h3>Bing TabNav Faq</h3>
    @for ($i = 0; $i < count($bing_tab_faq['tab_questions'][0]); $i++) <div class="col-md-6">
        <strong>{!! $bing_tab_faq['tab_questions'][0][$i] ?? "" !!}</strong><br>
        {!! $bing_tab_faq['tab_answers'][0][$i] ?? "" !!}<br><br>
</div>
@endfor
</div>
@endif



@if (!empty($bing_slider_faq['bing_tab_faq']))
<div class="row my-3">
    <h3>Bing slider Faq</h3>
    @for ($i = 0; $i < count($bing_slider_faq['slider_questions'][0]); $i++) <div class="col-md-6">
        <strong>{!! $bing_slider_faq['slider_questions'][0][$i] ?? "" !!}</strong><br>
        {!! $bing_slider_faq['slider_answers'][0][$i] ?? "" !!}<br><br>
</div>
@endfor
</div>
@endif

@if (!empty($bing_related_keywords))
<div class="row my-3">
    <h2>Related keywords: </h2>
    @foreach ( $bing_related_keywords as $bing_keyword)
    <div class="col-md-3">
        <a href="{{ route('scrape.keyword.update',['keyword'=>str_replace(' ', '-', $bing_keyword)]) }}"
            class="text-dec-non" title="{{ $bing_keyword }}">{{
            $bing_keyword }}</a>
    </div>
    @endforeach
</div>
@endif

{{-- {{ dd($bing_search_result) }} --}}
<h2>Bing Search Results</h2>
<div class="row">
    <!-- Left Sidebar -->
    <div class="col-xl-8 col-lg-8 col-md-8 col-md-8 col-12 mb-2">
        @if (!empty($bing_search_result))

        @for($i = 0; $i < count($bing_search_result['result_url'][0]); $i++) <div class="post-box mt-2">
            <h3 class="text-primary cursorp"> {{ $i+1 }} . {{$bing_search_result['result_title'][0][$i] ?? ""}}</h3>
            <p><strong>Url: </strong>{{ $bing_search_result['result_url'][0][$i] ?? "" }}</p>
            <p>
                <span class="badge bg-success">{{rand(1,36)}} hours ago</span>
                {{ $bing_search_result['result_description'][0][$i] ?? "" }}

            <div class="d-lg-flex align-items-center justify-content-between">
                <div>
                    <strong>Added by</strong>: <a href="#" class="text-dec-non" title="Phone Number"></a>
                </div>
                <div class="text-end">

                    {{-- @if (config('app.CID_STATUS'))
                    <span class="float-right">
                        <a href="{{route('post.cid', ['id' => $content->id, 'title' => $content->content_title, 'url' => $content->content_url, 'dec' => $content->content_dec, 'slug' => URL::current()]) }}"
                            rel="nofollow" target="_blank" class="btn btn-primary btn-sm">Show
                            details <i class="fa fa-caret-right" aria-hidden="true"></i></a></span>
                    @else
                    <span class="float-right">
                        <a href="{{ $content->content_url }}" rel="nofollow" target="_blank"
                            class="btn btn-primary btn-sm">Show
                            details <i class="fa fa-caret-right" aria-hidden="true"></i></a></span>
                    @endif --}}

                </div>
            </div>
    </div>
    @endfor
    @endif
</div>

<h2>Google Search Results</h2>
<div class="col-xl-8 col-lg-8 col-md-8 col-md-8 col-12 mb-2">
    @if (!empty($google_search_result))

    @for($i = 0; $i < count($google_search_result['url'][0]); $i++) <div class="post-box mt-2">
        <h3 class="text-primary cursorp"> {{ $i+1 }} . {{$google_search_result['title'][0][$i] }}</h3>
        <p><strong>Url: </strong>{{ $google_search_result['url'][0][$i] }}</p>
        <p>
            <span class="badge bg-success">{{rand(1,36)}} hours ago</span>
            {{ $google_search_result['description'][0][$i] }}

        <div class="d-lg-flex align-items-center justify-content-between">
            <div>
                <strong>Added by</strong>: <a href="#" class="text-dec-non" title="Phone Number"></a>
            </div>
            <div class="text-end">

                {{-- @if (config('app.CID_STATUS'))
                <span class="float-right">
                    <a href="{{route('post.cid', ['id' => $content->id, 'title' => $content->content_title, 'url' => $content->content_url, 'dec' => $content->content_dec, 'slug' => URL::current()]) }}"
                        rel="nofollow" target="_blank" class="btn btn-primary btn-sm">Show
                        details <i class="fa fa-caret-right" aria-hidden="true"></i></a></span>
                @else
                <span class="float-right">
                    <a href="{{ $content->content_url }}" rel="nofollow" target="_blank"
                        class="btn btn-primary btn-sm">Show
                        details <i class="fa fa-caret-right" aria-hidden="true"></i></a></span>
                @endif --}}

            </div>
        </div>
</div>
@endfor
@endif





{{-- <div class="row my-3">
    <h2>Faq</h2>
    <div class="col-md-6">
        {{ faq(3, $post->post_title) }}
    </div>

    <div class="col-md-6">
        {{ faq(3, $post->post_title) }}
    </div>
</div>
--}}

<h2>Related News: </h2>
@if (!empty($news))
@for ($i = 0; $i < count($news['title']); $i++) <div class="post-box mt-2 bg-light bg-gradient bg-opacity-70">
    <h3 class="text-primary cursorp">{{ $i+1 }}. {{ $news['title'][$i]}}</h3>
    <p>
        <span class="badge bg-success">{{rand(1,36)}} hours ago</span>
        {{ $news['description'][$i] }}

    <div class="d-lg-flex align-items-center justify-content-between">
        <span class="badge rounded-pill bg-warning text-dark">{{ $indexedArray[array_rand($indexedArray)]
            }}</span>
        <div>
            <strong>{{rand(1,360000)}} People Read</strong>
        </div>
    </div>
    </div>
    @endfor
    @endif

    @if (!empty($google_related_keywords))
    <div class="row my-3">
        <h2>Related keywords: </h2>
        @foreach ( $google_related_keywords as $google_keyword)
        <div class="col-md-3">
            <a href="{{ route('scrape.keyword.update',['keyword'=>str_replace(' ', '-', $google_keyword)]) }}"
                class="text-dec-non" title="{{ $google_keyword }}">{{
                $google_keyword }}</a>
        </div>
        @endforeach
    </div>
    @endif


    </div>

    <!-- Right Sidebar -->
    @include('themes.BootstrapSimple.panels.sidebar')
    </div>

    @if (!empty($videos))
    <h2>Related Videos: </h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">

        @foreach ($videos as $video)
        <?php 
         $video =  json_decode($video,true);
         $thumb_url = 'https://img.youtube.com/vi/'. str_replace('https://www.youtube.com/watch?v=','',$video['pgurl']) .'/hqdefault.jpg'
    ?>
        <div class="col">
            <div class="card h-100">
                <a href="{{ $video['pgurl'] }}" target="_blank">
                    <img src="{{ $thumb_url }}" class="card-img-top" alt="Skyscrapers" />
                    <div class="card-body">
                        <h5 class="card-title">{{ $video['vt'] }}</h5>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Video Duration: {{ $video['du'] }}</small>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif



    </div>
    @endsection





    @section('head')
    <title>{{ucwords($post->post_title ?? "Default Message")}}</title>
    @endsection