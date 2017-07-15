@extends('layouts.app.auth')

@section('title')
    {{ __('views/dashboard/user/create.page.title') }}
@endsection

@section('content')
    @include('views.dashboard.user.common.create-edit', [
        'user' => null,
    ])
@endsection