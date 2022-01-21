@extends('themes.BootstrapSimple.layouts.master')

@section('content')
<?php 
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

    <strong>Tags:</strong>
    @foreach($post->tags as $tag)
    <a href="{{ route('tag.show',['tag' => $tag->slug]) }}"><label class="badge bg-primary">{{ $tag->name }}</label></a>
    @endforeach


    <div class="row">
        <!-- Left Sidebar -->
        <div class="col-xl-8 col-lg-8 col-md-8 col-md-8 col-12 mb-2">
            @if (!empty($post->content))
            @foreach ($post->content as $content)

            <div class="post-box mt-2">
                <h3 class="text-primary cursorp">Answer: {{ $loop->iteration }}</h3>
                <p>
                    <span class="badge bg-success">{{rand(1,36)}} hours ago</span>
                    {!! $content->content_dec !!}

                <div class="d-lg-flex align-items-center justify-content-between">
                    <div>
                        <strong>Added by</strong>: <a href="#" class="text-dec-non" title="Phone Number">{{
                            $content->contentFakeAuthor->name }}</a>
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
        </div>

        <!-- Right Sidebar -->
        @include('themes.BootstrapSimple.panels.sidebar')
    </div>



</div>
@endsection





@section('head')
<title>{{ucwords($post->post_title ?? "Default Message")}}</title>
@endsection