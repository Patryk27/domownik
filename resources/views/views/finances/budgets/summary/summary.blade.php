@php
    /**
     * @var BudgetSummary $summary
     */
@endphp

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <i class="fa fa-filter"></i>&nbsp;
            {{ __('views/finances/budgets/summary.summary.title', [
                'year' => $summary->getYear(),
                'month' => $summary->getMonth(),
            ]) }}
        </div>
    </div>
</div>