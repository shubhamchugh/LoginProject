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
    <?php $color_code = (!empty($settings->theme_color)) ? $settings->theme_color : 1;
    $css_path  = "themes/DevBlog/assets/css/theme-$color_code.css";
    ?>
    <link id="theme-style" rel="stylesheet" href="{{ asset($css_path) }}">

    @section('head')
    @show
    {!! $settings->header_code !!}
    <!-- MINIFIED -->
    {!! SEO::generate() !!}

</head>