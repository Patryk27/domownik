@php
    /**
     * @var \App\Modules\Finances\Models\Transaction $transaction
     */
@endphp

@extends('layouts.application.auth')

@section('title')
    {{ __('Finances::views/transaction/edit.page.title', [
        'transactionName' => $transaction->name
    ]) }}
@endsection

@section('content')
    @include('Finances::transaction.common.create-edit', [
        'budget' => null,
        'transaction' => $transaction,
    ])
@endsection