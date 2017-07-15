@extends('layouts.app.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-plus-square"></i>&nbsp;
                {{ __('views/dashboard/user/create.page.title') }}
            </div>
        </div>

        @include('views.dashboard.user.common.create-edit', [
            'user' => null,
        ])
    </div>
@endsection