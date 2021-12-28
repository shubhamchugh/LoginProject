<!--*********************************************************************************************************-->
<!--************ FOOTER *************************************************************************************-->
<!--*********************************************************************************************************-->


<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-4 col-xl-4">
                <div class="widget-title">About US</div>
                <p class="mb-1">The display of third-party trademarks and trade names on this site does not necessarily
                    indicate
                    any affiliation or endorsement of webcontactus.com.</p>
                <p>If you click a merchant link and buy a product or service on their website, we may be paid a fee by
                    the
                    merchant.</p>
            </div>
            <div class="col-12 col-md-4 col-lg-4 col-xl-4">
                <div class="widget-title">Popular Search</div>
                <ul class="search-list">

                    @foreach ($sidebar as $item)
                    <li>
                        <a href="{{ route('post.show',$item->slug) }}">{{ $item->post_title }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-12 col-md-4 col-lg-4 col-xl-4">
                <div class="widget-title">Your Title</div>
                @foreach (array_merge(range('A', 'Z'),range(1,9)) as $char)
                <a href='{{ route("sitemap.show", ["sitemap" => $char]) }}' class="widget-btn"><strong>{{ $char
                        }}</strong></a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="copyright text-center">
        © 2021 <a href="#">{{ config('app.name') }}</a>. All rights reserved
    </div>
</footer>

@include('themes.civic.panels.scripts')