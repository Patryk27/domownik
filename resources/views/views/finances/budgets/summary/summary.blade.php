@php
    /**
     * @var \App\ValueObjects\Budget\Summary $summary
     */
@endphp

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <i class="fa fa-filter"></i>&nbsp;
            {{ __('views/finances/budgets/summary.summary.title', [
                'year' => sprintf('%04d', $summary->getYear()),
                'month' => sprintf('%02d', $summary->getMonth()),
            ]) }}
        </div>
    </div>

    <div class="panel-body">
        @include('views.finances.budgets.summary.summary.table')
        <hr>
        @include('views.finances.budgets.summary.summary.chart')
    </div>
</div>