@php
    /**
     * @var int $errorCode
     */
@endphp

@extends('layouts.app.auto')

@section('content')
    <div class="row">
        <div class="container-fluid">
            <div class="jumbotron">
                <h1>
                    {{ __(sprintf('views/dashboard/help/http-error.errors.%d.title', $errorCode)) }}
                </h1>

                @foreach(__(sprintf('views/dashboard/help/http-error.errors.%d.description', $errorCode)) as $descriptionLine)
                    <p>
                        {{ $descriptionLine }}
                    </p>
                @endforeach
            </div>
        </div>
    </div>
@endsection