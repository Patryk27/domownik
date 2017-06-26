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


    @include('Finances::common.transaction-list.full', [
        'transactions' => $transactions,
    ])
@endsection