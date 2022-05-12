<!-- Begin Nav
================================================== -->
<nav class="navbar navbar-toggleable-md navbar-light bg-white fixed-top mediumnavigation">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
        data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="container">
        <!-- Begin Logo -->
        <a class="navbar-brand" href="{{ route('index') }}">
            {!! $settings->site_name !!}
            {{-- <img src="{{ asset('themes/medium/assets/img/logo.png') }}" alt="logo"> --}}
        </a>
        <!-- End Logo -->
        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <!-- Begin Menu -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('index') }}">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('docs',['about']) }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('docs',['dmca']) }}">Disclaimer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('docs',['privacy']) }}">Privacy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('docs',['contact']) }}">Contact</a>
                </li>
            </ul>
            <!-- End Menu -->
            @include('themes.medium.panels.search')
        </div>
    </div>
</nav>
<!-- End Nav
    ================================================== -->