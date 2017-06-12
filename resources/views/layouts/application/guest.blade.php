@extends('layouts.application.base')

@section('layout-head-title')
    {{ config('app.name') }}
@endsection

@section('layout-body')
    <div id="app" class="container-fluid app-guest {{ Controller::getViewCssClass() }}">
        <div class="text-center">
            <h1 id="app-name">
                {{ config('app.name') }}
            </h1>
        </div>

        <div id="messages-container">
            @include('layouts.common.messages')
        </div>

        <div id="content" class="container-fluid">
            @yield('content')
        </div>
    </div>
@endsection