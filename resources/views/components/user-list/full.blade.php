@php
    /**
     * @var \App\Models\User[] $users
     */
@endphp

<p>
    @php($userCount = count($users))

    {!! Lang::choice(__('models/user.misc.found-count', [
        'count' => $userCount,
    ]), $userCount) !!}
</p>

@include('components.user-list.compact', [
    'users' => $users,
])