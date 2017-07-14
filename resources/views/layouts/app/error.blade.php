@extends('layouts.app.base')

@section('layout-head-title')
    {{ config('app.name') }} - {{ __('errors.page-title') }}
@endsection

@section('layout-body')
    <div id="app" class="container-fluid app-error">
        <div class="text-center">
            <h1 id="app-name">
                {{ config('app.name') }}
            </h1>
        </div>

        <div id="messages-container">
            @include('layouts.common.messages')
        </div>

        <div id="content" class="container-fluid">
            <div class="jumbotron">
                <h1>
                    {{ __('errors.header') }}
                </h1>

                @yield('content')

                <p>
                    @if (Auth::check())
                        <a class="btn btn-primary btn-lg" href="/dashboard/help/error-404" role="button">
                            {{ __('errors.learn-more') }}
                        </a>
                    @endif

                    <a class="btn btn-info btn-lg" href="/" role="button">
                        {{ __('errors.show-homepage') }}
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection