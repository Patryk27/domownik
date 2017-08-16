@php
    /**
     * @var \Illuminate\Support\Collection|\App\Models\User[] $users
     */
@endphp

<p>
    {!! Lang::choice(__('models/user.misc.found-count', [
        'count' => $users->count(),
    ]), $users->count()) !!}
</p>

<table class="table table-hover table-striped">
    <thead>
    <tr>
        <th>{{ __('models/user.fields.id') }}</th>
        <th>{{ __('models/user.fields.login') }}</th>
        <th>{{ __('models/user.fields.full-name') }}</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)
        @php
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
                    {{ __('views/dashboard/users/index.users-table.body.btn-edit') }}
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>