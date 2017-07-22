@php
    /**
     * @var \App\Models\User $user
     */
@endphp

@extends('layouts.app.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-edit"></i>&nbsp;
                {{ __('views/dashboard/users/edit.page.title', [
                    'userName' => $user->full_name
                ]) }}
            </div>
        </div>

        @include('views.dashboard.users.partials.form')
    </div>
@endsection