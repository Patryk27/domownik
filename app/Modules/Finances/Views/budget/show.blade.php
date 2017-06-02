@php
    /**
     * @var \App\Modules\Finances\Models\Budget $budget
     * @var \App\Modules\Finances\Models\Transaction[] $recentlyBookedTransactions
     * @var \App\Modules\Finances\ValueObjects\ScheduledTransaction[] $incomingTransactions
     */
@endphp

@extends('layouts/auth')

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

    <div class="row">
        <div class="col-md-12">
            <h4>
                {{ __('Finances::views/budget/show.budget-charts.header') }}
            </h4>
        </div>
    </div>
@endsection