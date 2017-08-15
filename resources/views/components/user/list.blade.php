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

@include('components.user.list.partial', [
    'users' => $users,
])