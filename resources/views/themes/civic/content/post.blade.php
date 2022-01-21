@extends('themes.civic.layouts.master')

@section('content')
<?php 
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


    <div class="row">

        <!-- Left Sidebar -->
        <div class="col-xl-8 col-lg-8 col-md-8 col-md-8 col-12 mb-2">



            @foreach ($post->content as $content)
            <div class="post-box">
                <div class="post-disc">
                    <div class="post-title">Answer: {{ $loop->iteration }}</div>
                    <div class="post-short-disc">
                        {!! $content->content_dec !!}
                    </div>
                    <div class="post-footer">
                        <div class="post-auth">
                            <strong>Added by</strong>: <span class="post-auther">{{
                                $content->contentFakeAuthor->name }}</span> - <span class="post-time">{{rand(1,36)}}
                                hours Ago</span>
                        </div>
                    </div>
                </div>
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


        </div>

        @include('themes.civic.panels.sidebar')
    </div>

</div>
@endsection





@section('head')
<title>{{ucwords($post->post_title ?? "Default Message")}}</title>
@endsection