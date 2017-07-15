@php
    /**
     * @var \App\Models\Transaction $transaction
     */
@endphp

@extends('layouts.app.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-edit"></i>&nbsp;
                {{ __('views/finances/transaction/edit.page.title', [
                    'transactionName' => $transaction->name
                ]) }}
            </div>
        </div>

        @include('views.finances.transaction.common.create-edit', [
            'budget' => null,
            'transaction' => $transaction,
        ])
    </div>
@endsection