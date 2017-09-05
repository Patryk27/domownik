@extends('layouts.app.base')

@section('layout-title')
    {{ config('app.name') }}
    {{-- @todo page title --}}
@endsection

@section('layout-content')
    <body class="app app-auth {{ Controller::getViewCssClass() }}">
    @include('layouts.app.auth.navbar')

    <div class="main-container">
        @include('layouts.app.auth.sidebar')
        @include('layouts.app.auth.content')
    </div>
    </body>
@endsection