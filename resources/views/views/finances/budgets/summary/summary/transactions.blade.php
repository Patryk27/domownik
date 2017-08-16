@php
    /**
     * @var \App\ValueObjects\Budget\Summary $summary
     */
@endphp

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <i class="fa fa-table"></i>&nbsp;
            {{ __('views/finances/budgets/summary.transactions.title', [
                'year' => sprintf('%04d', $summary->getYear()),
                'month' => sprintf('%02d', $summary->getMonth()),
            ]) }}
        </div>
    </div>

    <div class="panel-body">
        @include('components.transaction.list', [
            'transactions' => $summary->getTransactions(),
            'options' => [
                'buttons' => ['edit'],
                'highlightFuture' => true,
                'showCounter' => true,
                'showRowCounter' => true,
            ],
        ])
    </div>
</div>