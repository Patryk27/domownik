@php
    /**
     * @var \App\Models\Budget $budget
     */
@endphp

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <i class="fa fa-dashboard"></i>&nbsp;
            {{ __('views/finances/budgets/show.management.budget.header') }}
        </div>
    </div>

    <div class="panel-body">
        {{-- Edit budget --}}
        <a class="btn btn-primary" href="{{ route('finances.budgets.edit', $budget->id) }}">
            <i class="fa fa-gear"></i>&nbsp;
            {{ __('views/finances/budgets/show.management.budget.edit') }}
        </a>

        {{-- Show budget summary --}}
        <a class="btn btn-info" href="{{ route('finances.budgets.summary', $budget->id) }}">
            <i class="fa fa-list-alt"></i>&nbsp;
            {{ __('views/finances/budgets/show.management.budget.show-summary') }}
        </a>
    </div>
</div>