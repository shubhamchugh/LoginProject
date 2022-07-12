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