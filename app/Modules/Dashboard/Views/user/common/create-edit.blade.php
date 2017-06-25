@push('scripts')
<script>
  var AppView = App.Views.Dashboard.User.CreateEdit.initializeView();
</script>
@endpush

<form role="form"
      id="userForm"
      data-toggle="validator"
      action="{{ route('dashboard.user.store') }}"
      method="post">

    {{ csrf_field() }}

    @isset($user)
        {!!
            Form::hiddenInput()
                ->setIdAndName('userId')
                ->setValue($user->id)
        !!}
    @endisset

    {{-- User login --}}
    {!!
        Form::textInput()
            ->setIdAndName('userLogin')
            ->setLabel(__('Dashboard::views/user/common/create-edit.user-login.label'))
            ->setPlaceholder(__('Dashboard::views/user/common/create-edit.user-login.placeholder'))
            ->setAutoValue(true)
            ->setValueFromModel($user, 'login')
            ->setRequired(true)
            ->setAutofocus(true)
     !!}

    {{-- User name --}}
    {!!
        Form::textInput()
            ->setIdAndName('userFullName')
            ->setLabel(__('Dashboard::views/user/common/create-edit.user-full-name.label'))
            ->setPlaceholder(__('Dashboard::views/user/common/create-edit.user-full-name.placeholder'))
            ->setAutoValue(true)
            ->setValueFromModel($user, 'full_name')
            ->setRequired(true)
     !!}

    {{-- User password --}}
    {!!
        Form::passwordInput()
            ->setIdAndName('userPassword')
            ->setLabel(__('Dashboard::views/user/common/create-edit.user-password.label'))
            ->setAutoValue(true)
            ->setRequired(!isset($user)) // We require setting the password when user is being created but not edited
     !!}

    {{-- User status --}}
    {!!
        Form::select()
            ->setIdAndName('userStatus')
            ->setLabel(__('Dashboard::views/user/common/create-edit.user-status.label'))
            ->setAutoValue(true)
            ->setValueFromModel($user, 'status')
            ->setRequired(true)
            ->setItems(function() {
                $result = [];

                $statuses = \App\Models\User::getStatuses();

                foreach ($statuses as $status) {
                    $result[$status] = __(sprintf('Dashboard::common/user.status.%s', $status));
                }

                return $result;
            })
     !!}

    <div>
        @include('common.form.save-button')

        @isset($user)
            @include('common.form.delete-button', [
                'route' => route('dashboard.user.delete', $user->id),
                'confirmationMessage' => __('Dashboard::views/user/common/create-edit.delete-confirmation-message'),
            ])
        @endisset

        @include('common.form.required-fields')
    </div>
</form>