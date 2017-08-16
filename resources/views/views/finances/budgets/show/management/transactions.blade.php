@php
    /**
     * @var \App\Models\Budget $budget
     */
@endphp

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <i class="fa fa-cog"></i>&nbsp;
            {{ __('views/finances/budgets/show.management.transactions.header') }}
        </div>
    </div>

    <div class="panel-body">
        {{-- Create transaction --}}
        <a class="btn btn-success" href="{{ route('finances.budgets.transactions.create', $budget->id) }}">
            <i class="fa fa-plus"></i>&nbsp;
            {{ __('views/finances/budgets/show.management.transactions.create') }}
        </a>

        {{-- List transactions --}}
        <a class="btn btn-info" href="{{ route('finances.budgets.transactions.index', $budget->id) }}" a>
            <i class="fa fa-list"></i>&nbsp;
            {{ __('views/finances/budgets/show.management.transactions.list') }}
        </a>
    </div>
</div>