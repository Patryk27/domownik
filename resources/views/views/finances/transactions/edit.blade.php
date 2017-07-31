@php
    /**
     * @var \App\Models\Transaction $transaction
     * @var \App\Models\Model $transactionParent
     */
@endphp

@extends('layouts.app.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-edit"></i>&nbsp;
                {{ __('views/finances/transactions/edit.page.title', [
                    'transactionName' => $transaction->name
                ]) }}
            </div>
        </div>

        @include('views.finances.transactions.create-edit.form', [
            'transaction' => $transaction,
            'transactionParent' => $transactionParent,
        ])
    </div>
@endsection