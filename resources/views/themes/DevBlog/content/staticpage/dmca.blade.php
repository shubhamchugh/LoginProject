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
                                <div>We are an independent website trying to help the internet users by providing the
                                        relevant answer from different references for their query.

                                        We are not associated with any government or private organisation. We are just
                                        gathering information from different sources to provide the user all info at one
                                        portal.

                                        We are not associated with any brands mentioned on {{ request()->getHost() }}.
                                        All brands,
                                        logos, and websites mentioned we are directing to belong to respective
                                        organisations. If you have any issue with any content, you may directly contact
                                        us.


                                </div>
                                <p>&nbsp;</p>
                                <h3><strong>DMCA Copyright Infringement Notification</strong></h3>
                                <p><span style="font-weight: 400;">All trademarks, registered trademarks, product names
                                                and company names or logos appearing on the site are the property of
                                                their respective owners. We abides by the federal Digital Millennium
                                                Copyright Act (DMCA) by responding to notices of alleged infringement
                                                that complies with the DMCA and other applicable laws. As part of our
                                                response, we may remove or disable access to material residing on site
                                                that is controlled or operated by {{ request()->getHost() }} that is
                                                claimed to be
                                                infringing, in which case we will make a good-faith attempting to
                                                contact the developer who submitted the affected material so that they
                                                may make a counter notification, also in accordance with the DMCA.
                                                <br>
                                                Before serving either a Notice of Infringing Material or
                                                Counter-Notification, You can Contact Us directly for easy resolution.

                                        </span></p>
                                <p>&nbsp;</p>
                                <h3><strong>Terms & Conditions</strong></h3>
                                <p><span style="font-weight: 400;">These Website Standard Terms and Conditions written
                                                on this webpage shall manage your use of our website - this site. These
                                                Terms will be applied fully and effectively to your use of {{
                                                request()->getHost() }}.
                                                By using {{ request()->getHost() }}, you agreed to accept all terms and
                                                conditions
                                                written in here. You must not use {{ request()->getHost() }} if you
                                                disagree with any
                                                of these Website Standard Terms and Conditions. Minors or people below
                                                18 years old are not allowed to use {{ request()->getHost() }}.
                                        </span></p>
                                <h3><strong>Intellectual Property Rights</strong></h3>
                                <p>Other than the content you own, under these Terms, this site and/or its licensors own
                                        all the intellectual property rights and materials contained in {{
                                        request()->getHost() }}.
                                        You are granted the limited license only for purposes of viewing the material
                                        contained on {{ request()->getHost() }}
                                </p>
                                <h3><strong>Intellectual Property Rights</strong></h3>
                                <p>You are specifically restricted from all of the following:
                                </p>
                                <br>
                                <p>
                                        publishing any Website material in any other media;
                                        selling, sublicensing and/or otherwise commercializing any Website material;
                                        publicly performing and/or showing any Website material;
                                        using {{ request()->getHost() }} in any way that is or may be damaging to {{
                                        request()->getHost() }};
                                        using {{ request()->getHost() }} in any way that impacts user access to {{
                                        request()->getHost() }};
                                        using {{ request()->getHost() }} contrary to applicable laws and regulations, or
                                        in any way
                                        may cause harm to the Website, or to any person or business entity;
                                        engaging in any data mining, data harvesting, data extracting or any other
                                        similar activity in relation to {{ request()->getHost() }};
                                        using {{ request()->getHost() }} to engage in any advertising or marketing.
                                </p>
                                <p>Certain areas of {{ request()->getHost() }} are restricted from being access by you
                                        and this site
                                        may further restrict access by you to any areas of {{ request()->getHost() }},
                                        at any time, in
                                        absolute discretion. Any user ID and password you may have for {{
                                        request()->getHost() }} are
                                        confidential and you must maintain confidentiality as well.
                                </p>
                                <h3><strong>Your Content</strong></h3>
                                <p>In these Website Standard Terms and Conditions, "Your Content" shall mean any audio,
                                        video text, images or other material you choose to display on {{
                                        request()->getHost() }}. By
                                        displaying Your Content, you grant this site a non-exclusive, worldwide
                                        irrevocable, sub-licensable license to use, reproduce, adapt, publish, translate
                                        and distribute it in any and all media. Your Content must be your own and must
                                        not be invading any third-party’s rights. this site reserves the right to remove
                                        any of Your Content from {{ request()->getHost() }} at any time without notice.
                                </p>
                                <h3><strong>Your Privacy</strong></h3>
                                <p>
                                        Please read our Privacy Policy.

                                </p>
                                <h3><strong>No warranties</strong></h3>
                                <p>
                                        {{ request()->getHost() }} is provided "as is," with all faults, and this site
                                        expresses no
                                        representations or warranties, of any kind related to {{ request()->getHost() }}
                                        or the
                                        materials contained on {{ request()->getHost() }}. Also, nothing contained on {{
                                        request()->getHost() }}
                                        shall be interpreted as advising you.
                                </p>
                                <h3><strong>Limitation of liability</strong></h3>
                                <p>
                                        In no event shall this site, nor any of its officers, directors, and employees
                                        shall be held liable for anything arising out of or in any way connected with
                                        your use of {{ request()->getHost() }} whether such liability is under contract.
                                        this site,
                                        including its officers, directors, and employees shall not be held liable for
                                        any indirect, consequential or special liability arising out of or in any way
                                        related to your use of {{ request()->getHost() }}.

                                </p>
                                <h3><strong>Indemnification</strong></h3>
                                <p>You hereby indemnify to the fullest extent this site from and against any and/or all
                                        liabilities, costs, demands, causes of action, damages and expenses arising in
                                        any way related to your breach of any of the provisions of these Terms.
                                </p>
                                <h3><strong>Severability
                                        </strong></h3>
                                <p>If any provision of these Terms is found to be invalid under any applicable law, such
                                        provisions shall be deleted without affecting the remaining provisions herein.
                                </p>
                                <h3><strong>Variation of Terms
                                        </strong></h3>
                                <p>
                                        this site is permitted to revise these Terms at any time as it sees fit, and by
                                        using {{ request()->getHost() }} you are expected to review these Terms on a
                                        regular basis.

                                </p>
                                <h3><strong>Assignment</strong></h3>
                                The this site is allowed to assign, transfer, and subcontract its rights and/or
                                obligations under these Terms without any notification. However, you are not allowed to
                                assign, transfer, or subcontract any of your rights and/or obligations under these
                                Terms.

                                <h3><strong>Entire Agreement
                                        </strong></h3>
                                <p>
                                        These Terms constitute the entire agreement between this site and you in
                                        relation to your use of {{ request()->getHost() }} and supersede all prior
                                        agreements and
                                        understandings.

                                </p>
                                <h3><strong>Entire Agreement
                                        </strong></h3>
                                <p>These Terms constitute the entire agreement between this site and you in relation to
                                        your use of {{ request()->getHost() }} and supersede all prior agreements and
                                        understandings.
                                </p>
                                <h3><strong>Governing Law & Jurisdiction</strong></h3>
                                <p>
                                        These Terms will be governed by and interpreted in accordance with the laws of
                                        the State of in, and you submit to the non-exclusive jurisdiction of the state
                                        and federal courts located in for the resolution of any disputes.

                                </p>
                        </div>

                </div>

        </div>
        <!--//container-->
        {{-- Popular Posts start--}}
        @if (config('constant.popular_post') ==
        true)
        <div class="mt-5 mb-3">
                <h2 class="title">Popular Posts:</h2>
                @foreach ($sidebar as $item)
                <li class="list-group-item">
                        {{ $loop->iteration }}. <a href="{{ route('post.show',$item->slug) }}"
                                title="{{ $item->post_title }}"> {{
                                $item->post_title }}
                                <sup><i class="fa fa-external-link" aria-hidden="true"></i></sup></a>
                </li>
                @endforeach
                @endif
        </div>
        {{-- Bing Slider Questions end--}}

</article>
@endsection



@section('head')
<meta name="description" content="DMCA Disclaimer">
<title>DMCA Disclaimer</title>
@endsection