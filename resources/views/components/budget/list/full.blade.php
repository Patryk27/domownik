@php
    /**
     * @var \Illuminate\Support\Collection|\App\Models\Budget[] $budgets
     */
@endphp

<p>
    @php($budgetCount = count($budgets))

        {!! Lang::choice(__('models/budget.misc.found-count', [
            'count' => $budgetCount,
        ]), $budgetCount) !!}
</p>

@include('components.budget.list.compact', [
    'budgets' => $budgets,
])