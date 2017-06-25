<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
<div id="app" class="app-unlogged">
    {{-- Nazwa aplikacji --}}
    <div class="text-center">
        <h1 id="app-name">
            {{ config('app.name') }}
        </h1>
    </div>

    {{-- Wiadomości --}}
    <div id="messages-container">
        @if (session()->has('flash_notification.message'))
            <div class="alert alert-{{ session('flash_notification.level') }}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {!! session('flash_notification.message') !!}
            </div>
        @endif

        @if (count($errors) > 0)
            <div>
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    {{-- Główna treść szablonu --}}
    <div id="content">
        @yield('content')
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
