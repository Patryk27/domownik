@php
    /**
     * @var \App\Models\Budget $budget
     * @var \App\Models\Transaction[] $recentTransactions
     * @var \App\ValueObjects\ScheduledTransaction[] $incomingTransactions
     * @var string $recentTransactionsChart
     */
@endphp

@extends('layouts.app.auth')

@push('scripts')
<script>
  var AppView = App.Views.Finances.Budget.Show.initializeView({
    budgetId: {{ $budget->id }},
    recentTransactionsChart: {{ $recentTransactionsChart }}
  });
</script>
@endpush

@section('title')
    {{ __('views/finances/budget/show.page.title', [
        'budgetName' => $budget->name,
    ]) }}
@endsection

@section('content')
    @include('views.finances.budgets.show.management')
    @include('views.finances.budgets.show.recent-and-incoming-transactions')
    {{-- @include('views.finances.budgets.show.history') --}}
@endsection