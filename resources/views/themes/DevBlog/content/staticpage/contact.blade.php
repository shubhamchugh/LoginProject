@extends('themes.DevBlog.layouts.master')


@section('title', 'Home Page')
@section('content')
<!-- /container -->



<article class="blog-post px-3 py-5 p-md-5">
    <div class="container single-col-max-width">
        <header class="blog-post-header">
            <h1 class="title text-capitalize  mb-2">Contact Us</h1>
            <div class="meta mb-3">
            </div>
        </header>

        <div class="blog-post-body">
            <div class="container margin_60_35">
                <div class="detail_title_1">

                </div>
                <p>For any help/query/problem send us : <a href="{{ $settings->google_forms_contact }}"
                        target="_blank">Link</a>
                </p>
            </div>

        </div>

    </div>
    <!--//container-->
    {{-- Popular Posts start--}}
    @if (config('constant.popular_post') ==
    true)
    <div class="mt-5 mb-3">
        <h2 class="title">Popular Posts:</h2>
        @foreach ($sidebar as $item)
        <li class="list-group-item">
            {{ $loop->iteration }}. <a href="{{ route('post.show',$item->slug) }}" title="{{ $item->post_title }}"> {{
                $item->post_title }}
                <sup><i class="fa fa-external-link" aria-hidden="true"></i></sup></a>
        </li>
        @endforeach
        @endif
    </div>
    {{-- Bing Slider Questions end--}}

</article>
@endsection



@section('head')
<meta name="description" content="Contact Us">
<title>Contact Us</title>
@endsection