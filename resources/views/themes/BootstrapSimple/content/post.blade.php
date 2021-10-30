@extends('themes.BootstrapSimple.layouts.master')

@section('content')
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
    <div class="row">
        <!-- Left Sidebar -->
        <div class="col-xl-8 col-lg-8 col-md-8 col-md-8 col-12 mb-2">

            @foreach ($post->content as $content)
            <div class="post-box mt-2">
                <h3 class="text-primary cursorp">{{ $loop->iteration }}. {{ $content->content_title }}</h3>
                <p><strong>Url: </strong>{{ $content->content_url }}</p>
                <p>
                    @if ($content->content_image)
                    <img src="https://s3.us-west-1.wasabisys.com/{{ config('app.WASABI_BUCKET_NAME') }}/{{ $content->content_image }}"
                        alt="{{ $content->content_title }}" title="{{ $content->content_title }}"
                        class="pul-left rounded">
                    @endif
                    <span class="badge bg-success">{{rand(1,36)}} hours ago</span>
                    {{ $content->content_dec }}

                <div class="d-lg-flex align-items-center justify-content-between">
                    <div>
                        <strong>Added by</strong>: <a href="#" class="text-dec-non" title="Phone Number">{{
                            $content->contentFakeAuthor->name }}</a>
                    </div>
                    <div class="text-end">
                        <span class="float-right">
                            <a href="{{route('post.cid', ['title' => $content->content_title, 'url' => $content->content_url, 'dec' => $content->content_dec, 'slug' => URL::current()]) }}"
                                rel="nofollow" target="_blank" class="btn btn-primary btn-sm">Show
                                details <i class="fa fa-caret-right" aria-hidden="true"></i></a></span>
                    </div>
                </div>
            </div>
            @endforeach


            <div class="row my-3">
                <h3>FAQ</h3>
                <div class="col-md-6">
                    {{ faq(3,$content->content_title) }}
                </div>

                <div class="col-md-6">
                    {{ faq(3,$content->content_title) }}
                </div>
            </div>

        </div>



        <!-- Right Sidebar -->
        @include('themes.BootstrapSimple.panels.sidebar')
    </div>

</div>
@endsection





@section('head')
<title>{{ $PrePostPageTitle->value ?? ""}} {{ $post->post_title ?? "Default Message"}}
    {{ $AfterPostPageTitle->value ?? ""}}</title>
@endsection