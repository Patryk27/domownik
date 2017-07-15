@php
    /**
     * @var $user \App\Models\User
     */
@endphp

@extends('layouts.app.auth')

@section('title')
    {{ __('views/dashboard/user/edit.page.title', [
        'userName' => $user->full_name
    ]) }}
@endsection

@section('content')
    @include('views.dashboard.user.common.create-edit', [
        'user' => $user,
    ])
@endsection