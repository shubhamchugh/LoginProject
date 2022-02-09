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

    {{ $HeaderCode->value ?? "" }}
    @section('head')
    @show

</head>