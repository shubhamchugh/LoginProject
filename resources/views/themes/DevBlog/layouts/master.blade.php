@include('themes.DevBlog.panels.head')

<body>

    @include('themes.DevBlog.panels.header')

    <div class="main-wrapper">
        @section('pagetitle')

        @show

        @section('content')

        @show

        @include('themes.DevBlog.panels.footer')

    </div>
    <!--//main-wrapper-->


    @include('themes.DevBlog.panels.scripts')


</body>

</html>