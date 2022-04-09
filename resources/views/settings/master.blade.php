@include('themes.DevBlog.panels.head')

<body>

    <div class="main-wrapper">
        @section('pagetitle')

        @show
        @if (config('app.debug'))
        @section('content')

        @show
        @else

        Please Enable Debug to edit Settings

        @endif

    </div>
    <!--//main-wrapper-->


    @include('themes.DevBlog.panels.scripts')


</body>

</html>