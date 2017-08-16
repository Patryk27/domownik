@php
    /**
     * @var $users \App\Models\User[]
     */
@endphp

@extends('layouts.app.auth')

@section('content')
    {{-- User management --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-cogs"></i>&nbsp;
                {{ __('views/dashboard/users/index.user-management') }}
            </div>
        </div>

        <div class="panel-body">
            <a href="{{ route('dashboard.users.create') }}"
               class="btn btn-success">
                <i class="fa fa-plus"></i>&nbsp;
                {{ __('views/dashboard/users/index.create-user') }}
            </a>
        </div>
    </div>

    {{-- User list --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-users"></i>&nbsp;
                {{ __('views/dashboard/users/index.user-list') }}
            </div>
        </div>

        <div class="panel-body">
            @include('components.user.list', [
                'users' => $users,
            ])
        </div>
    </div>
@endsection