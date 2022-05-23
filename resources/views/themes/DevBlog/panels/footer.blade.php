<!--*********************************************************************************************************-->
<!--************ FOOTER *************************************************************************************-->
<!--*********************************************************************************************************-->

<div class="px-3 py-5 p-md-5">
    @foreach (array_merge(range('A', 'Z'),range(1,9)) as $char)
    <a href='{{ route("sitemap.show", ["sitemap" => $char]) }}'
        class="btn btn-outline-primary text-center mt-2 "><strong>{{
            $char
            }}</strong></a>

    @endforeach
</div>


<footer class="footer text-center py-2 theme-bg-dark">
    <small class="copyright">Designed with <i class="fas fa-heart" style="color: #fb866a;"></i> by <a href="#"
            target="_blank">{{ $settings->site_name }}</a>
        <div id="version_check"> @version</div>
    </small>

</footer>