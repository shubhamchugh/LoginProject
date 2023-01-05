@extends('themes.medium.layouts.master')
@section('content')
<!-- Begin Article
================================================== -->
<div class="container">
    <div class="row">

        @include('themes.medium.panels.sidebar')

        <!-- Begin Post -->
        <div class="col-md-8 col-md-offset-2 col-xs-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $post->post_title ?? "" }}</li>
                </ol>
            </nav>

            <div class="mainheading">

                <h1 class="posttitle text-capitalize">{{ $post->post_title ?? "" }}</h1>

            </div>
            <div class="meta mb-3">
                <span class="post-read">
                    by {{ $post->FakeUser->name ?? "" }}
                </span>
                <span class="dot"></span>
                <span class="post-date">
                    Published {{ $post->published_at->diffforhumans() ?? "" }}
                </span>
                <span class="dot"></span>
                <span class="post-date">
                    Updated {{ $updated_at->diffforhumans() ?? "" }}
                </span>
                <span class="dot"></span>
                <span class="post-read">{{ rand(3,10) }} min read</span>

                @if (config('constant.Update_Post_Link'))
                <span class="dot"></span>
                <span class="comment">
                    <a class="link-dark" target="_blank" href="{{ route('post_content.update_existing',[
                        'post_content_id' => $postContent['id'],
                        'keyword' => $post['source_value'],
                        ]) ?? "" }}"> Update Post </a>
                </span>
                @endif
            </div>

            <div align="center">
                {!! sprintf($settings->bellow_title_ads, $post->post_title) !!}
            </div>


            {{-- bing_search_result_top start --}}
            @if (config('constant.Bing_SERP_TOP') == true && 0 <
                count(is_countable($bing_search_result_url)?$bing_search_result_url:[]) ) @for ($i=0; $i <
                config('constant.Bing_SERP_TOP_COUNT'); $i++) <div class="alert bg-warning">
                <h3 class="text-primary cursorp">
                    <a href="{{ $bing_search_result_url[$i]  }}">
                        {{ $bing_search_result_title[$i] ?? "" }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z" />
                            <path fill-rule="evenodd"
                                d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z" />
                        </svg>
                    </a>
                </h3>
                <p>

                    <span class="badge bg-success">{{rand(1,36)}} hours ago</span>
                    {!! $bing_search_result_description[$i] ?? "" !!}

                    @if (config('constant.CID_STATUS'))
                    <a href="{{ route('post.cid', ['id' => $post->id, 'title' =>$bing_search_result_title[$i], 'url' => $bing_search_result_url[$i], 'dec' =>$bing_search_result_description[$i], 'slug' => URL::current()]) }}"
                        rel="nofollow" target="_blank" class="btn btn-primary btn-sm float-right"> >> Go To The
                        Portal</a>
                    @else
                    <a class="btn btn-primary btn-sm float-right" href="{{ $bing_search_result_url[$i]  }}"
                        rel="nofollow" target="_blank"> >> Go To The Portal
                    </a>
                    @endif

        </div>
        {{--loop_ads_1 Ads --}}
        <div align="center">
            {!! $settings->loop_ads_1 !!}
        </div>
        <hr>
        @endfor
        @endif
        {{-- bing_search_result_top end--}}

        <div class="article-post">
            {{-- Custom Content Above Content start--}}
            @if ((config('constant.post_content_above') == true) && !empty($postContent['post_content_above']))
            <div class="mt-5 mb-3">
                {!! $postContent['post_content_above'] !!}
            </div>
            @endif
            {{-- Custom Content Above Content en--}}

            {{-- Bing Random Image start--}}
            @if((config('constant.bing_image_first') == true) && !empty($bing_images[0]))
            <figure class="blog-banner">
                <img class="img-fluid img-responsive center-block d-block mx-auto"
                    src="{{ json_decode($bing_images[mt_rand(0,$total_images)],true)['murl'] ?? ""}}" alt="image">
                {{-- <figcaption class="mt-2 text-center image-caption">Image Credit: <a class="theme-link"
                        href="{{ json_decode($bing_images[mt_rand(0,$total_images)],true)['purl'] ?? ""}}"
                        rel="noopener noreferrer nofollow" target="_blank">{{
                        json_decode($bing_images[mt_rand(0,$total_images)],true)['t'] ?? ""
                        }}</a></figcaption> --}}
            </figure>
            @endif
            {{-- Bing Random Image end --}}


            {{-- Google And Bing Rich Snippe --}}
            @if (config('constant.bing_rich_snippet_text') == true)
            <p> {!! $bing_rich_snippet_text ?? "" !!}</p>
            @endif


            @if (config('constant.google_rich_snippet') == true)
            <p> {!! $google_rich_snippet ?? "" !!}</p>
            @endif


            {{-- Bing Full richSnippet link --}}
            @if ((config('constant.bing_rich_snippet_link') == true) &&
            !empty($bing_rich_snippet_link ) )
            <a href="{{ $bing_rich_snippet_link ?? "" }}" rel="noopener noreferrer nofollow" target="_blank">Full
                Answer</a><br><br>
            @endif


            {{-- Bing Random videostart--}}
            @if(!empty($bing_videos[0]) &&
            (config('constant.bing_videos_first') == true))
            <div class="ratio ratio-16x9">
                <iframe width="560" height="315"
                    src=" {{ str_replace('watch?v=','embed/',json_decode($bing_videos[mt_rand(0,$total_videos)],true)['pgurl']) }}"
                    frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>

            @endif
            {{-- Bing Random video end --}}


            {{-- related Keywords bing start--}}
            @if ((config('constant.bing_related_keywords') == true) && !empty($bing_related_keywords))
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



            @if ((config('constant.post_content_middle') == true) && !empty($postContent['post_content_middle']))
            <div class="mt-5 mb-3">
                {!! $postContent['post_content_middle'] ?? "" !!}
            </div>
            @endif


            {{-- Bing People Also Aks --}}
            @if ((config('constant.bing_paa') == true) && !empty($bing_paa_questions[0]))
            {{--loop_ads_1 Ads --}}
            <div align="center">
                {!! $settings->loop_ads_1 !!}
            </div>
            @for($i = 0; $i < count(is_countable($bing_paa_questions)?$bing_paa_questions:[]); $i++) <h2
                class="mt-5 mb-3">
                {!! $bing_paa_questions[$i] ?? "" !!}
                </h2>
                <p>
                    {!! $bing_paa_answers[$i] ?? "" !!}
                </p>
                @endfor



                {{-- Bing Random Image start--}}
                @if((config('constant.bing_images_second') == true) && !empty($bing_images[0]))
                <figure class="blog-banner">
                    <img class="img-fluid img-responsive center-block d-block mx-auto"
                        src="{{ json_decode($bing_images[mt_rand(0,$total_images)],true)['murl'] }}" alt="image">
                    {{-- <figcaption class="mt-2 text-center image-caption">Image Credit: <a class="theme-link"
                            href="{{ json_decode($bing_images['images'][mt_rand(0,$total_images)],true)['purl'] }}"
                            rel="noopener noreferrer nofollow" target="_blank">{{
                            json_decode($bing_images['images'][mt_rand(0,$total_images)],true)['t']
                            }}</a></figcaption> --}}
                </figure>
                @endif
                {{-- Bing Random Image end --}}
                @endif
                {{-- Bing People Also Aks end --}}





                {{-- Google Faq Questions start --}}
                @if ((config('constant.google_faq') == true) && !empty($google_faq_questions))
                {{--loop_ads_2 Ads --}}
                <div align="center">
                    {!! $settings->loop_ads_2 !!}
                </div>
                @for($i = 0; $i < count(is_countable($google_faq_questions)?$google_faq_questions:[]); $i++) <h2
                    class="mt-5 mb-3">
                    {!! $google_faq_questions[$i] ?? "" !!}
                    </h2>

                    <p>
                        {!! $google_faq_answers[$i] ?? "" !!}
                    </p>
                    @endfor

                    {{-- Bing Random videostart--}}
                    @if((config('constant.bing_videos_second') == true) && !empty($bing_videos[0]))
                    <div class="ratio ratio-16x9">
                        <iframe width="560" height="315"
                            src=" {{ str_replace('watch?v=','embed/',json_decode($bing_videos[mt_rand(0,$total_videos)],true)['pgurl']) }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    </div>

                    @endif
                    {{-- Bing Random video end --}}

                    @endif
                    {{-- Google Faq Questions end --}}



                    {{-- Bing Slider Questions start--}}
                    @if ((config('constant.bing_slider_faq') ==
                    true) && !empty($bing_slider_faq_questions) )
                    @for($i = 0; $i < count(is_countable($bing_slider_faq_questions)?$bing_slider_faq_questions:[]);
                        $i++) <h2 class="mt-5 mb-3">
                        {!! $bing_slider_faq_questions[$i] ?? "" !!}
                        </h2>

                        <p>
                            {!! $bing_slider_faq_answers[$i] ?? "" !!}
                        </p>
                        @endfor
                        @endif
                        {{-- Bing Slider Questions start end--}}


                        {{-- related Keywords Google start--}}
                        @if ( (config('constant.google_related_keywords') ==
                        true) && !empty($google_related_keywords))
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
                        @if ((config('constant.pop_questions') ==
                        true) && !empty($bing_pop_faq_questions))
                        {{--loop_ads_3 Ads --}}
                        <div align="center">
                            {!! $settings->loop_ads_3 !!}
                        </div>
                        @for($i = 0; $i < count(is_countable($bing_pop_faq_questions)?$bing_pop_faq_questions:[]); $i++)
                            <h2 class="mt-5 mb-3">
                            {!! $bing_pop_faq_questions[$i] ?? "" !!}
                            </h2>

                            <p>
                                {!! $bing_pop_faq_answers[$i] ?? "" !!}
                            </p>
                            @endfor

                            {{-- Bing Random Image start--}}
                            @if(!empty($bing_images[0])) <figure class="blog-banner">
                                <img class="img-fluid img-responsive center-block d-block mx-auto"
                                    src="{{ json_decode($bing_images[mt_rand(0,$total_images)],true)['murl'] }}"
                                    alt="image">
                                {{-- <figcaption class="mt-2 text-center image-caption">Image Credit: <a
                                        class="theme-link"
                                        href="{{ json_decode($bing_images[mt_rand(0,$total_images)],true)['purl'] }}"
                                        rel="noopener noreferrer nofollow" target="_blank">{{
                                        json_decode($bing_images[mt_rand(0,$total_images)],true)['t']
                                        }}</a></figcaption> --}}
                            </figure>
                            @endif
                            {{-- Bing Random Image end --}}

                            @endif
                            {{-- Bing pop Questions start end--}}




                            {{-- Bing bing_tab_faq_questions Questions start--}}
                            @if (!empty($bing_tab_faq_questions[0]) && (config('constant.bing_tab_faq') ==
                            true))
                            @for($i = 0; $i < count(is_countable($bing_tab_faq_questions)?$bing_tab_faq_questions:[]);
                                $i++) <h2 class="mt-5 mb-3">
                                {!! $bing_tab_faq_questions[$i] ?? "" !!}
                                </h2>

                                <p>
                                    {!! $bing_tab_faq_answers[$i] ?? "" !!}
                                </p>
                                @endfor
                                @endif
                                {{-- Bing bing_tab_faq_questions Questions start end--}}


                                @if (!empty($postContent['post_content_after']) &&
                                (config('constant.post_content_after') ==
                                true))
                                <div class="mt-5 mb-3">
                                    {!! $postContent['post_content_after'] !!}
                                </div>
                                @endif


                                {{-- Popular Posts start--}}
                                @if (config('constant.popular_post') ==
                                true)
                                <div class="mt-5 mb-3">
                                    <h2 class="title">Popular Posts:</h2>
                                    <ul>

                                        @foreach ($sidebar as $item)
                                        <li class="list-group-item">
                                            {{ $loop->iteration }}. <a href="{{ route('post.show',$item->slug) }}"
                                                title="{{ $item->post_title }}"> {{
                                                $item->post_title }}
                                                <sup><i class="fa fa-external-link" aria-hidden="true"></i></sup></a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
        </div>


        {{-- bing_search_result start --}}
        @if (config('constant.Bing_SERP') == true && 0 <
            count(is_countable($bing_search_result_url)?$bing_search_result_url:[]) ) @for ($i=0; $i <
            count($bing_search_result_url); $i++) <div class="post-box mt-2">
            <h3 class="text-primary cursorp">{{ $i+1 }}.{{ $bing_search_result_title[$i] ?? "" }}
            </h3>
            <p><strong>Url:</strong>{{ $bing_search_result_url[$i] ?? "" }}</p>
            <p>
                <span class="badge bg-success">{{rand(1,36)}} hours ago</span>
                {!! $bing_search_result_description[$i] ?? "" !!}
            <div class="d-lg-flex align-items-center justify-content-between">
                <div class="text-end">
                    <span class="float-right">
                        <a href="{{ $bing_search_result_url[$i] }}" rel="nofollow" target="_blank"
                            class="btn btn-primary btn-sm">Show details
                            <i class="fa fa-caret-right" aria-hidden="true"></i></a></span>
                </div>
            </div>
    </div>
    @endfor
    @endif
    {{-- bing_search_result end--}}


    {{-- bing_news start--}}
    @if (config('constant.Bing_news')==true && 0 < count(is_countable($bing_news_title)?$bing_news_title:[]) )
        @for($i=0; $i < count(is_countable($bing_news_title)?$bing_news_title:[]); $i++) <div
        class="post-box mt-2 bg-warning bg-gradient bg-opacity-70">
        <h3 class="text-primary cursorp">
            {{ $i+1 }}. {{ $bing_news_title[$i]}}</h3>
        <p>
            <span class="badge bg-success">{{rand(1,36)}} hours ago</span>
            {{ $bing_news_description[$i] }}

        <div class="d-lg-flex align-items-center justify-content-between">
            <span class="badge rounded-pill bg-info text-dark">{{ $indexedArray[array_rand($indexedArray)]
                }}</span>
            <div>
                <strong>{{rand(1,360000)}} People Read</strong>
            </div>
        </div>
</div>
@endfor
@endif
{{-- bing_news end--}}
</div>

<div class="hideshare"></div>
@endsection