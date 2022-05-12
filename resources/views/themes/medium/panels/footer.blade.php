<!-- Begin Footer
================================================== -->
<div class="container">
    <div class="footer">
        <p class="pull-left">
            Copyright &copy; 2017 <a href="{{ route('index') }}">{!! $settings->site_name !!}</a>
        </p>
        <p class="pull-right">
            <a href="{{ route('index') }}">Home <span class="sr-only">(current)</span></a> |
            <a href="{{ route('docs',['about']) }}">About</a> |
            <a href="{{ route('docs',['dmca']) }}">Disclaimer</a> |
            <a href="{{ route('docs',['privacy']) }}">Privacy</a> |
            <a href="{{ route('docs',['contact']) }}">Contact</a>
        </p>
        <div class="clearfix">
        </div>
    </div>
</div>
<!-- End Footer
================================================== -->