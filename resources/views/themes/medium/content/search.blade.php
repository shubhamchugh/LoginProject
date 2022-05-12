@extends('themes.medium.layouts.master')
@section('content')
<!-- Begin Article
================================================== -->
<div class="container">
    <div class="row">

        @include('themes.medium.panels.sidebar')

        <!-- Begin Post -->
        <div class="col-md-8 col-md-offset-2 col-xs-12">
            <div class="mainheading">

                <h1 class="posttitle">Faq List</h1>

            </div>


            <!-- Begin Post Content -->
            <div class="article-post">
                <script async src="{{ config('constant.GOOGLE_SEARCH') }}"></script>
                <div class="gcse-searchresults-only"></div>
            </div>
            <!-- End Post Content -->
        </div>
        <!-- End Post -->

    </div>
</div>
<!-- End Article
================================================== -->

<div class="hideshare"></div>






@endsection