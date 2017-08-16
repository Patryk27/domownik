@php
    /**
     * @var \Illuminate\Support\Collection|\App\Models\Budget[] $budgets
     */
@endphp

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <i class="fa fa-list"></i>&nbsp;
            {{ __('views/finances/budgets/index.budget-list') }}
        </div>
    </div>

    <div class="panel-body">
        @include('components.budget.list', [
            'budgets' => $budgets,
        ])
    </div>
</div>