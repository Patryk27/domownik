<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <i class="fa fa-cogs"></i>&nbsp;
            {{ __('views/finances/budgets/index.budget-management') }}
        </div>
    </div>

    <div class="panel-body">
        <a href="{{ route('finances.budgets.create') }}"
           class="btn btn-success">
            <i class="fa fa-plus"></i>&nbsp;
            {{ __('views/finances/budgets/index.create-budget') }}
        </a>
    </div>
</div>