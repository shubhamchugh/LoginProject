<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ asset('faq-icon.png') }}" />

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('themes/medium/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Fonts -->
    <link href="{{ asset('themes/medium/assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Righteous%7CMerriweather:300,300i,400,400i,700,700i"
        rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('themes/medium/assets/css/mediumish.css') }}" rel="stylesheet">


    @section('head')
    @show
    {!! $settings->header_code !!}
    <!-- MINIFIED -->
    {!! SEO::generate() !!}


</head>