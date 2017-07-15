@php
    /**
     * @var \App\Models\Transaction $transaction
     */
@endphp

@extends('layouts.app.auth')

@section('title')
    {{ __('views/finances/transaction/edit.page.title', [
        'transactionName' => $transaction->name
    ]) }}
@endsection

@section('content')
    @include('views.finances.transaction.common.create-edit', [
        'budget' => null,
        'transaction' => $transaction,
    ])
@endsection