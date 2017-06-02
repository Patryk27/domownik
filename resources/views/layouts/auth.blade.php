@extends('layouts/base')

@section('layout-head-title')
    {{ config('app.name') }} - {{ Translation::getModuleName($activeModule->getName()) }}
@endsection

@section('layout-body')
    <div id="app" class="container-fluid no-gutter app-auth {{ Controller::getViewCssClass() }}">
        @include('layouts/auth/top-menu')

        <div id="under-top-menu">
            @include('layouts/auth/sidebar')
            @include('layouts/auth/content')
        </div>
    </div>
@endsection