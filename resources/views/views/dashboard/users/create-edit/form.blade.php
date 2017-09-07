@php
    /**
     * @var \App\Models\User|null $user
     */
@endphp

@php
    $isModifying = isset($user);
    $isCreating = !$isModifying;
@endphp

{!! Form::model($user, [
    'url' => $form['url'],
    'method' => $form['method'],
    'class' => 'form-ajax',
]) !!}

<div class="panel-body">
    {{-- Login --}}
    <div class="form-group required">
        {!! Form::label('login', __('views/dashboard/users/create-edit/form.login.label')) !!}
        {!! Form::text('login', null, ['class' => 'form-control', 'required' => true, 'autofocus']) !!}
    </div>

    {{-- Full name --}}
    <div class="form-group required">
        {!! Form::label('full_name', __('views/dashboard/users/create-edit/form.full-name.label')) !!}
        {!! Form::text('full_name', null, ['class' => 'form-control']) !!}
    </div>

    {{-- Password and password confirmation --}}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group required">
                {!! Form::label('password', __('views/dashboard/users/create-edit/form.password.label')) !!}
                {!! Form::password('password', ['class' => 'form-control', 'required' => $isCreating]) !!}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group required">
                {!! Form::label('password_confirm', __('views/dashboard/users/create-edit/form.password-confirm.label')) !!}
                {!! Form::password('password_confirm', ['class' => 'form-control', 'required' => $isCreating]) !!}
            </div>
        </div>
    </div>

    {{-- Status --}}
    <div class="form-group required">
        {!! Form::label('status', __('views/dashboard/users/create-edit/form.status.label')) !!}
        {!! Form::select('status', \App\Models\User::getStatusesSelect(), null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="panel-footer">
    @include('components.form.buttons.save')

    @isset($user)
        @include('components.form.buttons.delete', [
            'url' => route('dashboard.users.destroy', $user),
            'message' => __('requests/user/crud.prompts.delete'),
        ])
    @endisset
</div>

{!! Form::close() !!}
