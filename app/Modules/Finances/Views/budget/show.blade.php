@php
    /**
     * @var \App\Modules\Finances\Models\Budget $budget
     * @var \App\Modules\Finances\Models\Transaction[] $recentlyBookedTransactions
     * @var \App\Modules\Finances\ValueObjects\ScheduledTransaction[] $incomingTransactions
     * @var array $budgetHistory
     */
@endphp

@extends('layouts/auth')

@push('scripts')
<script>
  var AppView = App.Views.Finances.Budget.Show.initializeView({
    budgetId: {{ $budget->id }},
    budgetHistoryRows: {{ $budgetHistoryRows }}
  });
</script>
@endpush

@section('title')
    {{ __('Finances::views/budget/show.page.title', [
        'budgetName' => $budget->name,
    ]) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4>
                {{ __('Finances::views/budget/show.budget-management.header') }}
            </h4>

            <a href="{{ route('finances.transaction.createToBudget', $budget->id) }}" class="btn btn-sm btn-success">
                <i class="fa fa-plus"></i>&nbsp;
                {{ __('Finances::views/budget/show.budget-management.create-transaction') }}
            </a>

            <a href="{{ route('finances.transaction.listFromBudget', $budget->id) }}" class="btn btn-sm btn-info">
                <i class="fa fa-list"></i>&nbsp;
                {{ __('Finances::views/budget/show.budget-management.list-transactions') }}
            </a>
        </div>
    </div>

    <hr>

    <div class="row">
        {{-- Recently booked transactions --}}
        <div class="col-sm-12 col-md-6">
            <h4>
                {{ __('Finances::views/budget/show.recently-booked-transactions.header') }}

                <a class="btn btn-xs btn-default pull-right">
                    {{ __('Finances::views/budget/show.recently-booked-transactions.show-more') }}
                </a>
            </h4>

            @include('Finances::common.transaction-list.compact', [
                'transactions' => $recentlyBookedTransactions,
                'transactionButtons' => ['edit', 'edit-parent'],
            ])
        </div>

        {{-- Incoming transactions --}}
        <div class="col-sm-12 col-md-6">
            <h4>
                {{ __('Finances::views/budget/show.incoming-transactions.header') }}

                <a class="btn btn-xs btn-default pull-right">
                    {{ __('Finances::views/budget/show.incoming-transactions.show-more') }}
                </a>
            </h4>

            @include('Finances::common.transaction-list.compact', [
                'transactions' => $incomingTransactions,
                'transactionButtons' => ['edit', 'edit-parent'],
            ])
        </div>
    </div>

    <hr>

    {{-- Budget history --}}
    <div class="row">
        <div class="col-md-12">
            <h4>
                {{ __('Finances::views/budget/show.history.header') }}

                &nbsp;

                <div style="display:inline-block">
                    {!!
                    Form::select()
                        ->setIdAndName('budget-history-group-mode')
                        ->setItems(function() {
                            $items = [
                                \App\Modules\Finances\Services\Transaction\HistoryCollectorServiceContract::GROUP_MODE_DAILY,
                                \App\Modules\Finances\Services\Transaction\HistoryCollectorServiceContract::GROUP_MODE_WEEKLY,
                                \App\Modules\Finances\Services\Transaction\HistoryCollectorServiceContract::GROUP_MODE_MONTHLY,
                                \App\Modules\Finances\Services\Transaction\HistoryCollectorServiceContract::GROUP_MODE_YEARLY,
                            ];

                            $result = [];

                            foreach ($items as $item) {
                                $result[$item] = __(sprintf('Finances::views/budget/show.history-group-mode.%s', $item));
                            }

                            return $result;
                        })
                    !!}
                </div>
            </h4>

            <div id="budget-history">
                @include('common.ajax.loader', [
                    'icon' => true,
                    'label' => true,
                ])
            </div>
        </div>
    </div>
@endsection