@php
    /**
     * @var $user \App\Models\User
     */
@endphp

@extends('layouts.app.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-edit"></i>&nbsp;
                {{ __('views/dashboard/user/edit.page.title', [
                    'userName' => $user->full_name
                ]) }}
            </div>
        </div>

        @include('views.dashboard.user.common.create-edit', [
            'user' => $user,
        ])
    </div>
@endsection