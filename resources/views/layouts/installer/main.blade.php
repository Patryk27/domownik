@extends('layouts.installer.base')

@section('layout-head-title')
    {{ config('app.name') }}
@endsection

@section('layout-body')
    <div id="app" class="container-fluid app-installer {{ Controller::getViewCssClass() }}">
        <div class="text-center">
            <h1 id="app-name">
                {{ config('app.name') }}
            </h1>
        </div>

        <div id="messages-container">
            @include('components.layout.messages')
        </div>

        <div id="content" class="container-fluid">
            @yield('content')
        </div>
    </div>
@endsection