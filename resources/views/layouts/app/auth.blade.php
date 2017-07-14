@extends('layouts.app.base')

@section('layout-title')
    {{ config('app.name') }}
    {{-- @todo page title --}}
@endsection

@section('layout-body')
    <div id="app" class="container-fluid no-gutter app-auth {{ Controller::getViewCssClass() }}">
        @include('layouts.app.auth.top-menu')

        <div id="under-top-menu">
            @include('layouts.app.auth.sidebar')
            @include('layouts.app.auth.content')
        </div>
    </div>
@endsection