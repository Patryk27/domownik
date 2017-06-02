@php
    /**
     * @var \App\Modules\Finances\Models\Transaction $transaction
     */
@endphp

@extends('Finances::transaction.create-edit.page')

@section('title')
    {{ __('Finances::views/transaction/edit.page.title', [
        'transactionName' => $transaction->name
    ]) }}
@endsection

@section('transaction-create-edit-form-partial')
    {!!
        Form::hiddenInput()
            ->setIdAndName('transactionId')
            ->setValue($transaction->id)
     !!}
@endsection