@extends('themes.DevBlog.layouts.master')

@section('content')
<article class="blog-post px-3 py-5 p-md-5">
    <div class="container single-col-max-width">
        <header class="blog-post-header">
            <h1 class="title text-capitalize  mb-2">Post List</h1>
            <div class="meta mb-3">
            </div>
        </header>

        <div class="blog-post-body">
            <script async src="{{ config('constant.GOOGLE_SEARCH') }}"></script>
            <div class="gcse-searchresults-only"></div>
        </div>
    </div>
    <!--//container-->
</article>
@endsection