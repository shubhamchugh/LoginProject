<!--*********************************************************************************************************-->
<!--************ FOOTER *************************************************************************************-->
<!--*********************************************************************************************************-->

<div class="col-xl-12 col-md-12 col-xs-12 text-center">
    @foreach (array_merge(range('A', 'Z'),range(1,9)) as $char)
    <a href='{{ route("sitemap.show", ["sitemap" => $char]) }}' class="btn btn-outline-primary
                    btn-sm"><strong>{{ $char }}</strong></a>

    @endforeach

</div>


<footer class="footer text-center py-2 theme-bg-dark">

    <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
    <small class="copyright">Designed with <i class="fas fa-heart" style="color: #fb866a;"></i> by <a href="#"
            target="_blank">{{ config('app.name') }}</a> for developers</small>

</footer>