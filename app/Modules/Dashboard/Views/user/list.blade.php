@php
    /**
     * @var $users \App\Models\User[]
     */
@endphp

@extends('layouts.application.auth')

@section('title')
    {{ __('Dashboard::views/user/list.page.title') }}
@endsection

@section('content')
    <p>
        @php($userCount = count($users))

        {!! Lang::choice(__('Dashboard::views/user/list.found-user-count', [
            'userCount' => $userCount,
        ]), $userCount) !!}
    </p>

    <table class="table table-hover table-striped">
        <thead>
        <tr>
            <th>{{ __('Dashboard::views/user/list.users-table.header.user-id') }}</th>
            <th>{{ __('Dashboard::views/user/list.users-table.header.user-login') }}</th>
            <th>{{ __('Dashboard::views/user/list.users-table.header.user-full-name') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            @php
                /**
                 * @var $user \App\Models\User
                 */

                $userPresenter = $user->getPresenter();
            @endphp
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->login }}</td>
                <td>{{ $user->full_name }}</td>
                <td>
                    <a class="btn btn-xs btn-default"
                       href="{{ $userPresenter->getEditUrl() }}">
                        <i class="fa fa-gear"></i>&nbsp;
                        {{ __('Dashboard::views/user/list.users-table.body.btn-edit') }}
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection