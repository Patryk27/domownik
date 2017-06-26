@extends('layouts.application.guest')

@section('content')
    <div id="loginFormWrapper" class="well">
        <form action="{{ route('dashboard.user.login') }}"
              id="loginForm"
              method="post">

            {{ csrf_field() }}

            <div>
                <img src="{{ asset('images/login-dummy-avatar.png') }}" class="dummy-avatar"/>
            </div>

            @php($isLoginSet = !empty(old('login')))

            {{-- Login --}}
            {!!
                Form::textInput()
                    ->setIdAndName('login')
                    ->setPlaceholder(__('Dashboard::views/user/login.login.placeholder'))
                    ->setLeftAddonIcon('fa fa-user')
                    ->setAutoValue(true)
                    ->setAutofocus(!$isLoginSet)
             !!}

            {{-- Password --}}
            {!!
                Form::passwordInput()
                    ->setIdAndName('password')
                    ->setPlaceholder(__('Dashboard::views/user/login.password.placeholder'))
                    ->setLeftAddonIcon('fa fa-key')
                    ->setAutofocus($isLoginSet)
             !!}

            {{-- Remember me --}}
            {!!
                Form::checkbox()
                    ->setIdAndName('remember-me')
                    ->setLabel(__('Dashboard::views/user/login.remember-me.label'))
                    ->setValue(1)
             !!}

            <div>
                <button type="submit" class="btn btn-success">
                    {{ __('Dashboard::views/user/login.submit') }}
                </button>
            </div>
        </form>
    </div>
@endsection