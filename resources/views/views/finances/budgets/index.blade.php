@php
    /**
     * @var \Illuminate\Support\Collection|\App\Models\Budget[] $budgets
     */
@endphp

@extends('layouts.app.auth')

@section('content')
    @include('views.finances.budgets.index.management')
    @include('views.finances.budgets.index.list')
@endsection