@extends('themes.civic.layouts.master')


@section('title', 'Home Page')
@section('content')
<div class="container margin_60_35">
    <div class="detail_title_1">
        <h1>About Us</h1>
    </div>
    <p> We are web devlopment agency who is working from last 10 years in the industry.</p>
    <p>We as a {{ config('app.name') }} trying to help all internet users by providing the list of trusted pages as per
        their query. </p>
    <p>With our site {{ config('app.name') }}, you can easily find desired website and pages easily. Just search and
        navigate to the listed pages.
        If you find any issue you can fill our contact us page we will try to help you.</p>
</div>
<!-- /container -->

@endsection



@section('head')
<meta name="description" content="Abouts Us">
<title>Abouts Us</title>
@endsection