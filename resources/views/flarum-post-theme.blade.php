<t>
</t>
<div>
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
    @for($i = 0; $i < count(is_countable($bing_paa_questions)?$bing_paa_questions:[]); $i++) <h2 class="mt-5 mb-3">
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
                    frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>

            @endif
            {{-- Bing Random video end --}}

            @endif
            {{-- Google Faq Questions end --}}



            {{-- Bing Slider Questions start--}}
            @if ((config('constant.bing_slider_faq') ==
            true) && !empty($bing_slider_faq_questions) )
            @for($i = 0; $i < count(is_countable($bing_slider_faq_questions)?$bing_slider_faq_questions:[]); $i++) <h2
                class="mt-5 mb-3">
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
                @for($i = 0; $i < count(is_countable($bing_pop_faq_questions)?$bing_pop_faq_questions:[]); $i++) <h2
                    class="mt-5 mb-3">
                    {!! $bing_pop_faq_questions[$i] ?? "" !!}
                    </h2>

                    <p>
                        {!! $bing_pop_faq_answers[$i] ?? "" !!}
                    </p>
                    @endfor

                    {{-- Bing Random Image start--}}
                    @if(!empty($bing_images[0])) <figure class="blog-banner">
                        <img class="img-fluid img-responsive center-block d-block mx-auto"
                            src="{{ json_decode($bing_images[mt_rand(0,$total_images)],true)['murl'] }}" alt="image">
                        {{-- <figcaption class="mt-2 text-center image-caption">Image Credit: <a class="theme-link"
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
                    @for($i = 0; $i < count(is_countable($bing_tab_faq_questions)?$bing_tab_faq_questions:[]); $i++) <h2
                        class="mt-5 mb-3">
                        {!! $bing_tab_faq_questions[$i] ?? "" !!}
                        </h2>

                        <p>
                            {!! $bing_tab_faq_answers[$i] ?? "" !!}
                        </p>
                        @endfor
                        @endif
                        {{-- Bing bing_tab_faq_questions Questions start end--}}









</div>
</div>

{{-- bing_search_result start --}}
@if (config('constant.Bing_SERP') == true && 0 < count(is_countable($bing_search_result_url)?$bing_search_result_url:[])
    ) @for ($i=0; $i < count($bing_search_result_url); $i++) <div class="post-box mt-2">
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
    </div>
    </t>