@extends('themes.DevBlog.layouts.master')

@section('content')

<article class="blog-post px-3 py-5 p-md-5">
    <div class="container single-col-max-width">
        <header class="blog-post-header">
            <h1 class="title text-capitalize  mb-2">{{ $post->post_title ?? "" }}</h1>
            <div class="meta mb-3">
                <span class="time">
                    by {{ $post->fakeAuthor->name ?? "" }}
                </span>
                <span class="date">
                    Published {{ $post->published_at->diffforhumans() ?? "" }}
                </span>
                <span class="time">
                    Updated {{ $post->updated_at->diffforhumans() ?? "" }}
                </span>
                @if (config('app.debug'))
                <span class="comment">
                    <a class="text-link" target="_blank" href="{{ route('post_content.update_existing',[
                        'post_content_id' => $postContent['id'],
                        'keyword' => $post['source_value'],
                        ]) ?? "" }}">Update Post</a>
                </span>
                @endif


            </div>
        </header>

        <div class="blog-post-body">

            {{-- Custom Content Above Content start--}}
            @if (!empty($postContent['post_content_above']))
            <div class="mt-5 mb-3">
                {!! $postContent['post_content_above'] !!}
            </div>
            @endif
            {{-- Custom Content Above Content en--}}

            {{-- Bing Random Image start--}}
            @if(!empty($bing_images['images'][0]))
            <figure class="blog-banner">
                <img class="img-fluid img-responsive center-block d-block mx-auto"
                    src="{{ json_decode($bing_images['images'][mt_rand(0,$totalimages)],true)['murl'] ?? ""}}"
                    alt="image">
                <figcaption class="mt-2 text-center image-caption">Image Credit: <a class="theme-link"
                        href="{{ json_decode($bing_images['images'][mt_rand(0,$totalimages)],true)['purl'] ?? ""}}"
                        rel="noopener noreferrer nofollow" target="_blank">{{
                        json_decode($bing_images['images'][mt_rand(0,$totalimages)],true)['t'] ?? ""
                        }}</a></figcaption>
            </figure>
            @endif
            {{-- Bing Random Image end --}}

            {{-- Google And Bing Rich Snippe --}}

            <p> {!! $bing_rich_snippet['bing_rich_snippet_text'][0][0] ?? ""!!}</p>
            <p> {!! $google_rich_snippet[0] ?? "" !!}</p>

            {{-- Bing Full richSnippet link --}}
            @if (!empty($bing_rich_snippet['bing_rich_snippet_link'][0][0] ))
            <a href="{{ $bing_rich_snippet['bing_rich_snippet_link'][0][0] ?? "" }}" rel="noopener noreferrer nofollow"
                target="_blank">Full
                Answer</a><br><br>
            @endif


            {{-- Bing Random videostart--}}
            @if(!empty($bing_videos[0]))
            <div class="ratio ratio-16x9">
                <iframe width="560" height="315"
                    src=" {{ str_replace('watch?v=','embed/',json_decode($bing_videos[mt_rand(0,$totalvideos)],true)['pgurl']) }}"
                    frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>

            @endif
            {{-- Bing Random video end --}}

            {{-- related Keywords bing start--}}
            @if (!empty($bing_related_keywords))
            <div class="row my-6 mt-5 mb-3">
                <h2>Related Posts: </h2>
                @foreach ( $bing_related_keywords as $bing_keyword)
                <li class="col-md-6">
                    <a rel="noopener noreferrer nofollow"
                        href="{{ route('scrape.keyword.update',['keyword'=>str_replace(' ', '-', $bing_keyword)]) }}"
                        target="_blank" class="text-dec-non" title="{{ $bing_keyword }}">{{
                        $bing_keyword }}</a>
                </li>
                @endforeach
            </div>
            @endif
            {{-- related Keywords bing end --}}

            @if (!empty($postContent['post_content_middle']))
            <div class="mt-5 mb-3">
                {!! $postContent['post_content_middle'] !!}
            </div>
            @endif

            {{-- Bing People Also Aks --}}
            @if (!empty($bing_paa['paa_questions']))
            @for($i = 0; $i < count($bing_paa['paa_questions'][0]); $i++) <h3 class="mt-5 mb-3">
                {!! $bing_paa['paa_questions'][0][$i] ?? "" !!}
                </h3>

                <p>
                    {!! $bing_paa['paa_Answers'][0][$i] ?? "" !!}
                </p>
                @endfor



                {{-- Bing Random Image start--}}
                @if(!empty($bing_images['images'][0]))
                <figure class="blog-banner">
                    <img class="img-fluid img-responsive center-block d-block mx-auto"
                        src="{{ json_decode($bing_images['images'][mt_rand(0,$totalimages)],true)['murl'] }}"
                        alt="image">
                    <figcaption class="mt-2 text-center image-caption">Image Credit: <a class="theme-link"
                            href="{{ json_decode($bing_images['images'][mt_rand(0,$totalimages)],true)['purl'] }}"
                            rel="noopener noreferrer nofollow" target="_blank">{{
                            json_decode($bing_images['images'][mt_rand(0,$totalimages)],true)['t']
                            }}</a></figcaption>
                </figure>
                @endif
                {{-- Bing Random Image end --}}


                @endif
                {{-- Bing People Also Aks end --}}





                {{-- Google Faq Questions start --}}
                @if (!empty($google_faq['questions']))
                @for($i = 0; $i < count($google_faq['questions'][0]); $i++) <h3 class="mt-5 mb-3">
                    {!! $google_faq['questions'][0][$i] ?? "" !!}
                    </h3>

                    <p>
                        {!! $google_faq['answers'][0][$i] ?? "" !!}
                    </p>
                    @endfor




                    {{-- Bing Random videostart--}}
                    @if(!empty($bing_videos[0]))
                    <div class="ratio ratio-16x9">
                        <iframe width="560" height="315"
                            src=" {{ str_replace('watch?v=','embed/',json_decode($bing_videos[mt_rand(0,$totalvideos)],true)['pgurl']) }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    </div>

                    @endif
                    {{-- Bing Random video end --}}
                    @endif
                    {{-- Google Faq Questions end --}}



                    {{-- Bing Slider Questions start--}}
                    @if (!empty($bing_slider_faq['slider_questions'][0]))
                    @for($i = 0; $i < count($bing_slider_faq['slider_questions'][0]); $i++) <h3 class="mt-5 mb-3">
                        {!! $bing_slider_faq['slider_questions'][0][$i] ?? "" !!}
                        </h3>

                        <p>
                            {!! $bing_slider_faq['slider_answers'][0][$i] ?? "" !!}
                        </p>
                        @endfor
                        @endif
                        {{-- Bing Slider Questions start end--}}

                        {{-- related Keywords Google start--}}
                        @if (!empty($google_related_keywords))
                        <div class="row my-6 mt-5 mb-3">
                            <h2 class="title">Posts you might like: </h2>
                            @foreach ( $google_related_keywords as $google_keyword)
                            <li class="col-md-12">
                                <a rel="noopener noreferrer nofollow"
                                    href="{{ route('scrape.keyword.update',['keyword'=>str_replace(' ', '-', $google_keyword)]) }}"
                                    target="_blank" class="text-dec-non" title="{{ $google_keyword }}">{{
                                    $google_keyword }}</a>
                            </li>
                            @endforeach
                        </div>
                        @endif
                        {{-- related Keywords Google end --}}


                        {{-- Bing pop Questions start--}}
                        @if (!empty($bing_pop_faq['pop_questions'][0]))
                        @for($i = 0; $i < count($bing_pop_faq['pop_questions'][0]); $i++) {{-- Bing Random Image
                            start--}} @if(!empty($bing_images['images'][0]) && ($i % 3==0)) <figure class="blog-banner">
                            <img class="img-fluid img-responsive center-block d-block mx-auto"
                                src="{{ json_decode($bing_images['images'][mt_rand(0,$totalimages)],true)['murl'] }}"
                                alt="image">
                            <figcaption class="mt-2 text-center image-caption">Image Credit: <a class="theme-link"
                                    href="{{ json_decode($bing_images['images'][mt_rand(0,$totalimages)],true)['purl'] }}"
                                    rel="noopener noreferrer nofollow" target="_blank">{{
                                    json_decode($bing_images['images'][mt_rand(0,$totalimages)],true)['t']
                                    }}</a></figcaption>
                            </figure>
                            @endif
                            {{-- Bing Random Image end --}}

                            <h3 class="mt-5 mb-3">
                                {!! $bing_pop_faq['pop_questions'][0][$i] ?? "" !!}
                            </h3>

                            <p>
                                {!! $bing_pop_faq['pop_answers'][0][$i] ?? "" !!}
                            </p>
                            @endfor
                            @endif
                            {{-- Bing pop Questions start end--}}




                            {{-- Bing Slider Questions start--}}
                            @if (!empty($bing_tab_faq['tab_questions'][0]))
                            @for($i = 0; $i < count($bing_tab_faq['tab_questions'][0]); $i++) <h3 class="mt-5 mb-3">
                                {!! $bing_tab_faq['tab_questions'][0][$i] ?? "" !!}
                                </h3>

                                <p>
                                    {!! $bing_tab_faq['tab_answers'][0][$i] ?? "" !!}
                                </p>
                                @endfor
                                @endif
                                {{-- Bing Slider Questions start end--}}


                                @if (!empty($postContent['post_content_middle']))
                                <div class="mt-5 mb-3">
                                    {!! $postContent['post_content_middle'] !!}
                                </div>
                                @endif


                                {{-- Popular Posts start--}}
                                @if (true)
                                <div class="mt-5 mb-3">
                                    <h2 class="title">Popular Posts:</h2>
                                    @foreach ($sidebar as $item)
                                    <li class="list-group-item">
                                        {{ $loop->iteration }}. <a href="{{ route('post.show',$item->slug) }}"
                                            title="{{ $item->post_title }}"> {{
                                            $item->post_title }}
                                            <sup><i class="fa fa-external-link" aria-hidden="true"></i></sup></a>
                                    </li>
                                    @endforeach
                                    @endif
                                </div>
                                {{-- Bing Slider Questions end--}}
        </div>
    </div>
    <!--//container-->
</article>
@endsection





@section('head')
<title>{{ ucwords($post->post_title ?? "Default Message") }}</title>
@endsection