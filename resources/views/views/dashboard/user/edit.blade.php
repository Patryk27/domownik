@php
    /**
     * @var $user \App\Models\User
     */
@endphp

@extends('layouts.application.auth')

@section('title')
    {{ __('Dashboard::views/user/edit.page.title', [
        'userName' => $user->full_name
    ]) }}
@endsection

@section('content')
    @include('Dashboard::user.common.create-edit', [
        'user' => $user,
    ])
@endsection