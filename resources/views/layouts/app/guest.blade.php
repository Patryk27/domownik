@extends('layouts.app.base')

@section('layout-title')
    {{ config('app.name') }}
@endsection

@section('layout-content')
    <body class="app app-guest container-fluid {{ Controller::getViewCssClass() }}">
    <h1 class="app-name">
        {{ config('app.name') }}
    </h1>

    <div class="messages-container">
        @include('components.layout.messages')
    </div>

    <div class="main-container container-fluid">
        @yield('content')
    </div>
    </body>
@endsection