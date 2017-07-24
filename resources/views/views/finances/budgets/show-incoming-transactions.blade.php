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
    {{-- Filtering --}}
    <form action="#" method="post">
        {{ csrf_field() }}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">
                    <i class="fa fa-filter"></i>&nbsp;
                    {{ __('views/finances/budget/show-incoming-transactions.filtering-title') }}
                </div>
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
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

                    <div class="col-xs-12 col-sm-12 col-md-6">
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
                </div>
            </div>

            <div class="panel-footer">
                <button type="submit" class="btn btn-success pull-right">
                    <i class="fa fa-search"></i>&nbsp;
                    {{ __('views/finances/budget/show-incoming-transactions.submit') }}
                </button>

                <div class="clearfix"></div>
            </div>
        </div>
    </form>

    {{-- Results --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-list"></i>&nbsp;
                {{ __('views/finances/budget/show-incoming-transactions.results-title') }}
            </div>
        </div>

        <div class="panel-body">
            @include('components.transaction-list.full', [
                'transactions' => $transactions,
            ])
        </div>
    </div>
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