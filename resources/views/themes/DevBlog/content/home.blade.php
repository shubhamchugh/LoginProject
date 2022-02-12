@extends('themes.DevBlog.layouts.master')


@section('content')
<!--*********************************************************************************************************-->
<!--************ CONTENT ************************************************************************************-->
<!--*********************************************************************************************************-->
<br>
<section class="cta-section theme-bg-light py-5">
    <div class="container text-center single-col-max-width">
        <h2 class="heading">DevBlog - A Blog Template Made For Developers</h2>
        <div class="intro">Welcome to my blog. Subscribe and get my latest blog post in your inbox.</div>
        <div class="single-form-max-width pt-3 mx-auto">



            <form action="{{ route('search.show') }}" class="signup-form row g-2 g-lg-2 align-items-center">
                <div class="input-group">
                    <?php if (isset($_GET['q'])) { ?>
                    <div class="col-12 col-md-9">
                        <input id="txtGoogleSearch" name="q" class="form-control me-md-1 semail" type="text"
                            placeholder="{{  config('app.SEARCH_INPUT_TEXT') }}" aria-haspopup="true"
                            aria-controls="prova-menu" value="<?php echo $_GET['q']  ?>">
                    </div>
                    <div class="dropdown-menu" id="prova-menu" role="menu">

                    </div>
                </div>

                <?php } else {?>

                <div class="col-12 col-md-9">
                    <input id="txtGoogleSearch" name="q" class="form-control me-md-1 semail" type="text"
                        placeholder="{{  config('app.SEARCH_INPUT_TEXT') }}" aria-haspopup="true"
                        aria-controls="prova-menu">
                </div>

                <?php } ?>

                <div class="col-12 col-md-2">
                    <button class="btn btn-primary" type="submit">
                        Search
                    </button>
                </div>
            </form>








            <!--//Search-form-->
        </div>
        <!--//single-form-max-width-->
    </div>
    <!--//container-->
</section>






<section class="blog-list px-3 py-5 p-md-5">
    <div class="container single-col-max-width">
        @foreach ($posts as $post)
        @if (count($post->content) > 0)
        <div class="item mb-5">
            <div class="row g-3 g-xl-0">
                <div class="col-2 col-xl-3">
                    <img class="img-fluid post-thumb"
                        src="{{$post->content[mt_rand(0,(count($post->content)-1))]->post_thumbnail  ?? config('app.DEFAULT_IMAGE')}}"
                        alt="{{
                            $post->post_title ?? "" }}">
                </div>
                <div class="col">
                    <h3 class="title mb-1"><a class="text-link" href="{{ route('post.show',$post->slug) ?? "" }}">{{
                            $post->post_title ?? "" }}</a></h3>
                    <div class="meta mb-1"><span class="date">Published {{
                            $post->published_at->diffforhumans() ?? ""}}</span><span class="time">Updated {{
                            $post->updated_at->diffforhumans() ?? "" }}</span>

                        {{-- <span class="comment"><a class="text-link" href="#">8
                                comments</a></span>
                    </div> --}}


                    <div class="intro">{{ $post->content[mt_rand(0,(count($post->content)-1))]->post_description ?? ""}}
                    </div>
                    <a class="text-link" href="{{ route('post.show',$post->slug) ??  "" }}">Read more &rarr;</a>
                </div>
                <!--//col-->
            </div>
            <!--//row-->
        </div>
        <!--//item-->
        @endif

        @endforeach

        {{ $posts->links('pagination::DevBlog') }}

    </div>

</section>
@endsection


@section('head')
<title>{{ 'Default Message' }}</title>
@endsection