<!DOCTYPE html>
<html>
<head>
    {{-- Meta data --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @yield('layout-head-title')
    </title>

    {{-- Stylesheets --}}
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    @stack('scripts')
</head>
<body>
@yield('layout-body')
</body>