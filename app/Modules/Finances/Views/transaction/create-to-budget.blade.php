@php
    /**
     * @var \App\Modules\Finances\Models\Budget $budget
     */
@endphp

@extends('layouts.application.auth')

@section('title')
    {{ __('Finances::views/transaction/create-to-budget.page.title', [
        'budgetName' => $budget->name
    ]) }}
@endsection

@section('content')
    @include('Finances::transaction.common.create-edit', [
        'budget' => $budget,
        'transaction' => null,
    ])
@endsection