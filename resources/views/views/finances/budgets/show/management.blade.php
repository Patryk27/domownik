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
        <a href="{{ route('finances.transaction.create-to-budget', $budget->id) }}"
           class="btn btn-success">
            <i class="fa fa-plus"></i>&nbsp;
            {{ __('views/finances/budgets/show.management.create-transaction') }}
        </a>

        &nbsp;

        <a href="{{ route('finances.transaction.list-from-budget', $budget->id) }}"
           class="btn btn-info">
            <i class="fa fa-list"></i>&nbsp;
            {{ __('views/finances/budgets/show.management.list-transactions') }}
        </a>
    </div>
</div>