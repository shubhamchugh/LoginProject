<!-- Begin Footer
================================================== -->
<div class="container">
    <div class="px-3 py-5 p-md-5">
        @foreach (array_merge(range('A', 'Z'),range(1,9)) as $char)
        <a href='{{ route("sitemap.show", ["sitemap" => $char]) }}' class="btn   text-center mt-2 "><strong>{{
                $char
                }}</strong></a>

        @endforeach
    </div>
    <div class="footer">
        <p class="pull-left">
            Copyright &copy; 2022 <a href="{{ route('index') }}">{!! $settings->site_name !!}</a> @version
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