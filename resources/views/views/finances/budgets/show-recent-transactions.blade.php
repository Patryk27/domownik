@php
    /**
     * @var \App\Models\Budget $budget
     * @var \App\Models\Transaction[] $transactions
     * @var string $dateFrom
     * @var string $dateTo
     * @var int $count
     */
@endphp

@extends('layouts.app.auth')

@section('content')
    {{-- Filtering --}}
    <form action="#" method="post">
        {{ csrf_field() }}
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">
                    <i class="fa fa-filter"></i>&nbsp;
                    {{ __('views/finances/budget/show-recent-transactions.filtering-title') }}
                </div>
            </div>

            <div class="panel-body">
                <div class="row">
                    {{-- Date from --}}
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <label>
                            {{ __('views/finances/budget/show-recent-transactions.date-from') }}
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

                    {{-- Date to --}}
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <label>
                            {{ __('views/finances/budget/show-recent-transactions.date-to') }}
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

                    {{-- Transaction count --}}
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <label>
                            {{ __('views/finances/budget/show-recent-transactions.count') }}
                        </label>

                        <div class="horizontal-gutters">
                            {!!
                                Form::select()
                                    ->setIdAndName('count')
                                    ->setValue($count)
                                    ->setRequired(true)
                                    ->setHelpBlockEnabled(false)
                                    ->setItems(function() {
                                        return [
                                            null => '----',
                                            10 => '10',
                                            25 => '25',
                                            50 => '50',
                                            100 => '100',
                                            200 => '200',
                                            500 => '500',
                                        ];
                                    })
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
                {{ __('views/finances/budget/show-recent-transactions.results-title') }}
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
      endDate: new Date(),
    });
  });
</script>
@endpush