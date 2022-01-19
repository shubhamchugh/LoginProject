@extends('themes.civic.layouts.master')

@section('content')
<?php 
$contentExtension = $post->contentExtension->toArray();
$keywords = unserialize($contentExtension[0]['related_keywords']);
$news = unserialize($contentExtension[0]['news']);
$videos = unserialize($contentExtension[0]['videos']);
$faqs = unserialize($contentExtension[0]['faq']);
$indexedArray = array("new", "trend", "hot", "top","best","tip","great","recommended","suggest","worst","excellent","fabulous");
?>
<div class="container">
    <!-- Page Title -->
    <nav aria-label="breadcrumb" class="theme-breadcrumb">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $post->post_title }}</li>
        </ol>
    </nav>

    <p>
        Official {{ $post->post_title }} Website and related Portal Pages Are listed below. You can reach
        them by clicking on the given websites links.
    </p>

    <h2 class="page-title my-3">{{ $post->post_title }} steps are given below</h2>

    <section class="steps text-center">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-12">
                <div class="step step-A">
                    <div class="step-title">
                        1
                    </div>
                    <div class="step-text">
                        Visit the official {{ $post->post_title }} webpage.
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12">
                <div class="step step-b">
                    <div class="step-title">
                        2
                    </div>
                    <div class="step-text">
                        Enter the username created on the {{ $post->post_title }}.
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12">
                <div class="step step-c">
                    <div class="step-title">
                        3
                    </div>
                    <div class="step-text">
                        Enter the password created for {{ $post->post_title }} and press the login button. </div>
                </div>
            </div>
            <p class="my-3">If you don’t have an account, then create a new account using the link given below</p>
        </div>
    </section>


    @if (!empty($faqs['question']))
    <div class="row my-3">
        <h3>People ask Questions</h3>
        @for ($i = 0; $i < count($faqs['question']); $i++) <div class="col-md-6">
            <strong>{{ $faqs['question'][$i] ?? "" }}</strong><br>
            {{ $faqs['answers'][$i] ?? "" }}<br><br>
    </div>
    @endfor
</div>
@endif


<div class="row">

    <!-- Left Sidebar -->
    <div class="col-xl-8 col-lg-8 col-md-8 col-md-8 col-12 mb-2">


        @if (!empty($keywords))
        <div class="row my-3">
            <h2>Related keywords: </h2>
            @foreach ( $keywords as $keyword)
            <div class="col-md-3">
                <a href="{{ route('scrape.keyword.update',['keyword'=>str_replace(' ', '-', $keyword)]) }}"
                    class="text-dec-non" title="Phone Number">{{
                    $keyword }}</a>
            </div>
            @endforeach
        </div>
        @endif


        @foreach ($post->content as $content)
        <div class="post-box">
            <div class="post-img">
                @if ($content->content_image)
                <img src="https://s3.us-west-1.wasabisys.com/{{ config('app.WASABI_BUCKET_NAME') }}/{{ $content->content_image }}"
                    alt="{{ $content->content_title }}" title="{{ $content->content_title }}">
                @endif
            </div>
            <div class="post-disc">
                <div class="post-title">{{ $loop->iteration }}. {{ $content->content_title }}</div>
                <a href="#" class="post-url"> <strong>Url</strong> : {{ $content->content_url }} </a>
                <div class="post-short-disc">
                    {{ $content->content_dec }}
                </div>
                <div class="post-footer">
                    <div class="post-auth">
                        <strong>Added by</strong>: <span class="post-auther">{{
                            $content->contentFakeAuthor->name }}</span> - <span class="post-time">{{rand(1,36)}}
                            hours Ago</span>
                    </div>
                </div>
            </div>
            @if (config('app.CID_STATUS'))
            <a href="{{route('post.cid', ['id' => $content->id, 'title' => $content->content_title, 'url' => $content->content_url, 'dec' => $content->content_dec, 'slug' => URL::current()]) }}"
                rel="nofollow" target="_blank" class="btn  float-right post-btn">Show
                details <i class="fa fa-angle-right" aria-hidden="true"></i></a>
            @else
            <a href="{{ $content->content_url }}" rel="nofollow" target="_blank" class="btn float-right post-btn">Show
                details <i class="fa fa-angle-right" aria-hidden="true"></i></a>
            @endif
        </div>
        @endforeach
        <div class="faq">
            <div class="list-group-title">faq</div>
            <div class="accordion p-3" id="myAccordion">
                {{ faqCivic(3, $post->post_title) }}
            </div>
        </div>

        @if (!empty($keywords))
        <div class="row my-3">
            <h2>Related keywords: </h2>
            @foreach ( $keywords as $keyword)
            <div class="col-md-3">
                <a href="{{ route('scrape.keyword.update',['keyword'=>str_replace(' ', '-', $keyword)]) }}"
                    class="text-dec-non" title="Phone Number">{{
                    $keyword }}</a>
            </div>
            @endforeach
        </div>
        @endif



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


</div>

@include('themes.civic.panels.sidebar')
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