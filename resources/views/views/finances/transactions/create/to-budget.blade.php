@php
    /**
     * @var \App\Models\Transaction|null $transaction
     * @var \App\Models\Budget $transactionParent
     */
@endphp

@extends('layouts.app.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-plus-square"></i>&nbsp;
                {{ __('views/finances/transactions/create/to-budget.page.title', [
                    'budgetName' => $transactionParent->name
                ]) }}
            </div>
        </div>

        @include('views.finances.transactions.create-edit.form', [
            'transaction' => $transaction,
            'transactionParent' => $transactionParent,
        ])
    </div>
@endsection