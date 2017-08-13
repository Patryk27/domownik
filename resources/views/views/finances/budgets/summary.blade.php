@php
    /**
     * @var \App\Models\Budget $budget
     * @var int $startingYear
     */
@endphp

@extends('layouts.app.auth')

@section('content')
    @include('views.finances.budgets.summary.filtering')
@endsection