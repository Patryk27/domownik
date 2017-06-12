@extends('layouts.application.guest')

@section('content')
    <form action="{{ route('dashboard.user.login') }}" id="loginForm" method="post">
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
                ->setAutoValue(true)
                ->setAutofocus($isLoginSet)
         !!}

        <div class="checkbox">
            <label for="remember-me">
                <input
                        type="checkbox"
                        id="remember-me"
                        name="remember-me"
                        value="1"
                        placeholder=""/>
                {{ __('Dashboard::views/user/login.remember-me.label') }}
            </label>
        </div>

        <div>
            <button type="submit" class="btn btn-info">
                {{ __('Dashboard::views/user/login.submit') }}
            </button>
        </div>
    </form>
@endsection