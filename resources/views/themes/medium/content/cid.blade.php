@extends('themes.medium.layouts.master')


@section('content')
<div class="container">
    <br>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-md-12 col-12 mb-2">
            <section class="py-5 text-center container">
                <div class="row py-lg-5">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        <h1 class="fw-light">{{ $title }}</h1>
                        <p class="lead text-muted">{{ $dec }}.
                        </p>
                        <p>
                            <a href="{{ $slug }}" class="btn btn-primary my-2">Back to list</a>
                            <a href="{{ $url }}" class="btn btn-secondary my-2" rel="nofollow" target="_blank">Go To The
                                Website</a>
                        </p>
                    </div>
                </div>
                <div align="center">
                    {!! $settings->bellow_title_ads !!}
                </div>
            </section>
        </div>

    </div>
    {{-- Popular Posts start--}}
    @if (config('constant.popular_post') ==
    true)
    <div class="mt-5 mb-3">
        <h2 class="title">Popular Posts:</h2>
        <ul>
            @foreach ($sidebar as $item)
            <li class="list-group-item">
                {{ $loop->iteration }}. <a href="{{ route('post.show',$item->slug) }}" title="{{ $item->post_title }}">
                    {{
                    $item->post_title }}
                    <sup><i class="fa fa-external-link" aria-hidden="true"></i></sup></a>
            </li>
            @endforeach
        </ul>

    </div>
    @endif

</div>
@endsection