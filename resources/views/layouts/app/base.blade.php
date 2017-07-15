<!DOCTYPE html>
<html lang="{{ Configuration::getLanguage()  }}">
<head>
    {{-- Meta data --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('layout-title')
    </title>

    {{-- Stylesheets --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/third-party.css') }}" rel="stylesheet">

    {{-- Scripts --}}
    <script>
      window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    <script src="{{ asset('js/localization.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/third-party.js') }}"></script>

    <script>
      window.App.Configuration.setLocale('{{ Configuration::getLanguage() }}');
    </script>

    @stack('scripts')
</head>
<body>
@yield('layout-body')
</body>