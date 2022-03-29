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
</article>
@endsection



@section('head')
<meta name="description" content="Contact Us">
<title>Contact Us</title>
@endsection