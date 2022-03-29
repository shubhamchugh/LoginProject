@extends('themes.DevBlog.layouts.master')


@section('title', 'Home Page')
@section('content')
<article class="blog-post px-3 py-5 p-md-5">
        <div class="container single-col-max-width">
                <header class="blog-post-header">
                        <h1 class="title text-capitalize  mb-2">Disclaimer</h1>
                        <div class="meta mb-3">
                        </div>
                </header>

                <div class="blog-post-body">
                        <div class="">
                                <div>We are an independent website trying to help the internet users find correct login
                                        websites and secure
                                        their Online Banking, Credit Card, Emails, and other login information.
                                        </br> We are not associated with any brands mentioned on this website. All
                                        brands, logos, and websites
                                        mentioned we are directing to belong to respective organizations.

                                </div>
                                <p>&nbsp;</p>
                                <h3><strong>DMCA Copyright Infringement Notification</strong></h3>
                                <p><span style="font-weight: 400;">All trademarks, registered trademarks, product names
                                                and company names or
                                                logos
                                                appearing on the site are the property of their respective owners. We
                                                abides by the
                                                federal
                                                Digital Millennium Copyright Act (DMCA) by responding to notices of
                                                alleged infringement that
                                                complies with
                                                the DMCA and other applicable laws. As part of our response, we may
                                                remove or disable access to
                                                material
                                                residing on site that is controlled or operated by {{
                                                $settings->site_name
                                                }} that is claimed to
                                                be
                                                infringing, in
                                                which case we will make a good-faith attempting to contact the developer
                                                who submitted the
                                                affected material
                                                so that they may make a counter notification, also in accordance with
                                                the DMCA.</span></p>
                                <p>&nbsp;</p>
                                <p><span style="font-weight: 400;">Before serving either a Notice of Infringing Material
                                                or
                                                Counter-Notification, You can can <b>Contact Us</b> directly for easy
                                                resolution.
                                        </span></p>
                                <p>&nbsp;</p>

                        </div>

                </div>

        </div>
        <!--//container-->
</article>
@endsection



@section('head')
<meta name="description" content="DMCA Disclaimer">
<title>DMCA Disclaimer</title>
@endsection