<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FontAwesome JS-->
    <script defer src="{{ asset('themes/DevBlog/assets/fontawesome/js/all.min.js') }}"></script>
    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="{{ asset('themes/DevBlog/assets/css/theme-1.css') }}">

    @section('head')
    @show
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('constant.Google_Analytics')  }}">
    </script>
    <script>
        window.dataLayer = window.dataLayer || [];
         function gtag(){dataLayer.push(arguments);}
         gtag('js', new Date());
         gtag('config', {{ config('constant.Google_Analytics') }});
    </script>
    <meta name="google-site-verification" content="{{ config('constant.Google_Search_console')  }}" />

</head>