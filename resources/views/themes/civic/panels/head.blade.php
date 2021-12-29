<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('themes/civic/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/civic/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/civic/css/custom.css') }}">

    {{ $HeaderCode->value ?? "" }}
    @section('head')
    @show
</head>