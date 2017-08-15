@php
    /**
     * @var \Illuminate\Support\Collection|\App\Models\Budget[] $budgets
     */
@endphp

<p>
    {!! Lang::choice(__('models/budget.misc.found-count', [
        'count' => $budgets->count(),
    ]), $budgets->count()) !!}
</p>

@include('components.budget.list.partial', [
    'budgets' => $budgets,
])