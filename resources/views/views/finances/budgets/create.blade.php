@extends('layouts.app.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-money"></i>&nbsp;
                {{ __('views/finances/budgets/create.page.title') }}
            </div>
        </div>

        @include('views.finances.budgets.create-edit.form', [
            'budget' => null,
            'availableConsolidatedBudgets' => $availableConsolidatedBudgets,
        ])
    </div>
@endsection