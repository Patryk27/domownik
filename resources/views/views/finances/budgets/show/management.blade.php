<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <i class="fa fa-cog"></i>&nbsp;
            {{ __('views/finances/budgets/show.management.header', [
                'budgetName' => $budget->name,
            ]) }}
        </div>
    </div>

    <div class="panel-body">
        <div class="inline-block right-divider">
            <a href="{{ route('finances.budgets.edit', $budget->id) }}"
               class="btn btn-primary">
                <i class="fa fa-gear"></i>&nbsp;
                {{ __('views/finances/budgets/show.management.edit-budget') }}
            </a>
        </div>

        <a href="{{ route('finances.budgets.transactions.create', $budget->id) }}"
           class="btn btn-success">
            <i class="fa fa-plus"></i>&nbsp;
            {{ __('views/finances/budgets/show.management.create-transaction') }}
        </a>

        <a href="{{ route('finances.budgets.transactions.index', $budget->id) }}"
           class="btn btn-info">
            <i class="fa fa-list"></i>&nbsp;
            {{ __('views/finances/budgets/show.management.list-transactions') }}
        </a>
    </div>
</div>