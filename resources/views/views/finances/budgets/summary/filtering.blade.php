@php
    /**
     * @var int $startingYear
     */
@endphp

{!! Form::open(['method' => 'get']) !!}

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <i class="fa fa-filter"></i>&nbsp;
            {{ __('views/finances/budgets/summary.filtering.title') }}
        </div>
    </div>

    <div class="panel-body">
        <div class="row">
            {{-- Select year --}}
            <div class="col-xs-12 col-sm-12 col-md-2 form-group">
                {!! Form::select('year', Calendar::getYearsMap($startingYear, $startingYear + 1), date('Y'), [
                    'class' => 'form-control',
                ]) !!}
            </div>

            {{-- Select month --}}
            <div class="col-xs-12 col-sm-12 col-md-2 form-group">
                {!! Form::select('month', Calendar::getMonthsMap(), date('m'), [
                    'class' => 'form-control',
                ]) !!}
            </div>

            <div class="col-xs-12 col-sm-12 col-md-2">
                {{-- Submit --}}
                <button class="btn btn-success">
                    <i class="fa fa-search"></i>&nbsp;
                    {{ __('views/finances/budgets/summary.filtering.submit') }}
                </button>
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}