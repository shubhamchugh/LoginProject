@extends('themes.loginwebmail.layouts.master')








@section('content')


<div class="container">
    <div class="columns" style="height: auto !important;">
        <div class="column is-two-thirds" style="height: auto !important;">
            <div class="box">
                <nav class="breadcrumb has-arrow-separator is-hidden-mobile" aria-label="breadcrumbs">
                    <ul>
                        <li><a href="/">Home</a></li>

                        <li class="is-active"><a href="#" aria-current="page">
                                {{ $post->post_title }}
                            </a></li>
                    </ul>
                </nav>

                <div class="columns">
                    <div class="column ">

                        <h1 class="title is-4">
                            {{ $post->post_title }}
                        </h1>
                        <div class="user">
                            <h5 class="name">
                                Created at: {{ $post->published_at }}
                            </h5>
                            <span class="badge badge-danger">Questioner</span>
                            <span class="badge badge-primary">General</span>
                        </div>

                        <div align="center">
                            <!-- ads -->
                        </div>


                        </br>
                        <p>All
                            {{ $post->post_title }} pages are listed here with their site stats and other details.
                            You can check
                            {{ $post->post_title }} links with our verified badge to select the right page. We also
                            did antivirus check of
                            {{ $post->post_title }} page to keep you safe. <br /><br />

                            We have also listed
                            {{ $post->post_title }} page stats, site age, rank to make it easy for you. Now you can
                            visit the official
                            {{ $post->post_title }} page and use your username and password to login. If you are
                            new user or forget your password for
                            {{ $post->post_title }}, try creating a new account or reset password option.
                        </p>

                    </div>
                </div>
                <div align="center">

                    <!-- ads -->



                </div>

            </div>

            @foreach ($post->content as $content)
            <div align="right"><b>Last Updated:</b>
                {{ $content->updated_at }}
            </div>
            <div class="box box-inner">
                <span><b>Answer By:</b>
                    {{
                    $content->contentFakeAuthor->name }}
                </span>
                <div class="columns">
                    @if ($content->content_image)
                    <div class="column is-one-quarter">
                        <a target="_blank" rel="noreferrer nofollow" class="has-text-centered"><img
                                src="https://s3.us-west-1.wasabisys.com/{{ config('app.WASABI_BUCKET_NAME') }}/{{ $content->content_image }}"
                                alt="
                            {{ $loop->iteration }}. {{ $content->content_title }}" />
                        </a>
                    </div>
                    @endif
                    <div class="column">
                        <h3 class="is-size-4">
                            <a target="_blank" rel="noreferrer nofollow" class="has-text-dark">
                                {{ $loop->iteration }}. {{ $content->content_title }}
                            </a>
                        </h3>
                        <a target="_blank" rel="noreferrer nofollow" class="has-text-dark">
                            <p class="has-text-success">
                                {{ $content->content_url }}
                            </p>
                        </a>
                        <div class="has-text-grey"> <a target="_blank" rel="noreferrer nofollow" class="has-text-dark">
                                {{ $content->content_dec }}
                            </a></div>
                    </div>
                </div>
                @if (config('app.CID_STATUS'))
                <div class="buttons has-addons is-right">
                    <a href="{{ route('post.cid', ['id' => $content->id, 'title' => $content->content_title, 'url' => $content->content_url, 'dec' => $content->content_dec, 'slug' => URL::current()]) }}"
                        rel="nofollow" target="_blank" target="_blank" class="button is-success">> More Info</a>
                </div>
                @else
                <div class="buttons has-addons is-right">
                    <a href=" {{ $content->content_url }}" rel="nofollow" target="_blank" target="_blank"
                        class="button is-success">> More Info</a>
                </div>
                @endif




            </div>
            @endforeach

            <div class=box>
                <div class="row my-3">
                    <div class="header">
                        <h2 class="title is-5"><strong>FAQ</strong></h2>
                    </div>
                    <div class="col-md-6">
                        {{ faq(3,$post->post_title) }}
                    </div>

                    <div class="col-md-6">
                        {{ faq(3,$post->post_title) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        @include('themes.loginwebmail.panels.sidebar')

    </div>
</div>
</div>

@endsection





@section('head')
<title>{{ $PrePostPageTitle->value ?? ""}} {{ $post->post_title ?? "Default Message"}}
    {{ $AfterPostPageTitle->value ?? ""}}</title>
@endsection
