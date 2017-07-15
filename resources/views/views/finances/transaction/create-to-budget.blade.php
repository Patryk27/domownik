@php
    /**
     * @var \App\Models\Budget $budget
     */
@endphp

@extends('layouts.app.auth')

@section('title')
    {{ __('views/finances/transaction/create-to-budget.page.title', [
        'budgetName' => $budget->name
    ]) }}
@endsection

@section('content')
    @include('views.finances.transaction.common.create-edit', [
        'budget' => $budget,
        'transaction' => null,
    ])
@endsection