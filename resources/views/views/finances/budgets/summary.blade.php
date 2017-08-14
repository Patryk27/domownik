@php
    /**
     * @var \App\Models\Budget $budget
     * @var int $startingYear
     * @var \App\ValueObjects\Budget\Summary $summary
     */
@endphp

@extends('layouts.app.auth')

@section('content')
    @include('views.finances.budgets.summary.filtering')

    @isset($summary)
        @include('views.finances.budgets.summary.summary')
    @endisset
@endsection