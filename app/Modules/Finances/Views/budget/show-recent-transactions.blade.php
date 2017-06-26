@php
    /**
     * @var \App\Modules\Finances\Models\Budget $budget
     * @var \App\Modules\Finances\Models\Transaction[] $transactions
     */
@endphp

@extends('layouts.application.auth')

@section('title')
    {{ __('Finances::views/budget/show-recent-transactions.page.title', [
        'budgetName' => $budget->name,
    ]) }}
@endsection

@section('content')
    <form id="recentTransactionsForm"
          action="#"
          method="get">
        <div class="form-inline">
            <label>
                {{ __('Finances::views/budget/show-recent-transactions.show-recent-nth-pre') }}
            </label>

            <div class="horizontal-gutters">
                {!!
                    Form::select()
                        ->setIdAndName('count')
                        ->setValue($filterCount)
                        ->setRequired(true)
                        ->setHelpBlockEnabled(false)
                        ->setItems(function() {
                            return [
                                10 => '10',
                                25 => '25',
                                50 => '50',
                                100 => '100',
                                200 => '200',
                                500 => '500',
                            ];
                        })
                 !!}
            </div>

            <label>
                {{ __('Finances::views/budget/show-recent-transactions.show-recent-nth-post') }}
            </label>

            <div class="horizontal-gutters">
                <button type="submit"
                        class="btn btn-success">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <hr>

    @include('Finances::common.transaction-list.full', [
        'transactions' => $transactions,
    ])
@endsection