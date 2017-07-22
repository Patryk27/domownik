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
                {{ __('views/dashboard/user/list.user-management') }}
            </div>
        </div>

        <div class="panel-body">
            <a href="{{ route('dashboard.users.create') }}"
               class="btn btn-success">
                <i class="fa fa-plus"></i>&nbsp;
                {{ __('views/dashboard/user/list.create-user') }}
            </a>
        </div>
    </div>

    {{-- User list --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-users"></i>&nbsp;
                {{ __('views/dashboard/user/list.user-list') }}
            </div>
        </div>

        <div class="panel-body">
            <p>
                @php($userCount = count($users))

                {!! Lang::choice(__('common/user.misc.found-count', [
                    'count' => $userCount,
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
        </div>
    </div>
@endsection