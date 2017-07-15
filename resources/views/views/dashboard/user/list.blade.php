@php
    /**
     * @var $users \App\Models\User[]
     */
@endphp

@extends('layouts.app.auth')

@section('title')
    {{ __('views/dashboard/user/list.page.title') }}
@endsection

@section('content')
    <a href="{{ route('dashboard.user.create') }}"
       class="btn btn-success">
        <i class="fa fa-plus"></i>&nbsp;
        {{ __('views/dashboard/user/list.create-new-user') }}
    </a>

    <hr>

    <p>
        @php($userCount = count($users))

        {!! Lang::choice(__('views/dashboard/user/list.found-user-count', [
            'userCount' => $userCount,
        ]), $userCount) !!}
    </p>

    <table class="table table-hover table-striped">
        <thead>
        <tr>
            <th>{{ __('views/dashboard/user/list.users-table.header.user-id') }}</th>
            <th>{{ __('views/dashboard/user/list.users-table.header.user-login') }}</th>
            <th>{{ __('views/dashboard/user/list.users-table.header.user-full-name') }}</th>
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

                $trClass = '';

                if ($user->status === \App\Models\User::STATUS_INACTIVE) {
                    $trClass = 'warning';
                }
            @endphp
            <tr class="{{ $trClass }}">
                <td>{{ $user->id }}</td>
                <td>{{ $user->login }}</td>
                <td>{{ $user->full_name }}</td>
                <td>
                    <a class="btn btn-xs btn-primary"
                       href="{{ $userPresenter->getEditUrl() }}">
                        <i class="fa fa-gear"></i>&nbsp;
                        {{ __('views/dashboard/user/list.users-table.body.btn-edit') }}
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection