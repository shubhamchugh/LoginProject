@extends('themes.medium.layouts.master')


@section('content')

<!-- Begin Site Title
================================================== -->
<div class="container">
    <div class="mainheading">
        <h1 class="sitetitle">{{ $settings->home_h1_title }}</h1>
        <p class="lead">
            {{ $settings->home_page_description }}
        </p>
    </div>
    <!-- End Site Title
================================================== -->

    <!-- Begin List Posts
	================================================== -->
    <section class="recent-posts">
        <div class="section-title">
            <h2><span>Featured</span></h2>
        </div>
        <div class="card-columns listrecent">

            @foreach ($posts as $post)

            @if (count($post->content) > 0)
            <!-- begin post -->
            <div class="card">
                <a href="{{ route('post.show',$post->slug) ??  "" }}">
                    <img class="img-fluid"
                        src="{{$post->content[mt_rand(0,(count($post->content)-1))]->post_thumbnail  ?? config('constant.DEFAULT_IMAGE')}}"
                        alt="">
                </a>
                <div class="card-block">
                    <h2 class="card-title"><a href="{{ route('post.show',$post->slug) ??  "" }}">{{
                            $post->post_title ?? "" }}</a></h2>
                    <h4 class="card-text">{{
                        $post->content[mt_rand(0,(count($post->content)-1))]->post_description ??
                        ""}}</h4>
                    <div class="metafooter">
                        <div class="wrapfooter">


                            <span class="author-meta">
                                <span class="post-date">{{
                                    $post->published_at->diffforhumans() ?? ""}}</span><span class="dot"></span><span
                                    class="post-read">{{ rand(3,10) }} min read</span>
                            </span>
                            <span class="post-read-more"><a href="{{ route('post.show',$post->slug) ??  "" }}"
                                    title="Read Story"><svg class="svgIcon-use" width="25" height="25"
                                        viewbox="0 0 25 25">
                                        <path
                                            d="M19 6c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v14.66h.012c.01.103.045.204.12.285a.5.5 0 0 0 .706.03L12.5 16.85l5.662 4.126a.508.508 0 0 0 .708-.03.5.5 0 0 0 .118-.285H19V6zm-6.838 9.97L7 19.636V6c0-.55.45-1 1-1h9c.55 0 1 .45 1 1v13.637l-5.162-3.668a.49.49 0 0 0-.676 0z"
                                            fill-rule="evenodd"></path>
                                    </svg></a></span>



                        </div>
                    </div>
                </div>
            </div>
            <!-- end post -->
            @endif

            @endforeach

        </div>
        {{ $posts->links('pagination::medium') }}
    </section>
    <!-- End Featured
        ================================================== -->
    @endsection