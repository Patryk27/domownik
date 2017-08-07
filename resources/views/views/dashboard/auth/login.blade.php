@extends('layouts.app.guest')

@section('content')
    <div id="loginFormWrapper" class="well">
        {!! Form::open([
            'route' => 'dashboard.auth.login',
            'method' => 'post',
            'id' => 'loginForm',
            'data-toggle' => 'validator',
        ]) !!}

        <img src="{{ asset('images/login-dummy-avatar.png') }}" class="dummy-avatar"/>

        @php
            $isLoginSet = !empty(old('login'))
        @endphp

        {{-- Login --}}
        <div class="form-group">
            {!! Form::text('login', null, [
                'class' => 'form-control',
                'placeholder' => __('views/dashboard/auth/login.login.placeholder'),
                'autofocus' => !$isLoginSet,
            ]) !!}
        </div>

        {{-- Password --}}
        <div class="form-group">
            {!! Form::password('password', [
                'class' => 'form-control',
                'placeholder' => __('views/dashboard/auth/login.password.placeholder'),
                'autofocus' => $isLoginSet,
            ]) !!}
        </div>

        {{-- Remember me --}}
        <div class="checkbox">
            <label>
                {!! Form::checkbox('remember_me') !!}
                {{ __('views/dashboard/auth/login.remember-me.label') }}
            </label>
        </div>

        <div>
            <button class="btn btn-success">
                {{ __('views/dashboard/auth/login.submit') }}
            </button>
        </div>

        {!! Form::close() !!}
    </div>
@endsection