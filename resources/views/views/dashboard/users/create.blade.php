@extends('layouts.app.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-plus-square"></i>&nbsp;
                {{ __('views/dashboard/users/create.page.title') }}
            </div>
        </div>

        @include('views.dashboard.users.create-edit.form')
    </div>
@endsection