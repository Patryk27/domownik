@php
    /**
     * @var \App\Models\Budget $budget
     * @var \App\Models\Transaction[] $recentTransactions
     * @var \App\ValueObjects\ScheduledTransaction[] $incomingTransactions
     * @var array $recentTransactionsChart
     */
@endphp

@extends('layouts.app.auth')

@push('scripts')
<script>
  var AppView = App.Views.Finances.Budget.Show.initializeView({
    budgetId: {{ $budget->id }},
    recentTransactionsChart: {{ $recentTransactionsChart }}
  });
</script>
@endpush

@section('title')
    {{ __('views/finances/budget/show.page.title', [
        'budgetName' => $budget->name,
    ]) }}
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-cog"></i>&nbsp;
                {{ __('views/finances/budget/show.budget-management.header', [
                    'budgetName' => $budget->name,
                ]) }}
            </div>
        </div>

        <div class="panel-body">
            <a href="{{ route('finances.transaction.create-to-budget', $budget->id) }}"
               class="btn btn-success">
                <i class="fa fa-plus"></i>&nbsp;
                {{ __('views/finances/budget/show.budget-management.create-transaction') }}
            </a>

            &nbsp;

            <a href="{{ route('finances.transaction.list-from-budget', $budget->id) }}"
               class="btn btn-info">
                <i class="fa fa-list"></i>&nbsp;
                {{ __('views/finances/budget/show.budget-management.list-transactions') }}
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Recently booked transactions --}}
        <div class="col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <i class="fa fa-history"></i>&nbsp;
                        {{ __('views/finances/budget/show.recently-booked-transactions.header') }}

                        <a class="btn btn-xs btn-default pull-right"
                           href="{{ route('finances.budget.show-recent-transactions', $budget->id) }}">
                            {{ __('views/finances/budget/show.recently-booked-transactions.show-more') }}
                        </a>
                    </div>
                </div>

                <div class="panel-body">
                    @include('components.transaction-list.compact', [
                        'transactions' => $recentTransactions,
                        'transactionButtons' => ['edit', 'edit-parent'],
                    ])
                </div>
            </div>
        </div>

        {{-- Incoming transactions --}}
        <div class="col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <i class="fa fa-calendar"></i>&nbsp;
                        {{ __('views/finances/budget/show.incoming-transactions.header') }}

                        <a class="btn btn-xs btn-default pull-right"
                           href="{{ route('finances.budget.show-incoming-transactions', $budget->id) }}">
                            {{ __('views/finances/budget/show.incoming-transactions.show-more') }}
                        </a>
                    </div>
                </div>

                <div class="panel-body">
                    @include('components.transaction-list.compact', [
                        'transactions' => $incomingTransactions,
                        'transactionButtons' => ['edit', 'edit-parent'],
                    ])
                </div>
            </div>
        </div>
    </div>

    {{-- Budget history --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-database"></i>&nbsp;
                {{ __('views/finances/budget/show.history.header') }}

                <div style="display:inline-block">
                    {!!
                    Form::select()
                        ->setIdAndName('budget-history-group-mode')
                        ->setItems(function() {
                            $items = [
                                \App\Services\Transaction\HistoryCollectorContract::GROUP_MODE_DAILY,
                                \App\Services\Transaction\HistoryCollectorContract::GROUP_MODE_WEEKLY,
                                \App\Services\Transaction\HistoryCollectorContract::GROUP_MODE_MONTHLY,
                                \App\Services\Transaction\HistoryCollectorContract::GROUP_MODE_YEARLY,
                            ];

                            $result = [];

                            foreach ($items as $item) {
                                $result[$item] = __(sprintf('views/finances/budget/show.history-group-mode.%s', $item));
                            }

                            return $result;
                        })
                    !!}
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div id="budget-history">
                @include('common.ajax.loader', [
                    'icon' => true,
                    'label' => true,
                ])
            </div>
        </div>
    </div>
@endsection