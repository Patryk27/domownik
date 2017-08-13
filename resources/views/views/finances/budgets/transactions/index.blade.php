@php
    /**
     * @var \App\Models\Budget $budget
     * @var \Illuminate\Support\Collection|\App\Models\Transaction[] $transactions
     */
@endphp

@extends('layouts.app.auth')

@section('content')
    {{-- Filtering --}}
    {!! Form::open(['url' => '#', 'method' => 'post']) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-filter"></i>&nbsp;
                {{ __('views/finances/budgets/transactions.filtering-title') }}
            </div>
        </div>

        <div class="panel-body">
            <div class="row">
                {{-- Date from --}}
                <div class="col-xs-12 col-sm-6 col-md-4 form-group">
                    {!! Form::label('dateFrom', __('views/finances/budgets/transactions.date-from')) !!}

                    <div class="input-group horizontal-gutters">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-calendar"></i>&nbsp;
                        </span>

                        {!! Form::date('dateFrom', null, [
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>

                {{-- Date to --}}
                <div class="col-xs-12 col-sm-6 col-md-4 form-group">
                    {!! Form::label('dateTo', __('views/finances/budgets/transactions.date-to')) !!}

                    <div class="input-group horizontal-gutters">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-calendar"></i>&nbsp;
                        </span>

                        {!! Form::date('dateTo', null, [
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>

                {{-- Limit --}}
                <div class="col-xs-12 col-sm-12 col-md-4 form-group">
                    {!! Form::label('limit', __('views/finances/budgets/transactions.limit')) !!}

                    <div class="horizontal-gutters">
                        @php
                            $items = [
                                null => '----',
                                10 => '10',
                                25 => '25',
                                50 => '50',
                                100 => '100',
                                200 => '200',
                                500 => '500',
                            ];
                        @endphp

                        {!! Form::select('limit', $items, 100, [
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>

                {{-- Name --}}
                <div class="col-xs-12 col-sm-12 col-md-4 form-group">
                    {!! Form::label('name', __('views/finances/budgets/transactions.name')) !!}

                    <div class="input-group horizontal-gutters">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-font"></i>&nbsp;
                            </span>

                        {!! Form::text('name', null, [
                            'class' => 'form-control',
                            'autofocus',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            <button class="btn btn-success pull-right">
                <i class="fa fa-search"></i>&nbsp;
                {{ __('views/finances/budgets/transactions.submit') }}
            </button>

            <div class="clearfix"></div>
        </div>
    </div>
    {!! Form::close() !!}

    {{-- Results --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-list"></i>&nbsp;
                {{ __('views/finances/budgets/transactions.results-title') }}
            </div>
        </div>

        <div class="panel-body">
            @include('components.transaction.list.full', [
                'transactions' => $transactions,
            ])
        </div>
    </div>
@endsection