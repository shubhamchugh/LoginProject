<nav class="navbar navbar-expand-lg navbar-dark">

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navigation"
        aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div id="navigation" class="collapse navbar-collapse flex-column">
        <div class="profile-section pt-3 pt-lg-0">
            <img class="profile-image mb-3 rounded-circle mx-auto"
                src="{{ asset('themes/DevBlog/assets/images/profile.png') }}" alt="image">

            <div class="bio mb-3">Hi, my name is {{ config('app.name') }}. Briefly introduce yourself here. You can also
                provide a link to the about page.<br>
                {{-- <a href="about.html">Find out more about me</a> --}}
            </div>
            <!--//bio-->
            {{-- <ul class="social-list list-inline py-3 mx-auto">
                <li class="list-inline-item"><a href="#"><i class="fab fa-twitter fa-fw"></i></a></li>
                <li class="list-inline-item"><a href="#"><i class="fab fa-linkedin-in fa-fw"></i></a></li>
                <li class="list-inline-item"><a href="#"><i class="fab fa-github-alt fa-fw"></i></a></li>
                <li class="list-inline-item"><a href="#"><i class="fab fa-stack-overflow fa-fw"></i></a></li>
            </ul> --}}
            <!--//social-list-->
            <hr>
        </div>
        <!--//profile-section-->

        <ul class="navbar-nav flex-column text-start">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('index') }}"><i class="fas fa-home fa-fw me-2"></i>Blog Home
                    <span class="sr-only">(current)</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('docs',['about']) }}"><i class="fas fa-user fa-fw me-2"></i>About
                    Me</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('docs',['dmca']) }}"><i
                        class="fas fa-bookmark fa-fw me-2"></i>DMCA</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('docs',['privacy']) }}"><i
                        class="fas fa-bookmark fa-fw me-2"></i>Privacy</a>
            </li>
        </ul>

        <div class="my-2 my-md-3">
            <a class="btn btn-primary" href="{{ route('docs',['contact']) }}" target="_blank">Contact</a>
        </div>
    </div>
</nav>