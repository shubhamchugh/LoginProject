@extends('themes.BootstrapSimple.layouts.master')

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
                <p>If you want to login to {{ $post->post_title }} Planning Portal, then here we have provided the
                    official link. We have summed up all the login pages related to the {{ $post->post_title }} Planning
                    Portal. This huge list is up to date and also we update this on time by time. So, it’s our personal
                    advice that please go through the below-provided links.</p>
                <h2 style="font-size:18px;">Follow these easy steps:</h2>
                <ul style="margin-bottom:0px;">
                    <li><strong>Step 1. </strong>Go to <span style="color:blue;">{{ $post->post_title }} </span>
                        page via
                        official link below.</li>
                    <li><strong>Step 2. </strong>Login using your username and password. Login screen appears
                        upon successful login.</li>
                    <li><strong>Step 3. </strong>If you still can't access {{ $post->post_title }} then see <a
                            href="#drop" style="color:blue;">Troublshooting options here</a>.</li>
                </ul>
            </div>
        </div>
    </div>
    <br>


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

@if (!empty($keywords))
<div class="row my-3">
    <h2>Related keywords: </h2>
    @foreach ( $keywords as $keyword)
    <div class="col-md-3">
        <a href="{{ route('scrape.keyword.update',['keyword'=>str_replace(' ', '-', $keyword)]) }}" class="text-dec-non"
            title="Phone Number">{{
            $keyword }}</a>
    </div>
    @endforeach
</div>
@endif



<div class="row">
    <!-- Left Sidebar -->
    <div class="col-xl-8 col-lg-8 col-md-8 col-md-8 col-12 mb-2">
        @if (!empty($post->content))
        @foreach ($post->content as $content)

        <div class="post-box mt-2">
            <h3 class="text-primary cursorp">{{ $loop->iteration }}. {{ $content->content_title }}</h3>
            <p><strong>Url: </strong>{{ $content->content_url }}</p>
            <p>
                @if ($content->content_image)
                <img src="https://s3.us-west-1.wasabisys.com/{{ config('app.WASABI_BUCKET_NAME') }}/{{ $content->content_image }}"
                    alt="{{ $content->content_title }}" title="{{ $content->content_title }}" class="pul-left rounded">
                @endif
                <span class="badge bg-success">{{rand(1,36)}} hours ago</span>
                {{ $content->content_dec }}

            <div class="d-lg-flex align-items-center justify-content-between">
                <div>
                    <strong>Added by</strong>: <a href="#" class="text-dec-non" title="Phone Number">{{
                        $content->contentFakeAuthor->name }}</a>
                </div>
                <div class="text-end">



                    @if (config('app.CID_STATUS'))
                    <span class="float-right">
                        <a href="{{route('post.cid', ['id' => $content->id, 'title' => $content->content_title, 'url' => $content->content_url, 'dec' => $content->content_dec, 'slug' => URL::current()]) }}"
                            rel="nofollow" target="_blank" class="btn btn-primary btn-sm">Show
                            details <i class="fa fa-caret-right" aria-hidden="true"></i></a></span>
                    @else
                    <span class="float-right">
                        <a href="{{ $content->content_url }}" rel="nofollow" target="_blank"
                            class="btn btn-primary btn-sm">Show
                            details <i class="fa fa-caret-right" aria-hidden="true"></i></a></span>
                    @endif

                </div>
            </div>
        </div>
        @endforeach
        @endif



        <div class="row my-3">
            <h2>Faq</h2>
            <div class="col-md-6">
                {{ faq(3, $post->post_title) }}
            </div>

            <div class="col-md-6">
                {{ faq(3, $post->post_title) }}
            </div>
        </div>


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