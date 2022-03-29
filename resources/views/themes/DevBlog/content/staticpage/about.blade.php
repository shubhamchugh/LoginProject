@extends('themes.DevBlog.layouts.master')


@section('title', 'Home Page')
@section('content')

<!-- /container -->


<article class="blog-post px-3 py-5 p-md-5">
    <div class="container single-col-max-width">
        <header class="blog-post-header">
            <h1 class="title text-capitalize  mb-2">About Us</h1>
            <div class="meta mb-3">
            </div>
        </header>

        <div class="blog-post-body">
            <div class="container margin_60_35">
                <div class="detail_title_1">

                </div>
                <p> We are web devlopment agency who is working from last 10 years in the industry.</p>
                <p>We as a {{$settings->site_name }} trying to help all internet users by providing the list of trusted
                    pages as per
                    their query. </p>
                <p>With our site {{$settings->site_name }}, you can easily find desired website and pages easily. Just
                    search and
                    navigate to the listed pages.
                    If you find any issue you can fill our contact us page we will try to help you.</p>
            </div>

        </div>

    </div>
    <!--//container-->
</article>

@endsection



@section('head')
<meta name="description" content="Abouts Us">
<title>Abouts Us</title>
@endsection