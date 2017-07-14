@extends('layouts.application.auth')

@section('title')
    {{ __('Dashboard::views/user/create.page.title') }}
@endsection

@section('content')
    @include('Dashboard::user.common.create-edit', [
        'user' => null,
    ])
@endsection