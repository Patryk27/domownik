@php
    /**
     * @var \App\Models\Budget $budget
     * @var \App\Models\Transaction[] $transactions
     * @var string $dateFrom
     * @var string $dateTo
     */
@endphp

@extends('layouts.app.auth')

@section('title')
    {{ __('views/finances/budget/show-incoming-transactions.page.title', [
        'budgetName' => $budget->name,
    ]) }}
@endsection

@section('content')
    <form action="#" method="post">
        {{ csrf_field() }}

        <div class="form-inline">
            <label>
                {{ __('views/finances/budget/show-incoming-transactions.date-from') }}
            </label>

            <div class="horizontal-gutters">
                {!!
                    Form::textInput()
                        ->setIdAndName('dateFrom')
                        ->setValue($dateFrom)
                        ->setRightAddonIcon('glyphicon glyphicon-calendar')
                 !!}
            </div>
        </div>

        <div class="form-inline">
            <label>
                {{ __('views/finances/budget/show-incoming-transactions.date-to') }}
            </label>

            <div class="horizontal-gutters">
                {!!
                    Form::textInput()
                        ->setIdAndName('dateTo')
                        ->setValue($dateTo)
                        ->setRightAddonIcon('glyphicon glyphicon-calendar')
                 !!}
            </div>
        </div>

        <div>
            <div class="horizontal-gutters">
                <button type="submit" class="btn btn-success">
                    {{ __('views/finances/budget/show-incoming-transactions.submit') }}&nbsp;
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <hr>

    @include('components.transaction-list.full', [
        'transactions' => $transactions,
    ])
@endsection

@push('scripts')
<script>
  $(function() {
    $('#dateFrom, #dateTo').datepicker({
      startDate: new Date(),
    });
  });
</script>
@endpush